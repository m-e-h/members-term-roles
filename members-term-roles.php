<?php
/**
 * Plugin Name:       Members Term Roles
 * Plugin URI:        https://github.com/m-e-h/members-term-roles
 * Description:       An add-on for the Members plugin. Adds Content Permissions to Taxonomy Terms.
 * Version:           0.1.2
 * Author:            Marty Helmick
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mtr
 * GitHub Plugin URI: https://github.com/m-e-h/members-term-roles
 * Requires WP:       4.7
 * Requires PHP:      5.4
 */

/**
 * Loads files needed by the plugin.
 */
add_action( 'plugins_loaded', '_members_term_roles' );

function _members_term_roles() {

	// Check if Members is installed.
	if ( members_term_roles_can_init() ) {

		// Load function files.
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-post-permissions.php' );

		// Load admin files.
		if ( is_admin() ) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-meta-box-term-permissions.php' );
		}
	}
}

/**
 * Show a notice if Members is not installed and deactivate the Members Term Roles plugin.
 *
 * @since 0.1.0
 * @return void
 */
function members_term_roles_can_init() {

	if ( class_exists( 'Members_Plugin' ) ) {
		return true;
	}

	if ( current_user_can( 'activate_plugins' ) ) {
		add_action( 'admin_notices', 'members_term_roles_requirements_notice' );
	}

	/**
	 * Deactivation admin notice.
	 */
	function members_term_roles_requirements_notice() {
		$notice = sprintf( '<strong>Members Term Roles</strong> requires the <a href="%1$s">%2$s</a> plugin but could not find it. You will need to install and activate <a href="%1$s">%2$s</a> before using Members Term Roles.', esc_url( 'https://wordpress.org/plugins/members/' ), 'Members' );
		echo '<div class="error"><p>' . $notice . '</p></div>';
	}

	return false;
}
