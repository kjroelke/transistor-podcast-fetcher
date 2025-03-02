<?php
/**
 * Plugin Loader
 *
 * @package KJR_Dev
 */

namespace KJR_Dev\Podcast_API;

/** Inits the Plugin */
class Plugin_Loader {
	/**
	 * Constructor
	 */
	public function __construct() {
		new Controller();
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Initializes the Plugin
	 *
	 * @return void
	 */
	public function activate(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		flush_rewrite_rules();
	}

	/**
	 * Handles Plugin Deactivation
	 * (this is a callback function for the `register_deactivation_hook` function)
	 *
	 * @return void
	 */
	public function deactivate(): void {
		$this->deregister_post_type();
		flush_rewrite_rules();
	}

	/**
	 * Registers the Podcast Episode Post Type
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type(
			'episodes',
			array(
				'labels'           => array(
					'name'                     => 'Podcast Episodes',
					'singular_name'            => 'Podcast Episode',
					'menu_name'                => 'Podcast Episodes',
					'all_items'                => 'All Podcast Episodes',
					'edit_item'                => 'Edit Podcast Episode',
					'view_item'                => 'View Podcast Episode',
					'view_items'               => 'View Podcast Episodes',
					'add_new_item'             => 'Add new Podcast Episode',
					'add_new'                  => 'Add new',
					'new_item'                 => 'New Podcast Episode',
					'parent_item_colon'        => 'Parent Podcast Episode:',
					'search_items'             => 'Search Podcast Episodes',
					'not_found'                => 'No Podcast Episodes found',
					'not_found_in_trash'       => 'No Podcast Episodes found in trash',
					'archives'                 => 'Podcast Episode archives',
					'attributes'               => 'Podcast Episodes attributes',
					'featured_image'           => 'Featured image for this Podcast Episode',
					'set_featured_image'       => 'Set featured image for this Podcast Episode',
					'remove_featured_image'    => 'Remove featured image for this Podcast Episode',
					'use_featured_image'       => 'Use as featured image for this Podcast Episode',
					'insert_into_item'         => 'Insert into Podcast Episode',
					'uploaded_to_this_item'    => 'Upload to this Podcast Episode',
					'filter_items_list'        => 'Filter Podcast Episodes list',
					'items_list_navigation'    => 'Podcast Episodes list navigation',
					'items_list'               => 'Podcast Episodes list',
					'item_published'           => 'Podcast Episode published',
					'item_published_privately' => 'Podcast Episode published privately.',
					'item_reverted_to_draft'   => 'Podcast Episode reverted to draft.',
					'item_scheduled'           => 'Podcast Episode scheduled',
					'item_updated'             => 'Podcast Episode updated.',
				),
				'public'           => true,
				'show_in_rest'     => true,
				'menu_position'    => 5,
				'menu_icon'        => 'dashicons-microphone',
				'supports'         => array(
					0 => 'title',
					1 => 'comments',
					2 => 'editor',
					3 => 'excerpt',
					4 => 'thumbnail',
					5 => 'custom-fields',
				),
				'taxonomies'       => array(
					0 => 'post_tag',
					1 => 'podcast-type',
				),
				'has_archive'      => true,
				'rewrite'          => array(
					'feeds' => false,
				),
				'delete_with_user' => false,
			)
		);
	}

	/**
	 * De-registers the Podcast Episode Post Type
	 *
	 * @return void
	 */
	public function deregister_post_type() {
		unregister_post_type( 'episodes' );
	}
}
