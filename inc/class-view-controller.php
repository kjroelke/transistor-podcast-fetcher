<?php
/**
 * View Controller
 * The View Layer Controller. Contains all the callback functions for the admin screen
 *
 * @package KJR_Dev
 * @since 1.0
 */

namespace KJR_Dev\Podcast_API;

/**
 * The View Layer Controller. Contains all the callback functions for the admin screen
 */
class View_Controller {
	/** Load the Admin Page HTML */
	public function admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'kjr-transistor-podcast-fetcher' ) );
		}
		if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_settings_error( 'kjr_transistor_podcast_fetcher', 'kjr_transistor_podcast_fetcher_message', __( 'Settings Saved', 'kjr-transistor-podcast-fetcher' ), 'success' );
		}
		ob_start();
		require_once __DIR__ . '/admin-screen.php';
		ob_end_flush();
	}

	/** Displays below the section */
	public function section_callback() {
			ob_start();
			require_once __DIR__ . '/admin-screen-header.php';
			ob_end_flush();
	}

	/**
	 * The Transistor API Key Callback
	 *
	 * @param false|string $transistor_api_key The Transistor API Key
	 * @return void
	 */
	public function transistor_api_key_callback( false|string $transistor_api_key ): void {
		echo '<input type="text" autocomplete="off" name="kjr_transistor_podcast_fetcher_options[transistor-api-key]" id="transistor-api-key" ' . ( $transistor_api_key ? 'value="' . esc_attr( $transistor_api_key ) . '"' : '' ) . ' /><input type="hidden" name="action" value="kjr_transistor_podcast_fetcher_save_options" />';
	}

	/**
	 * The Transistor Show Name Callback
	 *
	 * @param false|string $transistor_show_name The Transistor Show Name
	 * @return void
	 */
	public function transistor_show_name_callback( false|string $transistor_show_name ): void {
		echo '<input type="text" autocomplete="off" name="kjr_transistor_podcast_fetcher_options[transistor-show-name]" id="transistor-show-name" ' . ( $transistor_show_name ? 'value="' . esc_attr( $transistor_show_name ) . '"' : '' ) . ' /><input type="hidden" name="action" value="kjr_transistor_podcast_fetcher_save_options" />';
	}

	/**
	 * Show Release Schedule Callback
	 *
	 * @param false|string $transistor_show_release_schedule The Transistor Show Release Schedule
	 * @return void
	 */
	public function transistor_show_release_schedule_callback( false|string $transistor_show_release_schedule ): void {
		$options = array(
			'daily'  => __( 'Daily', 'kjr-transistor-podcast-fetcher' ),
			'weekly' => __( 'Weekly', 'kjr-transistor-podcast-fetcher' ),
		);
		echo '<fieldset>';
		foreach ( $options as $value => $label ) {
			echo '<input name="kjr_transistor_podcast_fetcher_options[transistor-show-release-schedule]" type="radio" id="transistor-show-release-schedule-' . esc_attr( $value ) . '" value="' . esc_attr( $value ) . '"' . ( $transistor_show_release_schedule === $value ? ' checked' : '' ) . ' /><label for="transistor-show-release-schedule-' . esc_attr( $value ) . '">' . esc_html( $label ) . '</label><br>';
		}
		echo '</fieldset>';
	}
}
