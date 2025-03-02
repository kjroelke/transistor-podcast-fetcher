<?php
/** Section Header
 * Displays below the Section Headline
 *
 * @package KJR_Dev
 */

if ( isset( $_GET['status'] ) && 'success' === $_GET['status'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
<div class='notice notice-success is-dismissible'>
	<p>Settings Updated, Episodes synced</p>
</div>
	<?php
endif;
