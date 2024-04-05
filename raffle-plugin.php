<?php
/**
 * Plugin Name: Raffle Plugin
 * Description: Plugin de sorteos para la pagina alcalacentro.es
 * Plugin URI: https://github.com/MaTrihh/raffle-plugin
 * Author: Ibai Ocaña Lorente
 * Version: 0.1
 * Author URI: https://github.com/MaTrihh/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('RAFFLE_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Incluir archivos principales
include_once plugin_dir_path( __FILE__ ) . 'includes/pages/rp_pages.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/functions/functions.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes/shortcodes.php';

register_activation_hook( __FILE__, 'raffle_plugin_tablas' );

function raffle_plugin_agregar_estilos_admin() {
    wp_enqueue_style( 'rp_admin', plugins_url( 'includes/styles/rp_admin.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'raffle_plugin_agregar_estilos_admin' );

function raffle_plugin_enqueue_scripts() {
    // Define la URL base de tu plugin
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_script('jquery');
    wp_enqueue_style('dashicons');

    wp_localize_script('rp_script', 'my_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'raffle_plugin_enqueue_scripts' );