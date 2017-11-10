<?php
/**
 * Handles the general settings view.
 *
 * @package    Members
 * @subpackage Admin
 * @author     Marty Helmick
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Members\Admin;

/**
 * Sets up and handles the general settings view.
 *
 * @since  2.0.0
 * @access public
 */
class View_Terms extends View {

	/**
	 * Holds an array the plugin settings.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var    array
	 */
	public $settings = array();

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		//wp_enqueue_script( 'members-term-settings' );
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	function register_settings() {

		$this->settings = get_option( 'members_term_settings' );

		register_setting(
			'members_term_settings',
			'members_term_settings', array( $this, 'validate_settings' )
		);

		add_settings_section(
			'hide_content_permissions_mb',
			__( 'Post Admin', 'members-term-roles' ),
			'__return_false',
			'members-term-settings'
		);

		add_settings_field(
			'hide_cp_post_metabox',
			__( 'Hide Role Selection', 'members-term-roles' ), array( $this, 'field_hide_cp_post_metabox' ),
			'members-term-settings',
			'hide_content_permissions_mb'
		);

	}

	/**
	 * Validates the plugin settings.
	 *
	 * @since  2.0.0
	 * @access public
	 * @param  array  $input
	 * @return array
	 */
	function validate_settings( $settings ) {

		// Validate true/false checkboxes.
		$settings['hide_content_permissions_mb'] = ! empty( $settings['hide_content_permissions_mb'] ) ? true : false;

		// Return the validated/sanitized settings.
		return $settings;
	}

	/**
	 * Enable content permissions field callback.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function field_hide_cp_post_metabox() {

		$options = get_option( 'members_term_settings' );

		// Set default value.
		$value = isset( $options['hide_content_permissions_mb'] ) ? $options['hide_content_permissions_mb'] : false;
	?>

		<label>
			<input type="checkbox" name="members_term_settings[hide_content_permissions_mb]" value="true" <?php checked( $value ); ?> />
			<?php esc_html_e( 'Do not show the default content permissions meta-box on the post admin screen.', 'members-term-roles' ); ?>

		</label>
	<?php
	var_dump(members_content_permissions_mb_hidden());
	}

	/**
	 * Renders the settings page.
	 *
	 * @since  2.0.0
	 * @access public
	 * @return void
	 */
	public function template() {
	?>

		<form method="post" action="options.php">
			<?php settings_fields( 'members_term_settings' ); ?>
			<?php do_settings_sections( 'members-term-settings' ); ?>
			<?php submit_button( 'Update Settings' ); ?>
		</form>

	<?php
	}
}
