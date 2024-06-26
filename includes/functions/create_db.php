<?php

function raffle_plugin_tablas() {
    raffle_plugin_crear_tabla();
    raffle_plugin_crear_tabla_configuracion();
    raffle_plugin_crear_tabla_premios();
    raffle_plugin_crear_tabla_premios_guardados();
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

function raffle_plugin_crear_tabla_configuracion() {
    global $wpdb;
    $tabla_nombre = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "CREATE TABLE IF NOT EXISTS $tabla_nombre (
        campana_permitida VARCHAR(50) NOT NULL,
        porcentaje_premio INT(10) NOT NULL,
        PRIMARY KEY (campana_permitida)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $consulta_sql );
}

function raffle_plugin_crear_tabla_premios() {
    global $wpdb;
    $tabla_nombre = $wpdb->prefix . 'raffle_prizes';

    $consulta_sql = "CREATE TABLE IF NOT EXISTS $tabla_nombre (
        id INT NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(50) NOT NULL,
        cantidad INT NOT NULL,
        descripcion varchar(255) NOT NULL,
        premio_global TINYINT NOT NULL,
        probabilidad int NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $consulta_sql );
}

function raffle_plugin_crear_tabla_premios_guardados() {
    global $wpdb;
    $tabla_nombre = $wpdb->prefix . 'raffle_prizes_user';

    $consulta_sql = "CREATE TABLE IF NOT EXISTS $tabla_nombre (
        id INT NOT NULL AUTO_INCREMENT,
        idPremio INT NOT NULL,
        user_id INT NOT NULL,
        idAsociado INT NOT NULL,
        canjeado TINYINT NOT NULL,
        fecha_canjeado DATE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $consulta_sql );
}
