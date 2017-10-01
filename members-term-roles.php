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

// namespace Members_Term_Roles;

 /**
  * Sets up the plugin
 *
 * @since  1.0.0
 * @access public
 */
final class Members_Term_Roles_Plugin {

	/**
	 * Plugin directory path.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir = '';

	/**
	 * Plugin directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up globals.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function setup() {

		// Main plugin directory path and URI.
		$this->dir = plugin_dir_path( __FILE__ );
		$this->uri = plugin_dir_url( __FILE__ );
	}

	/**
	 * Loads files needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function includes() {

		// Load function files.
		require_once( $this->dir . 'inc/functions-post-permissions.php' );

		// Load admin files.
		if ( is_admin() ) {

			require_once( $this->dir . 'inc/class-meta-box-term-permissions.php' );
		}
	}

	/**
	 * Sets up main plugin actions and filters.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private function setup_actions() {

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Sets up necessary actions for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	public function activation() {
		smcs_roles_on_plugin_activation();
	}
}

Members_Term_Roles_Plugin::get_instance();
