<?php

// namespace Members_Term_Roles;

add_filter( 'members_can_user_view_post', 'mt_can_user_view_post_term', 10, 3 );
// Allow per post Members roles to be selectable from a taxonomy.
function mt_can_user_view_post_term( $can_view, $user_id, $post_id ) {

	$post = get_post( $post_id );
	$post_type = get_post_type_object( $post->post_type );
	$taxonomies = get_object_taxonomies( $post_type );
	$terms = wp_get_object_terms( $post_id, $taxonomies, array( 'fields' => 'ids' ) );

	if ( ! empty( $terms ) ) {

		foreach ( $terms as $term ) {

			$term_roles = get_term_meta( $term->term_id, 'members_term_role', false );

			if ( ! empty( $term_roles ) ) {

				$can_view = false;

				if ( $post->post_author == $user_id || user_can( $user_id, 'restrict_content' ) || user_can( $user_id, $post_type->cap->edit_post, $post_id ) ) {
					$can_view = true;
				}

				if ( members_user_has_role( $user_id, $term_roles ) ) {
					$can_view = true;
				}
			}
		}
	}

	return $can_view;
}

/**
 * Sets a post's access roles given an array of roles.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $post_id
 * @param  array   $roles
 * @global object  $wp_roles
 * @return void
 */
function mtr_set_term_roles( $term_id, $roles ) {
	global $wp_roles;

	// Get the current roles.
	$current_roles = get_term_meta( $term_id, 'members_term_role', false );

	// Loop through new roles.
	foreach ( $roles as $role ) {

		// If new role is not already one of the current roles, add it.
		if ( ! in_array( $role, $current_roles ) )
			add_term_meta( $term_id, 'members_term_role', $role, false );
	}

	// Loop through all WP roles.
	foreach ( $wp_roles->role_names as $role => $name ) {

		// If the WP role is one of the current roles but not a new role, remove it.
		if ( ! in_array( $role, $roles ) && in_array( $role, $current_roles ) )
			delete_term_meta( $term_id, 'members_term_role', $role );
	}
}
