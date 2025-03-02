<?php
/**
 * The Admin Screen
 *
 * @package KJR_Dev
 * @since 1.0
 */

// show error/update messages
settings_errors( 'kjr_transistor_podcast_fetcher' );

?>
<div class="wrap">
	<h1>
		<?php echo esc_html( get_admin_page_title() ); ?>
	</h1>
	<form method="POST" action="admin-post.php" novalidate="novalidate" autocomplete="off" id="kjr-transistor-podcast-fetcher">
		<?php
		wp_nonce_field( 'kjr_transistor_podcast_fetcher_options_verify', 'kjr_transistor_podcast_fetcher_options_nonce' );
		settings_fields( 'kjr_transistor_podcast_fetcher_options_group' );
		do_settings_sections( 'kjr-transistor-podcast-fetcher' );
		submit_button( esc_html_e( 'Sync Episodes', 'kjr-transistor-podcast-fetcher' ) );
		?>
	</form>
</div>
