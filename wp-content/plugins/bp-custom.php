<?php
function profile_new_nav_item() {

    global $bp;


    bp_core_new_nav_item(
    array(
        'name'                => 'Prospective Students',
        'slug'                => 'extra_tab',
        'default_subnav_slug' => 'extra_sub_tab', // We add this submenu item below
        'screen_function'     => 'view_manage_tab_main'
    )
    );
}

add_action( 'bp_setup_nav', 'profile_new_nav_item', 10 );

function view_manage_tab_main() {
    add_action( 'bp_template_content', 'bp_template_content_main_function' );
    bp_core_load_template( 'template_content' );
}

function bp_template_content_main_function() {
    if ( ! is_user_logged_in() ) {
        wp_login_form( array( 'echo' => true ) );
    } else{
        global $wpdb;
        global $current_user;
        get_currentuserinfo();
        $user_id = $current_user->ID;
        $field_id = xprofile_get_field_id_from_name('Practicing Language' );
        $field_value = xprofile_get_field_data( 'Native Language', $user_id );
        $query = "SELECT user_id FROM wp_bp_xprofile_data WHERE field_id = $field_id AND value = '$field_value' LIMIT 5";
        $custom_ids = $wpdb->get_col( $query );
        //print_r($custom_ids);
        for ($i = 0; $i < count($custom_ids); ++$i) {
            $name = bp_core_get_user_displayname($custom_ids[$i]);
            $link = bp_core_get_user_domain($custom_ids[$i]);
            echo bp_core_fetch_avatar( array('item_id'=> $custom_ids[$i], 'height'=>25, 'width'=> 25));
            echo '<p><a href='.$link.'>'.$name.'</a></p>';
            //echo bp_core_get_avatar( $custom_ids[$i], 1 );
            echo "\r\n";
        }
    }
 }
?>
