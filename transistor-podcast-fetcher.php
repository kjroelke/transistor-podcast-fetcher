<?php
/**
 * Plugin Name: Transistor Podcast Fetcher
 * Plugin URI: https://github.com/kjroelke/transistor-podcast-fetcher
 * Description: Built for The Janchi Show website, this plugin fetches a show's episodes from Transistor and stores them to the "Podcast" Category of the "Posts" post type.
 * Version: 0.1.0
 * Author: K.J. Roelke
 * Author URI: https://www.kjroelke.online
 * Text Domain: kjr-transistor-podcast-fetcher
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 8.2
 * Requires at least: 6.0
 * Tested up to: 6.7.2
 *
 * @package KJR_Dev
 */

use KJR_Dev\Podcast_API\Plugin_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
require_once __DIR__ . '/vendor/autoload.php';
$plugin_loader = new Plugin_Loader();

register_activation_hook( __FILE__, array( $plugin_loader, 'activate' ) );
register_deactivation_hook( __FILE__, array( $plugin_loader, 'deactivate' ) );
