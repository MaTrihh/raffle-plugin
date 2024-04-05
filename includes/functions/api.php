<?php

function generarCodigo($longitud) {
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitudCaracteres = strlen($caracteres);
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, $longitudCaracteres - 1)];
    }
    return $codigo;
}

function separarDigitos($codigo) {
    $digitos = array();

    // Iterar sobre cada carácter del código
    for ($i = 0; $i < strlen($codigo); $i++) {
        $caracter = $codigo[$i];
        // Verificar si el carácter es un dígito
        if (is_numeric($caracter)) {
            // Si es un dígito, agregarlo al array de dígitos
            $digitos[] = intval($caracter);
        } else {
            // Si no es un dígito, agregar el carácter tal cual al array de dígitos
            $digitos[] = $caracter;
        }
    }

    return $digitos;
}

function guardarCodigo($codigo) {
    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_codes';

    $datos = array(
        'codigo' => $codigo,
        'canjeado' => 0
    );

    $formatos = array(
        '%s',
        '%d'
    );

    $wpdb->insert($tabla_ofertas, $datos, $formatos);

    if ($wpdb->last_error) {
        return false;
    } else {
        return true;
    }
}

function verificarCodigo($codigo) {

    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_codes';

    $consulta = $wpdb->prepare("SELECT COUNT(*) FROM $tabla_ofertas WHERE codigo = %s", $codigo);
    $existe_codigo = $wpdb->get_var($consulta);

    // Si $existe_codigo es mayor que 0, significa que el código ya existe en la tabla
    if ($existe_codigo > 0) {
        return true;
    } else {
        return false;
    }

}

function verificarCodigoCanjeado($codigo) {

    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_codes';

    $consulta = $wpdb->prepare("SELECT COUNT(*) FROM $tabla_ofertas WHERE codigo = %s AND canjeado = 0", $codigo);
    $existe_codigo = $wpdb->get_var($consulta);

    // Si $existe_codigo es mayor que 0, significa que el código ya existe en la tabla
    if ($existe_codigo > 0) {
        return true;
    } else {
        return false;
    }

}


function getCodigo()
{

    $bandera = false;

    while(!$bandera){
        $codigo = generarCodigo(4);

        if(!verificarCodigo($codigo)){
            guardarCodigo($codigo);
    
            wp_send_json(array('success' => true, 'code' => $codigo));
        }
    }
    
}
add_action('wp_ajax_getCodigo', 'getCodigo');

function hayPremio() {

}

function getPremioAleatorio(){

}

function canjearCodigo() {

    //Coger el codigo, y ver si esta guardado en la bbdd
    $result = verificarCodigoCanjeado($_POST['codigo']);

    //Ver si no está canjeado
    if($result){

        //Funcion para sacar el premio
        $premio = hayPremio();

        //Retornar true o false si ha ganado premio o no y la informacion del premio
        if($premio){

            //Ver que premio toca
            $premioConseguido = getPremioAleatorio();

            //Retornar premio
            wp_send_json(array('error' => 0, 'mensaje' => 'Te ha tocado un premio', 'premio' => $premioConseguido));
        }else{

            //Retornar false, no hay premio
            wp_send_json(array('error' => 1, 'mensaje' => 'No hay premio'));
        }
        
    
    }else{

        //Retornar error
        wp_send_json(array('error' => 2, 'mensaje' => 'Este codigo ya ha sido canjeado'));
    }

}
add_action('wp_ajax_canjearCodigo', 'canjearCodigo');

function crearCampana(){

    $premios = $_POST['premios'];
    $campana = $_POST['campana'];

    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_prizes';

    foreach($premios as $premio){
        $datos = array(
            'nombre' => $premio[0],
            'cantidad' => $premio[1],
            'descripcion' => $premio[2],
            'probabilidad' => $premio[3],
        );

    
        $formatos = array(
            '%s',
            '%d',
            '%s',
            '%d',
        );
    
        $wpdb->insert($tabla_ofertas, $datos, $formatos);
    }

    $tabla_ofertas2 = $wpdb->prefix . 'raffle_config';

    $datos2 = array(
        'campana_permitida' => $campana
    );
    
    $formatos2 = array(
        '%s'
    );
    
    $wpdb->insert($tabla_ofertas2, $datos2, $formatos2);
    

    if ($wpdb->last_error) {
        wp_send_json(array('success' => false));
    } else {
        wp_send_json(array('success' => true));
    }

}
add_action('wp_ajax_crearCampana', 'crearCampana');

function existeCampana() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "SELECT campana_permitida FROM $tabla";
    $count = $wpdb->get_results($consulta_sql);

    if(!empty($count)){
        return true;
    }else{
        return false;
    }
}

function buscarCampana(){
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "SELECT * FROM $tabla";
    $campana = $wpdb->get_results($consulta_sql);

    wp_send_json(array('success' => true, 'campana' => $campana[0]->campana_permitida));
    
}
add_action('wp_ajax_buscarCampana', 'buscarCampana');

function rellenarTablaPremios() {

    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes';

    $consulta_sql = "SELECT * FROM $tabla";
    $resultados = $wpdb->get_results($consulta_sql);

    $premios = array();

    // Recorrer los resultados y crear objetos Oferta
    foreach ($resultados as $fila) {
        $premio = array(
            $fila->id,
            $fila->nombre,
            $fila->cantidad,
            $fila->descripcion,
            $fila->probabilidad
        );
        // Agregar la oferta al array
        $premios[] = $premio;
    }

    wp_send_json(array('success' => true, 'premios' => $premios));

}
add_action('wp_ajax_rellenarTablaPremios', 'rellenarTablaPremios');