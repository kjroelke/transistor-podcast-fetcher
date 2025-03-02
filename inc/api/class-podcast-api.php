<?php
/**
 * Podcast API
 *
 * The Class that handles the Transistor API Request
 *
 * @package KJR_Dev
 */

namespace KJR_Dev\Podcast_API\Api;

use WP_Post;

/**
 * This class protects and manages the API Request that a Cron job will make.
 */
class Podcast_API extends API {
	/**
	 * The episode data
	 *
	 * @var $episode
	 */
	protected Episode_Attributes $episode;

	/**
	 * The episode ID from transistor
	 *
	 * @var $episode_id
	 */
	private int $episode_id;

	/**
	 * The "Podcast" Category ID on the Production Site
	 *
	 * @var $production_env_category_id
	 */
	private int $production_env_category_id = 164;

	/**
	 * Fetches the episodes data for a show and loops through the pages to fetch all episodes
	 */
	public function fetch_episodes() {
		$data         = $this->get_episode_data( array( 'pagination[page]' => '1' ) );
		$current_page = intval( $data['meta']['currentPage'] );
		$total_pages  = intval( $data['meta']['totalPages'] );
		while ( $current_page <= $total_pages ) {
			$this->fetch_all_episodes( $current_page );
			++$current_page;
		}
	}

	/**
	 * Fetches all the episodes
	 *
	 * @param int $current_page The current page
	 */
	private function fetch_all_episodes( int $current_page ) {
		$data = $this->get_episode_data( array( 'pagination[page]' => $current_page ) );
		foreach ( $data['data'] as $episode ) {
			$this->create_episode_post( $episode );
		}
	}

	/**
	 * Creates new Post for each artist in the `artist_data` object with `wp_insert_post`
	 */
	public function get_latest_episode() {
		$data    = $this->get_episode_data();
		$episode = $data['data'][0];
		$this->create_episode_post( $episode );
	}

	/**
	 * Creates a new Post for the episode
	 *
	 * @param array $episode The episode data
	 */
	private function create_episode_post( array $episode ) {
		$latest_episode   = new Episode_Attributes( $episode['attributes'] );
		$this->episode    = $latest_episode;
		$this->episode_id = intval( $episode['id'] );
		$new_episode_post = $this->wp_friendly_array( $latest_episode );
		remove_filter( 'content_save_pre', 'wp_filter_post_kses' );
		remove_filter( 'content_filtered_save_pre', 'wp_filter_post_kses' );
		$post_exists = $this->check_if_post_exists( $latest_episode );
		$episode_id  = null;
		if ( $post_exists ) {
			$episode_id = $post_exists->ID;
			wp_update_post(
				array(
					'ID'           => $post_exists->ID,
					'post_title'   => $latest_episode->title,
					'post_content' => $this->set_the_post_content( $latest_episode ),
				)
			);
		} else {
			$episode_id = wp_insert_post( $new_episode_post, true );
		}
		add_filter( 'content_save_pre', 'wp_filter_post_kses' );
		add_filter( 'content_filtered_save_pre', 'wp_filter_post_kses' );
		if ( is_wp_error( $episode_id ) ) {
			$error = $episode_id->get_error_message();
		} else {
			$this->set_artwork( $episode_id );
		}
	}

	/**
	 * Create a WordPress Friendly Array
	 *
	 * @param Episode_Attributes $episode the episode
	 */
	private function wp_friendly_array( Episode_Attributes $episode ): array {
		$content = $this->set_the_post_content( $episode );
		$excerpt = $this->get_the_post_excerpt( $episode->formatted_description );

		$new_episode_post = array(
			'post_title'    => $episode->title,
			'post_type'     => 'episodes',
			'post_status'   => 'publish',
			'post_date'     => $episode->published_at,
			'post_content'  => $content,
			'post_category' => array( $this->production_env_category_id ),
			'meta_input'    => array( 'transistor_id' => $this->episode_id ),
		);
		if ( $excerpt ) {
			$new_episode_post['post_excerpt'] = $excerpt;
		}
		return $new_episode_post;
	}

	/**
	 * Checks if a Post exists with a given Transistor ID
	 *
	 * @return WP_Post|false the Post or false if it doesn't exist
	 */
	private function check_if_post_exists(): WP_Post|false {
		$posts = get_posts(
			array(
				'post_type'      => 'episodes',
				'post_status'    => 'publish',
				'meta_key'       => 'transistor_id',
				'meta_value'     => $this->episode_id,
				'posts_per_page' => 1,
			)
		);
		return count( $posts ) > 0 ? $posts[0] : false;
	}

	/**
	 * Sets the Post Content to the Show Description & Embedded HTML Player
	 *
	 * @param Episode_Attributes $episode the Episode
	 */
	private function set_the_post_content( Episode_Attributes $episode ): string {
		$content = $episode->embed_html . $episode->formatted_description;
		return $content;
	}

	/**
	 * Gets the Post excerpt
	 *
	 * @param string $description the Episode_Attributes->formatted_description
	 * @return string|false returns the excerpt or false
	 */
	private function get_the_post_excerpt( string $description ) {
		$html_parts = explode( '---', $description );
		// Get the content before '---' (if it exists)
		if ( count( $html_parts ) > 0 ) {
			$html_before_dash = $html_parts[0];
			return $html_before_dash;
		} else {
			return false;
		}
	}

	/** Sets the Show Art
	 *
	 * @param int $id the Episode ID
	 * @return int|bool the ID or 0 if error
	 */
	private function set_artwork( int $id ): ?int {
		if ( ! $this->episode->image_url ) {
			return 0;
		}
		$response	  = wp_remote_get( $this->episode->image_url,);
		if ( is_wp_error( $response ) ) {
			return null;
		}
		$image_bits = wp_remote_retrieve_body( $response );
		if (empty($image_bits)) {
			return null;
		}
		$image_data = wp_upload_bits( basename( $this->episode->image_url ), null, $image_bits );

		$attachment    = array(
			'post_mime_type' => $image_data['type'],
			'post_title'     => "Episode {$this->episode->number} artwork",
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$attachment_id = wp_insert_attachment( $attachment, $image_data['file'], $id, true );

		if ( ! is_wp_error( $attachment_id ) ) {
			// Set the uploaded image as the featured image
			return set_post_thumbnail( $id, $attachment_id );
		} else {
			return null;
		}
	}
}
