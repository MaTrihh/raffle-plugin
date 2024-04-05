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
}
add_action('admin_menu', 'raffle_plugin_agregar_menu');

