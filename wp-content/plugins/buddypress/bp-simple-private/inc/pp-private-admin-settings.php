<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings Page class
 */

class PP_Simple_Private_Settings {

	private $settings_message = '';

    public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}


	function admin_menu() {
		add_options_page(  __( 'BP Simple Private', 'bp-simple-private'), __( 'BP Simple Private', 'bp-simple-private' ), 'manage_options', 'bp-simple-private', array( $this, 'settings_admin_screen' ) );
	}


	function settings_admin_screen(){

		if ( !is_super_admin() )
			return;

		$this->settings_update();

		// redirection url
		$pp_private_url = trailingslashit( site_url() );

		// components
		$bp = buddypress();

		$active_components = array( 'member Profile Pages' => 1 );

		$skip_these_bp = array( 'friends', 'messages', 'notifications', 'settings', 'xprofile' );

		foreach( $bp->active_components as $key => $value ) {

			if ( ! in_array( $key, $skip_these_bp ) )
				$active_components[ $key ] = 1;
		}

		ksort( $active_components );

		$pp_private_components = get_option( 'pp-private-components' );
		if ( $pp_private_components == false )
			$pp_private_components = array();

		// custom post types
		$args = array( 'public' => true, '_builtin' => false );
		$output = 'names';
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator );

		$skip_these_bbPress = array( 'forum', 'topic', 'reply' );

		$private_post_types = array( 'page' => 1, 'post' => 1 );

		foreach( $post_types as $key => $value ) {

			if ( ! in_array( $key, $skip_these_bbPress ) )
				$private_post_types[ $key ] = 1;
		}

		ksort( $private_post_types );

		$pp_private_cpts = get_option( 'pp-private-cpts' );
		if ( $pp_private_cpts == false )
			$pp_private_cpts = array();

		?>

		<h3>BuddyPress Simple Private Settings</h3>

		<table class="wp-list-table widefat fixed striped">
		<tr>
		<td style="vertical-align:top; border: 1px solid #ccc;" >

			<?php echo $this->settings_message . '<br/>'; ?>

			<form action="" name="settings-form" id="settings-form"  method="post" class="standard-form">

				<?php wp_nonce_field('settings-action', 'settings-field'); ?>

				<p>
					<?php echo __('When a non-logged-in user tries to access Private content, they will be sent to:', 'bp-simple-private'); ?>
					<br/>
					<?php echo '<strong>' . $pp_private_url . '</strong>'; ?>
					<br/>
					<?php echo __("If you'd like to set a custom redirection URL, please visit:", 'bp-simple-private'); ?>
					<a href="http://www.philopress.com/products/bp-simple-private-pro" target="_blank">BP Simple Private Pro<a/>
				</p>

				<hr/>

				<p>
					<br/>
					<?php echo __('Select which BuddyPress sections are NOT viewable by non-logged-in users:', 'bp-simple-private'); ?>

					<br/>

					<ul id="pp-comp-fields">

						<?php
						foreach( $active_components as $key => $value ) {
							if ( $key != 'blogs' ) {
							?>
								<li>&nbsp;<label><input type="checkbox" name="pp-private-components[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $pp_private_components ) ); ?> /> <?php echo ucfirst( $key );	?></label></li>
							<?php
							}
						}
						?>

					</ul>
				</p>

				<hr/>

				<p>
					<br/>
					<?php echo __('For selected Post Types, a Public or Private checkbox will appear in the upper right corner of their wp-admin Create and Edit screens.', 'bp-simple-private'); ?>
					<br/>

	 				<ul id="pp-cpt-fields">

						<?php
						foreach ( $private_post_types as $key => $value ) {
						?>
							<li>&nbsp;<label><input type="checkbox" name="pp-private-cpts[]" value="<?php echo $key; ?>" <?php checked( in_array( $key, $pp_private_cpts ) ); ?> /> <?php echo ucfirst( $key); ?></label></li>
						<?php
						}
						?>

					</ul>
				</p>

				<hr/>

				<p>
					<br/>
					<input type="hidden" name="settings-access" value="1"/>
					<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Save Settings', 'bp-simple-private'); ?>"/>
				</p>
			</form>

		</td></tr></table>
	<?php
	}


	//  save any changes to settings options
	private function settings_update() {

		if ( isset( $_POST['settings-access'] ) ) {

			if ( !wp_verify_nonce($_POST['settings-field'],'settings-action') )
				die('Security check');

			if ( !is_super_admin() )
				return;

			delete_option( 'pp-private-components' );
			$pp_private_components = array();
			if ( ! empty( $_POST['pp-private-components'] ) ) {
				foreach ( $_POST['pp-private-components'] as $value )
					$pp_private_components[] = $value;
			}
			update_option( 'pp-private-components', $pp_private_components, true );


			delete_option( 'pp-private-cpts' );
			$pp_private_cpts = array();
			if ( ! empty( $_POST['pp-private-cpts'] ) ) {
				foreach ( $_POST['pp-private-cpts'] as $value )
					$pp_private_cpts[] = $value;
			}
			update_option( 'pp-private-cpts', $pp_private_cpts, true );


			$this->settings_message .=
				"<div class='updated below-h2'>" .
				__('Settings have been updated.', 'bp-simple-private') .
				"</div>";
		}
	}

} // end of PP_Simple_Privacy_Settings class

$pp_simple_private_settings_instance = new PP_Simple_Private_Settings();