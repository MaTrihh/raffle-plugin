<?php
function raffle_plugin_agregar_menu()
{
    // Agregar un menú principal
    add_menu_page(
        __('Sorteos por Codigo', ''),
        __('Sorteos por Codigo', ''),
        'manage_options',
        'raffle_plugin_menu',
        'raffle_plugin_general_page',
        'dashicons-schedule',
        3
    );

    add_submenu_page(
        'raffle_plugin_menu',
        __('Configuración de Campaña', ''),
        __('Configuración de Campaña', ''),
        'manage_options',
        'raffle_plugin_campana',
        'raffle_plugin_campana_page'
    );

    add_submenu_page(
        'raffle_plugin_menu',
        __('Info Shortcodes', ''),
        __('Info Shortcodes', ''),
        'manage_options',
        'raffle_plugin_info',
        'raffle_plugin_info_page'
    );

    add_submenu_page(
        'raffle_plugin_menu',
        __('Premios Conseguidos', ''),
        __('Premios Conseguidos', ''),
        'manage_options',
        'raffle_plugin_prizes',
        'raffle_plugin_prizes_page'
    );

    add_submenu_page(
        'raffle_plugin_menu',
        __('Premios Canjeados', ''),
        __('Premios Canjeados', ''),
        'manage_options',
        'raffle_plugin_user_prizes',
        'raffle_plugin_user_prizes_page'
    );
}
add_action('admin_menu', 'raffle_plugin_agregar_menu');

