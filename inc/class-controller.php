<?php
/**
 * Controller
 * Handles the WordPress Dashboard side of things for the plugin
 *
 * @since 1.0
 * @package KJR_Dev
 * @subpackage Podcast_API
 */

namespace KJR_Dev\Podcast_API;

/**
 * Handles the WordPress Dashboard side of things for the plugin
 */
class Controller {
	/**
	 * The View Layer Controller.
	 *
	 * @var View_Controller $view
	 */
	private View_Controller $view;

	/**
	 * The Model Layer Controller.
	 *
	 * @var Model $model
	 */
	private Model $model;


	/**
	 * If set, when the current token expires
	 *
	 * @var ?string $token_expiry
	 */
	public ?string $token_expiry;

	/** Constructor */
	public function __construct() {
		$this->wire_actions();
		$this->model = new Model();
		$this->view  = new View_Controller();
	}

	/** Wires the actions to the appropriate hooks */
	private function wire_actions() {
		$actions = array(
			'admin_menu' => array( $this, 'my_admin_menu' ),
			'admin_init' => array( $this, 'register_fields' ),
			'admin_post_kjr_transistor_podcast_fetcher_save_options' => array( $this, 'save_options' ),
		);

		foreach ( $actions as $hook => $callback ) {
			add_action( $hook, $callback );
		}
	}

	/** Register the Admin Menu */
	public function my_admin_menu() {
		add_menu_page(
			'Transistor Podcast Fetcher',
			'Transistor Podcast Fetcher',
			'manage_options',
			'kjr-transistor-podcast-fetcher',
			array( $this->view, 'admin_page' ),
			'dashicons-microphone',
			75
		);
	}



	/** Define Admin Setting Group & Fields */
	public function register_fields() {
		register_setting(
			'kjr_transistor_podcast_fetcher_options_group',
			'kjr_transistor_podcast_fetcher_options',
		);

		add_settings_section(
			'kjr-transistor-podcast-fetcher-settings',
			'Configure the plugin',
			array( $this->view, 'section_callback' ),
			'kjr-transistor-podcast-fetcher'
		);

		add_settings_field(
			'transistor-show-name',
			'Podcast Name',
			function () {
				$this->view->transistor_show_name_callback( $this->model->get_the_option( 'transistor-show-name' ) );
			},
			'kjr-transistor-podcast-fetcher',
			'kjr-transistor-podcast-fetcher-settings',
			array( 'label_for' => 'transistor-show-name' )
		);

		add_settings_field(
			'transistor-api-key',
			'Transistor API Key',
			function () {
				$this->view->transistor_api_key_callback( $this->model->get_the_option( 'transistor-api-key' ) );
			},
			'kjr-transistor-podcast-fetcher',
			'kjr-transistor-podcast-fetcher-settings',
			array( 'label_for' => 'transistor-api-key' )
		);

		add_settings_field(
			'transistor-release-schedule',
			'Show Release Schedule',
			function () {
				$this->view->transistor_show_release_schedule_callback( $this->model->get_the_option( 'transistor-show-release-schedule' ) );
			},
			'kjr-transistor-podcast-fetcher',
			'kjr-transistor-podcast-fetcher-settings',
			array( 'label_for' => 'transistor-release-schedule' )
		);
	}


	/** Saves the options to the database */
	public function save_options() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		check_admin_referer( 'kjr_transistor_podcast_fetcher_options_verify', 'kjr_transistor_podcast_fetcher_options_nonce' );
		$this->model->set_the_options( $_POST['kjr_transistor_podcast_fetcher_options'] );
		$this->model->init();
		wp_safe_redirect( admin_url( 'admin.php?page=kjr-transistor-podcast-fetcher&status=success' ) );
	}
}
