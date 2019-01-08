<?php

if ( !defined( 'ABSPATH' ) ) exit;

// Adds a box to the right column on the Post and Page edit screens.
function pp_private_add_meta_box() {

	$args = array(
	   'public'   => true,
	);

	$output = 'names';

	$post_types = get_post_types( $args, $output );

	$skip_these_bbPress = array( 'forum', 'topic', 'reply' );

	$pp_private_cpts = get_option( 'pp-private-cpts' );

	foreach ( $post_types as $post_type ) {

		if ( ! in_array( $post_type, $skip_these_bbPress ) ) {

			if ( in_array( $post_type, $pp_private_cpts ) ) {

				add_meta_box(
					'pp_private_sectionid',
					__( 'Public or Private', 'bp-simple-private' ),
					'pp_private_meta_box_callback',
					$post_type,
					'side',
					'high'
				);
			}
		}
	}
}
add_action( 'add_meta_boxes', 'pp_private_add_meta_box' );


// Prints the box content.
function pp_private_meta_box_callback( $post ) {

	$front_id = get_option('page_on_front');

	if ( $post->ID == $front_id )
		_e( 'This is your Front Page - and cannot be Private.', 'bp-simple-private' );
	else {
		wp_nonce_field( 'pp_private_save_meta_box_data', 'pp_private_meta_box_nonce' );

		$value = get_post_meta( $post->ID, 'pp-private', true );
	?>
		<label for="pp_private">
		<?php _e( 'This post or page cannot be seen unless the user is logged-in:', 'bp-simple-private' ); ?>
		</label> &nbsp;
		<input type="checkbox" id="pp-private" name="pp-private" value="1" <?php checked( $value, 1 ); ?> />
<?php
	}
}


// Save or delete meta box data when the post is saved or updated
function pp_private_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['pp_private_meta_box_nonce'] ) )
		return;

	if ( ! wp_verify_nonce( $_POST['pp_private_meta_box_nonce'], 'pp_private_save_meta_box_data' ) )
		return;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// check user permissions
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) )
			return;

	}
	else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

	}

	// update or delete the post_meta
	if ( ! isset( $_POST['pp-private'] ) )
		 delete_post_meta($post_id, 'pp-private', '');

	else {

		$front_id = get_option('page_on_front');

		if ( $post_id != $front_id ) {

			$private_data = sanitize_text_field( $_POST['pp-private'] );

			update_post_meta( $post_id, 'pp-private', $private_data );

		}
		else
			delete_post_meta($post_id, 'pp-private', '');

	}

}
add_action( 'save_post', 'pp_private_save_meta_box_data' );