<?php
/**
 * Handles settings functionality.
 *
 * @package    Members
 * @subpackage Admin
 * @author     Marty Helmick
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

# Register settings views.
add_action( 'members_register_settings_views', 'members_register_term_settings_views', 5 );

/**
 * Registers the plugin's built-in settings views.
 *
 * @since  2.0.0
 * @access public
 * @param  object  $manager
 * @return void
 */
function members_register_term_settings_views( $manager ) {

	// Bail if not on the settings screen.
	if ( 'members-settings' !== $manager->name ) {
		return;
	}

	// Register general settings view (default view).
	$manager->register_view(
		new \Members\Admin\View_Terms(
			'terms',
			array(
				'label'    => esc_html__( 'Terms', 'members' ),
				'priority' => 1,
			)
		)
	);
}
