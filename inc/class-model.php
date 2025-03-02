<?php
/**
 * Model
 *
 * @package KJR Dev
 * @subpackage Podcast API
 * @since 1.0
 */

namespace KJR_Dev\Podcast_API;

use Exception;
use KJR_Dev\Podcast_API\Api\Podcast_API;

/** Interacts with the WP Database */
class Model {
	/**
	 * The option key
	 *
	 * @var string $option_key
	 */
	private string $option_key = 'kjr_transistor_podcast_fetcher_options';

	/** Returns the value of $options */
	public function get_the_options(): false|array {
		return get_option( $this->option_key );
	}

	/**
	 * Stores the details to the database
	 *
	 * @param array $options the options array
	 */
	public function set_the_options( array $options ): bool {
		return update_option( $this->option_key, $options );
	}

	/**
	 * Gets the value of a specific option
	 *
	 * @param string $option_name The option name
	 * @return false|string The option value
	 */
	public function get_the_option( string $option_name ): false|string {
		$allowed_keys = array(
			'transistor-api-key',
			'transistor-show-name',
			'transistor-show-release-schedule',
		);
		if ( ! in_array( $option_name, $allowed_keys, true ) ) {
			wp_die( 'Invalid Option Name' );
		}
		$options = $this->get_the_options();
		if ( ! $options ) {
			return false;
		}
		return $options[ $option_name ] ?? false;
	}

	/** Initializes the plugin. Fetches the episodes, then schedules the next fetch based on the schedule option */
	public function init() {
		try {
			$api_key   = $this->get_the_option( 'transistor-api-key' );
			$show_name = $this->get_the_option( 'transistor-show-name' );
			$api       = new Podcast_API( $api_key, $show_name );
			$api->fetch_episodes();
			$this->schedule_fetch( $api );
		} catch ( Exception $e ) {
			wp_die( $e->getMessage() );
		}
	}

	/** Schedules the fetch based on the schedule option
	 *
	 * @param Podcast_API $api The API object
	 */
	private function schedule_fetch( Podcast_API $api ): void {
		$schedule = $this->get_the_option( 'transistor-show-release-schedule' );
		if ( ! wp_next_scheduled( 'kjr_transistor_podcast_fetcher_fetch_episodes' ) ) {
			wp_schedule_event( time(), $schedule, 'kjr_transistor_podcast_fetcher_fetch_episodes' );
		}
		add_action( 'kjr_transistor_podcast_fetcher_fetch_episodes', array( $api, 'get_latest_episode' ) );
	}
}
