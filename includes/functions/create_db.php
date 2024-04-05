<?php

function raffle_plugin_tablas() {
    raffle_plugin_crear_tabla();
}

function raffle_plugin_crear_tabla() {
    global $wpdb;
    $tabla_nombre = $wpdb->prefix . 'raffle_codes';

    $consulta_sql = "CREATE TABLE IF NOT EXISTS $tabla_nombre (
        codigo VARCHAR(4) NOT NULL,
        canjeado TINYINT,
        PRIMARY KEY (codigo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $consulta_sql );
}
