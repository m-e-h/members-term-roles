<?php
/**
 * Term permissions meta box.
 *
 */

class Meta_Box_Term_Permissions {

	/**
	 * Singleton instance.
	 *
	 * @var
	 * @since  0.1.0
	 */
	private static $instance = null;


	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  0.1.0
	 * @return object
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function __construct() {

		// Load on the edit tags screen.
		add_action( 'load-tags.php', array( $this, 'screen_loaded' ) );
		add_action( 'load-edit-tags.php', array( $this, 'screen_loaded' ) );
		add_action( 'load-term.php', array( $this, 'screen_loaded' ) );

		// Update term meta.
		add_action( 'created_term', array( $this, 'save_data' ), 10, 3 );
		add_action( 'edited_term', array( $this, 'save_data' ), 10, 3 );

	}

	/**
	 * Runs when the page is loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function screen_loaded() {

		$screen   = get_current_screen();
		$taxonomy = get_taxonomy( $screen->taxonomy );

		if ( $taxonomy->public ) {
			add_action( "{$screen->taxonomy}_add_form_fields", array( $this, 'add_form_fields' ), 10, 1 );
			add_action( "{$screen->taxonomy}_edit_form_fields", array( $this, 'edit_form_fields' ), 10, 2 );
		}
	}

	public function add_form_fields( $taxonomy ) {

		global $wp_roles;

		// Get roles and sort.
		$_wp_roles = $wp_roles->role_names;
		asort( $_wp_roles );

		// Set default values.
		$members_term_role = '';
		?>

		<div class="form-field term-members_term_role-wrap">
			<h2><?php _e( 'Term Permissions', 'members-terms' ); ?></h2>
			<?php foreach ( $_wp_roles as $role => $name ) : ?>
					<label>
						<input type="checkbox" name="members_term_role[]" <?php checked( is_array( $members_term_role ) && in_array( $role, $members_term_role ) ); ?> value="<?php echo esc_attr( $role ); ?>" />
						<?php echo esc_html( members_translate_role( $role ) ); ?>
					</label>
			<?php endforeach; ?>
			<p class="description"><?php _e( 'Select the roles required to view content within this category. Leave unchecked for all.', 'members-terms' ); ?></p>
		</div>
		<?php

	}

	public function edit_form_fields( $term, $taxonomy ) {

		global $wp_roles;

		// Get roles and sort.
		$_wp_roles = $wp_roles->role_names;
		asort( $_wp_roles );

		// Set default values.
		$members_term_role = get_term_meta( $term->term_id, 'members_term_role', false );

		// Set default values.
		if ( empty( $members_term_role ) ) {
			$members_term_role = '';
		}
			?>

		<tr class="form-field term-members_term_role-wrap">
			<th scope="row"><?php _e( 'Term Permissions', 'members-terms' ); ?></th>
			<td class="term-roles-datalist">
				<?php foreach ( $_wp_roles as $role => $name ) : ?>
						<label>
							<input type="checkbox" name="members_term_role[]" <?php checked( is_array( $members_term_role ) && in_array( $role, $members_term_role ) ); ?> value="<?php echo esc_attr( $role ); ?>" />
							<?php echo esc_html( members_translate_role( $role ) ); ?>
						</label><br>
				<?php endforeach; ?>
				<p class="description"><?php _e( 'Select the roles required to view content in this category. Leave unchecked for all.', 'members-terms' ); ?></p>
			</td>
		</tr>
		<?php

	}

	public function save_data( $term_id, $tt_id, $taxonomy ) {

		// Get the current roles.
		$current_term_roles = get_term_meta( $term_id, 'members_term_role', false );

		// Get the new roles.
		$new_term_roles = isset( $_POST['members_term_role'] ) ? $_POST['members_term_role'] : '';

		// If we have an array of new roles, set the roles.
		if ( is_array( $new_term_roles ) ) {
			mtr_set_term_roles( $term_id, array_map( 'members_sanitize_role', $new_term_roles ) );
		} elseif ( ! empty( $current_roles ) ) {
			delete_term_meta( $term_id, 'members_term_role' );
		}

	}

}

Meta_Box_Term_Permissions::get_instance();
