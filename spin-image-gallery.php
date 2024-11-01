<?php
/*
Plugin Name: Spinmyplanet
Plugin URI: http://www.spinmyplanet.com/
Description: An easy to use image gallery to rotate images
Version: 1.1.2
Author: SpinMyPlanet
Author URI: http://www.spinmyplanet.com/services
Text Domain: spin-my-planet
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Spin_Image_Gallery' ) ) {

    /**
     * PHP5 constructor method.
     *
     * @since 1.0
    */
    class Spin_Image_Gallery {

        public function __construct() {
                add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
                add_action( 'plugins_loaded', array( $this, 'constants' ));
                add_action( 'plugins_loaded', array( $this, 'includes' ) );
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'spin_image_gallery_plugin_action_links' );
                add_action( 'init', array( &$this, 'register_spin_types' ) );

        }


        /**
         * Internationalization
         *
         * @since 1.0
        */
        public function load_textdomain() {
                load_plugin_textdomain( 'spin-image-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }


        /**
         * Constants
         *
         * @since 1.0
        */
        public function constants() {

                if ( !defined( 'SPIN_IMAGE_GALLERY_DIR' ) )
                        define( 'SPIN_IMAGE_GALLERY_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

                if ( !defined( 'SPIN_IMAGE_GALLERY_URL' ) )
                    define( 'SPIN_IMAGE_GALLERY_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

                if ( ! defined( 'SPIN_IMAGE_GALLERY_VERSION' ) )
                    define( 'SPIN_IMAGE_GALLERY_VERSION', '1.2' );

                if ( ! defined( 'SPIN_IMAGE_GALLERY_INCLUDES' ) )
                    define( 'SPIN_IMAGE_GALLERY_INCLUDES', SPIN_IMAGE_GALLERY_DIR . trailingslashit( 'includes' ) );

        }

        /**
        * Loads the initial files needed by the plugin.
        *
        * @since 1.0
        */
        public function includes() {

                require_once( SPIN_IMAGE_GALLERY_INCLUDES . 'template-functions.php' );
                require_once( SPIN_IMAGE_GALLERY_INCLUDES . 'metabox.php' );
                require_once( SPIN_IMAGE_GALLERY_INCLUDES . 'admin-page.php' );
                require_once( SPIN_IMAGE_GALLERY_INCLUDES . 'scripts.php' );

        }


        function register_spin_types() {

        // Set up some labels for the post type
        $labels = array(
                'name'	   => ( 'Images' ),
                'singular' => ( 'Image' )
        );

        // Set up the argument array for register_post_type()
        $args = array(
                'label'	   => ('Images'),
                'labels'   => $labels,
                'show_ui'  => true,
                'rewrite'  => array( 'slug' => 'images' ),
                'supports' => array( 'title', 'thumbnail'),
                'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
                'publicly_queryable' => true,  // you should be able to query it
                'exclude_from_search' => true,  // you should exclude it from search results
                'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
                'has_archive' => false,  // it shouldn't have archive page
        );

        // Register the post type.
        // you will have to declare more names.
        register_post_type( 'spin_image', $args );


        register_taxonomy(
                'spin_image_category', array("spin_image"), array(
                        'hierarchical' => true,
                        'show_admin_column' => true,
                        'label' => __('Categories'), 
                        'singular_label' => __('Category'), 
                        'rewrite' => array( 'slug' => 'spin_image_category'  )));
        register_taxonomy(
                'spin_image_tag', array("spin_image"), array(
                        'hierarchical' => false,
                        'show_admin_column' => true,
                        'label' => __('Tags'), 
                        'singular_label' => __('Tag'), 
                        'rewrite' => array( 'slug' => 'spin_image_tag'  )));

        //parent::register_post_types();


        }

    }
}

function spin_enqueue_scripts() {
    wp_enqueue_script('customm.js', plugins_url('includes/js/customm.js', __FILE__),array('jquery'), false);
    wp_enqueue_style('spin-image-gallery.css', plugins_url('includes/css/spin-image-gallery.css', __FILE__), false);	

    if ( class_exists( 'WooCommerce' ) && is_product() ) {
      wp_enqueue_style('custom-css', plugins_url('includes/css/custom.css', __FILE__),'1.0', true);
      wp_enqueue_script('custom-js', plugins_url('includes/js/custom.js', __FILE__),array('jquery'), false);
    }
}

add_action( 'wp_enqueue_scripts', 'spin_enqueue_scripts' ); 
add_action('plugins_loaded','wpis_remove_woo_hooks');
function wpis_remove_woo_hooks() {
    remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
    add_action( 'woocommerce_before_single_product_summary', 'wpis_show_product_image', 10 ); 

}
function wpis_show_product_image() {
    require_once 'includes/product-image.php';
}

function wpis_get_version(){
    if ( ! function_exists( 'get_plugins' ) )
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
    $plugin_file = basename( ( __FILE__ ) );
    return $plugin_folder[$plugin_file]['Version'];
}

$spin_image_gallery = new Spin_Image_Gallery();
