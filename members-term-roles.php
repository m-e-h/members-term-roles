<?php
/**
 * Plugin Name:       Members Term Roles
 * Plugin URI:        https://github.com/m-e-h/members-term-roles
 * Description:       An add-on for the Members plugin. Adds Content Permissions to Taxonomy Terms.
 * Version:           1.0.0
 * Author:            Marty Helmick
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mtr
 * GitHub Plugin URI: https://github.com/DioceseOfCharlotte/smcs-functions
 * Requires WP:       4.7
 * Requires PHP:      5.4
 */

 // Exit if accessed directly
 defined( 'ABSPATH' ) || exit;

 /**
  * Loads files needed by the plugin.
  */
add_action( 'plugins_loaded', '_members_term_roles' );

function _members_term_roles() {

	$plugin_dir = plugin_dir_path( __FILE__ );
	$plugin_uri = plugin_dir_url( __FILE__ );

	// Load function files.
	require_once( $plugin_dir . 'inc/functions-post-permissions.php' );

	// Load admin files.
	if ( is_admin() ) {
		require_once( $plugin_dir . 'inc/class-meta-box-term-permissions.php' );
	}
}
