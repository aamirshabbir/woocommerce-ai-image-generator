<?php
/**
 * Plugin Name: WooCommerce AI Image Generator
 * Description: Automatically generates product images using AI for WooCommerce products without images.
 * Version: 1.0.0
 * Author: Amir Shabbir
 * Author URI: https://github.com/amirshabbir
 * License: GPL2
 * Text Domain: wc-ai-image-generator
 *
 * @package WC_AI_Image_Generator
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WC_AI_IMG_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_AI_IMG_URL', plugin_dir_url( __FILE__ ) );

// Include main class
require_once WC_AI_IMG_PATH . 'includes/class-ai-image-generator.php';

// Init plugin
add_action( 'plugins_loaded', function() {
    new WC_AI_Image_Generator();
});
