<?php
/**
* Plugin Name: Simple cookies notice
* Plugin URI:  https://wp-experts.gr/en/wordpress-plugins/simple-cookies-notice-wordpress-plugin/
* Description: Just a simple popup fixed at bottom of the screen. You can use it for your cookies notice.
* Version:     1.0.0
* Author:      Konstantinos Sofianos
* Author URI:  https://wp-experts.gr/en/kostas-sofianos/
* Text Domain: simple-cookies-notice
* Domain Path: /languages
* License:     GPL2

Simple cookies notice is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Simple cookies notice is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Simple cookies notice. If not, see https://opensource.org/licenses/category.
*/

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

// Admin scripts
function sokoBackEnqueueScripts() {
	wp_enqueue_style( 'mystyles', plugins_url('assets/styles.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'sokoBackEnqueueScripts' );

// Front end scripts
function sokoEnqueueFrontScripts() {
	wp_enqueue_style( 'myfrontstyles', plugins_url('assets/frontstyles.css', __FILE__ ) );
	wp_enqueue_script( 'myfrontscripts', plugins_url('assets/frontscripts.js', __FILE__ ), array('jquery') );
}

add_action( 'wp_enqueue_scripts', 'sokoEnqueueFrontScripts' );

if ( !class_exists( 'SokoCookiesNotice' ) ) {

	class SokoCookiesNotice {
	    public function __construct() {
		    // Hook into the admin menu
		    add_action( 'admin_menu', array( $this, 'sokoSettingsPage' ) );
		    add_action( 'admin_init', array( $this, 'sokoSetupSections' ) );
		    add_action( 'admin_init', array( $this, 'sokoSetupFields' ) );
		    // Hook into wp_footer
		    add_action( 'wp_footer', array( $this, 'sokoCookiesPrint' ) );
		}


		// Add the menu item and page
		public function sokoSettingsPage() {
		    $page_title = 'Simple cookies notice';
		    $menu_title = 'Simple cookies notice';
		    $capability = 'manage_options';
		    $slug = 'simple-cookies-notice';
		    $callback = array( $this, 'sokoSettingsPageContent' );
		    $icon = 'dashicons-testimonial';
		    $position = 100;

		    add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
		}

		public function sokoSettingsPageContent() { ?>
			<div class="wrap soko-wrap">
				<h1><?php echo __('Simple cookies notice settings', 'simple-cookies-notice' ) ?></h1>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'simple-cookies-notice' );
					do_settings_sections( 'simple-cookies-notice' );
					submit_button();
					?>
				</form>
			</div>		
		    
		<?php 
		}

		public function sokoSetupSections() {
		    add_settings_section( 'popup_text', 'The text for popup', false, 'simple-cookies-notice' );
		}
		public function sokoSectionCallback( $arguments ) {
		    switch( $arguments['id'] ){
		        case 'popup_text':
		        	echo "1.";
	            break;
		    }
		}

		// Register and add settings
		public function sokoSetupFields() {
			register_setting( 'simple-cookies-notice', 'popup_text_field' );
		    add_settings_field( 
		    	'popup_text_field',
		    	'Message',
		    	array( $this, 'sokoTextFieldCallback' ),
		    	'simple-cookies-notice',
		    	'popup_text',
		    	array( 'label_for' => 'popup_text_field' ) 
		    );
		}

		// To, Subject and message fields
		public function sokoTextFieldCallback( $arguments ) {
			global $allowedposttags;
			$value = wp_kses( get_option( 'popup_text_field' ), $allowedposttags );
		    wp_editor( $value , 'popup_text_field' );
		}

		// Print the popup at front end
		function sokoCookiesPrint(){ 
		global $allowedposttags;?>
        <div class="soko-cookies-pop">
        	<?php $sokopopupvalue = wp_kses( get_option( 'popup_text_field' ), $allowedposttags ); ?>
            <span><?php echo __( $sokopopupvalue,'simple-cookies-notice'); ?></span><img src="<?php echo plugins_url('images/black-close-16.png', __FILE__ ); ?>" class="soko-close-popup" />
        </div>
        <?php


     }

	}

	new SokoCookiesNotice();

}