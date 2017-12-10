<?php
/**
 * Unlimit Con.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Unlimit Con
 * @author  Themener
 * @license GPL-2.0+
 * @link    http://www.themener.com/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'unlimit_localization_setup' );
function unlimit_localization_setup(){
	load_child_theme_textdomain( 'unlimit-con', get_stylesheet_directory() . '/languages' );
}

// Add the helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Include widget Areas.
include_once( get_stylesheet_directory() . '/lib/widget-areas.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the required WooCommerce styles and Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Add the Genesis Connect WooCommerce notice.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );


// Add  Speaker Post Type
include_once( get_stylesheet_directory() . '/lib/post-types/speaker.php' );
// Add  Session Post Type
include_once( get_stylesheet_directory() . '/lib/post-types/session.php' );
// Add  Organizer Post Type
include_once( get_stylesheet_directory() . '/lib/post-types/organizer.php' );
// Add  Day Post Type
include_once( get_stylesheet_directory() . '/lib/post-types/day.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Unlimit Con' );
define( 'CHILD_THEME_URL', 'http://www.themener.com/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

// Enqueue Scripts and Styles.
add_action( 'wp_enqueue_scripts', 'unlimit_enqueue_scripts_styles' );
function unlimit_enqueue_scripts_styles() {

	wp_enqueue_style( 'unlimit-con-fonts', '//fonts.googleapis.com/css?family=Nunito|Open+Sans:400,600', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'unlimit-con-responsive-menu', get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'unlimit-con-responsive-menu',
		'genesis_responsive_menu',
		unlimit_responsive_menu_settings()
	);

  wp_enqueue_script( 'unlimit-session', get_stylesheet_directory_uri() . "/js/session.js", array( 'jquery' ), CHILD_THEME_VERSION, true );
  wp_localize_script( 'unlimit-session', 'session_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
}

// Define our responsive menu settings.
function unlimit_responsive_menu_settings() {

	$settings = array(
		'mainMenu'          => __( 'Menu', 'unlimit-con' ),
		'menuIconClass'     => 'dashicons-before dashicons-menu',
		'subMenu'           => __( 'Submenu', 'unlimit-con' ),
		'subMenuIconsClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'       => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

// Add support for custom background.
add_theme_support( 'custom-background' );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add Image Sizes.
add_image_size( 'featured-image', 720, 400, TRUE );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'After Header Menu', 'unlimit-con' ), 'secondary' => __( 'Footer Menu', 'unlimit-con' ) ) );

// Reposition the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

// Reduce the secondary navigation menu to one level depth.
add_filter( 'wp_nav_menu_args', 'unlimit_secondary_menu_args' );
function unlimit_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

// Modify size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'unlimit_author_box_gravatar' );
function unlimit_author_box_gravatar( $size ) {
	return 90;
}

// Modify size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'unlimit_comments_gravatar' );
function unlimit_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

//* Change the footer text
add_filter('genesis_footer_creds_text', 'unl_footer_creds_filter');
function unl_footer_creds_filter( $creds ) {
	$creds = 'Copyright[footer_copyright] &middot; <a href="http://www.unlimitcon.com"> unlimitcon.com</a>. All rights reserved.';
	return $creds;
}

/*
*
* add header after  
* the about, contact page 
* 
----------------------------------*/

 

add_action('genesis_after_header','unlimitcon_header_after',10);
function unlimitcon_header_after(){

    $pages= array('about','contact','sponsors','sessions','speakers','sponsorship');     
     
    if (is_page( $pages)){     
          remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
	      remove_action( 'genesis_entry_header', 'genesis_do_post_title' );		 
	      remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
          genesis_entry_header_markup_open();
                 echo '<div class="wrap">';
	                    genesis_do_post_title();
	             echo '</div>';
	      genesis_entry_header_markup_close();
   
     }   
        
 }

