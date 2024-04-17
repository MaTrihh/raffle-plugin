<?php

function generarCodigo($longitud)
{
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitudCaracteres = strlen($caracteres);
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, $longitudCaracteres - 1)];
    }
    return $codigo;
}

function separarDigitos($codigo)
{
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

function guardarCodigo($codigo)
{
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

function verificarCodigo($codigo)
{

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

function verificarCodigoCanjeado($codigo)
{

    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_codes';

    $consulta = $wpdb->prepare("SELECT COUNT(*) FROM $tabla_ofertas WHERE codigo = %s AND canjeado = 0", $codigo);
    $existe_codigo = (int) $wpdb->get_var($consulta);

    // Si $existe_codigo es mayor que 0, significa que el código ya existe en la tabla
    if ($existe_codigo == 1) {
        return true;
    } else {
        return false;
    }

}


function getCodigo()
{

    $bandera = false;

    while (!$bandera) {
        $codigo = generarCodigo(4);

        if (!verificarCodigo($codigo)) {
            guardarCodigo($codigo);

            wp_send_json(array('success' => true, 'code' => $codigo));
        }
    }

}
add_action('wp_ajax_getCodigo', 'getCodigo');

function getPorcentajeConfig()
{
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "SELECT porcentaje_premio FROM $tabla";
    $porcentaje = $wpdb->get_results($consulta_sql);

    if (!empty($porcentaje)) {
        return $porcentaje[0]->porcentaje_premio;
    } else {
        return 0;
    }
}

function hayPremio()
{

    //Ver porcentaje de premio que hay
    $porcentaje = getPorcentajeConfig();
    $porcentaje = $porcentaje/100;

    $numero_aleatorio = mt_rand() / mt_getrandmax();
    $numero_aleatorio = number_format($numero_aleatorio, 2);

    if ($porcentaje != 0) {
        //Comprobar si hay premio
        if ($numero_aleatorio < $porcentaje) {
            return true;
        } else {
            return false;
        }
    }
}

function getPremioAleatorio()
{

    //Ver porcentaje de los premios
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes';

    $consulta_sql = "SELECT * FROM $tabla WHERE cantidad > 0";
    $resultados = $wpdb->get_results($consulta_sql);

    $premios = array();
    $probabilidades = array();
    $probabilidades_acumuladas = array();
    $premio = array();
    $premio_devolver = null;

    // Recorrer los resultados y crear objetos Oferta
    foreach ($resultados as $fila) {
        $premio = array(
            $fila->id,
            $fila->nombre,
            $fila->cantidad,
            $fila->descripcion,
            $fila->premio_global,
            $fila->probabilidad
        );
        // Agregar la oferta al array
        $premios[] = $premio;
        $probabilidades[] = $premio[5]/100;
        $premio = array();
        
    }

    for ($i = 0; $i < count($probabilidades); $i++) {
        if($i-1 >= 0){
            $contador = $i;
            $probabilidad = $probabilidades[$i];

            do{
                $contador--;
                $probabilidad = $probabilidades[$contador] + $probabilidad;

            }while($contador != 0);

            $probabilidades_acumuladas[] = $probabilidad;
        }else{
            $probabilidades_acumuladas[] = $probabilidades[$i];
        }
    }


    while($premio_devolver == null){
        $numero_aleatorio = mt_rand() / mt_getrandmax();
        $numero_aleatorio = number_format($numero_aleatorio, 2);

        for($f = 0; $f < count($probabilidades_acumuladas); $f++) {
            if($probabilidades_acumuladas[$f] > $numero_aleatorio){
                $premio_devolver = $premios[$f];
                return $premio_devolver;
            }
        }
    }
}

function prueba(){
    
    $premios = array(
        '232' => 0,
        '233' => 0,
        '234' => 0,
        '235' => 0,
        'No hay Premio' => 0
    );

    for($i = 0; $i <= 1000; $i++){

        if(hayPremio()){
            $premio_nuevo = getPremioAleatorio();

            foreach($premios as $premio => $veces){
                if(intval($premio) == $premio_nuevo[0]){
                    $premios[$premio] = $veces + 1;
                }
            }
        }else{
            $premios['No hay Premio'] = $premios['No hay Premio']+1;
        }
    }

    return $premios;
}

function guardarPremioUsuario($premio, $user_id) {
    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_prizes_user';

    if($premio[4] == 1){
        $premio_config = getPremioConfigById($premio[0]);
        $comercios_config = $premio_config['comercio_config'];


        do{
            $numeroAleatorio = rand(1, count($comercios_config) - 1);
            
            $cantidad = $comercios_config[$numeroAleatorio]['cantidad'];
            $hoy = new DateTime();

            // Agregar 15 días
            $hoy->add(new DateInterval('P15D'));
            $fecha_caducidad = $hoy->format('Y-m-d');

            if($cantidad != 0){
                $datos = array(
                    'idPremio' => $premio[0],
                    'user_id' => $user_id,
                    'idAsociado' => $comercios_config[$numeroAleatorio]['comercioID'],
                    'canjeado' => 0,
                    'fecha_caducidad' => $fecha_caducidad
                );
        
                $formatos = array(
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                );
        
                $wpdb->insert($tabla_ofertas, $datos, $formatos);
        
                if ($wpdb->last_error) {
                    return -1;
                } else {
                    return $comercios_config[$numeroAleatorio]['comercioID'];
                }
            }
        }while($cantidad == 0);
        
    }else{
        $hoy = new DateTime();

        // Agregar 15 días
        $hoy->add(new DateInterval('P15D'));
        $fecha_caducidad = $hoy->format('Y-m-d');

        $datos = array(
            'idPremio' => $premio[0],
            'user_id' => $user_id,
            'idAsociado' => 0,
            'canjeado' => 0,
            'fecha_caducidad' => $fecha_caducidad
        );

        $formatos = array(
            '%d',
            '%d',
            '%d',
            '%d',
            '%s'
        );

        $wpdb->insert($tabla_ofertas, $datos, $formatos);

        if ($wpdb->last_error) {
            return -1;
        } else {
            return 0;
        }
    }
    
}

function canjearCodigo()
{

    //Coger el codigo, y ver si esta guardado en la bbdd
    $result = verificarCodigoCanjeado($_POST['codigo']);

    //Ver si no está canjeado
    if ($result) {

        //Funcion para sacar el premio
        $premio = hayPremio();

        //Retornar true o false si ha ganado premio o no y la informacion del premio
        if ($premio) {

            //Ver que premio toca
            $premioConseguido = getPremioAleatorio();

            //Guardar premio
            $idComercio = guardarPremioUsuario($premioConseguido, $_POST['user_id']);
            $comercio = get_userdata($idComercio)->first_name;

            $hoy = new DateTime();

            // Agregar 15 días
            $hoy->add(new DateInterval('P15D'));
            $fecha_caducidad = $hoy->format('Y-m-d');

            //Retornar premio
            wp_send_json(array('error' => 0, 'mensaje' => 'Te ha tocado un premio', 'nombre' => $premioConseguido[1], 'idComercio' => $idComercio, 'id' => $premioConseguido[0], 'descripcion' => $premioConseguido[3], 'fecha_caducidad' => $fecha_caducidad, 'comercio' => $comercio));
        } else {

            //Retornar false, no hay premio
            wp_send_json(array('error' => 1, 'mensaje' => 'No hay premio'));
        }


    } else {

        //Retornar error
        wp_send_json(array('error' => 2, 'mensaje' => 'Este codigo ya ha sido canjeado'));
    }

}
add_action('wp_ajax_canjearCodigo', 'canjearCodigo');

function getUsersCampana($campana){
    $usuarios = get_users( array( 'role' => 'comercio_asociado') );
    $usuarios_campana = array();

    foreach($usuarios as $usuario){
        $campanas_usuario = get_user_meta($usuario->ID, 'opciones_formulario', true);
    
        if(in_array($campana, $campanas_usuario)){
            array_push($usuarios_campana, $usuario);
        }
    }

    return $usuarios_campana;
}

function guardarConfigPremio($id_premio, $premios_config){


    // Procesar la configuración del premio
    if ($id_premio !== 0 && is_array($premios_config) && !empty($premios_config)) {
        // Construir la estructura de datos
        $data = array(
            'id' => $id_premio,
            'comercio_config' => array()
        );

        foreach ($premios_config as $premio) {
            $comercioID = isset($premio['comercioID']) ? $premio['comercioID'] : 0;
            $cantidad = isset($premio['cantidad']) ? $premio['cantidad'] : 0;

            // Verificar si los datos son válidos
            if (is_numeric($comercioID) && is_numeric($cantidad)) {
                // Agregar la configuración del premio al array
                $data['comercio_config'][] = array(
                    'comercioID' => $comercioID,
                    'cantidad' => $cantidad
                );
            }
        }

        // Codificar el array en JSON
        $jsonString = json_encode($data);

        // Guardar el JSON en un archivo local
        $fileDirectory = plugin_dir_path(__FILE__) . 'config/';

        if (!file_exists($fileDirectory)) {
            if (!mkdir($fileDirectory, 0755, true)) {
                echo "Error al crear el directorio de configuración.";
                exit;
            }
        }

        $file = $fileDirectory . $id_premio . '.json';

        if (file_put_contents($file, $jsonString) !== false) {
            // Obtener la URL del directorio del plugin
            $plugin_url = plugins_url('/', __FILE__);

            // Construir la URL completa del archivo JSON
            $file_url = $plugin_url . 'config/' . $id_premio . '.json';

            // Devolver la URL o utilizarla según sea necesario
            echo "La configuración del premio se ha guardado correctamente. URL del archivo: $file_url";
        } else {
            echo "Error al guardar la configuración del premio. Detalles: " . error_get_last()['message'];
        }

    } else {
        echo "Datos de entrada inválidos.";
    }
}

function guardarPremios($premios, $premios_config){


    global $wpdb;
    $tabla_ofertas = $wpdb->prefix . 'raffle_prizes';

    $contador = 0;
    foreach ($premios as $premio) {

        if($premio[3] == 1){
            
                $datos = array(
                    'nombre' => $premio[0],
                    'cantidad' => $premio[1],
                    'descripcion' => $premio[2],
                    'premio_global' => $premio[3],
                    'probabilidad' => $premio[4]
                );
        
        
                $formatos = array(
                    '%s',
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                );
        
                $wpdb->insert($tabla_ofertas, $datos, $formatos);

                $nuevo_id = $wpdb->insert_id;
                guardarConfigPremio($nuevo_id, $premios_config[$contador]);
                $contador++;
            
        }else{
            $datos = array(
                'nombre' => $premio[0],
                'cantidad' => $premio[1],
                'descripcion' => $premio[2],
                'premio_global' => $premio[3],
                'probabilidad' => $premio[4]
            );
    
    
            $formatos = array(
                '%s',
                '%d',
                '%s',
                '%d',
                '%d',
            );
    
            $wpdb->insert($tabla_ofertas, $datos, $formatos);
        }
        
    }

    if ($wpdb->last_error) {
        return false;
    } else {
        return true;
    }
}

function crearCampana() 
{
    global $wpdb;

    $premios = $_POST['premios'];
    $premios_config = $_POST['premios_config'];
    $campana = $_POST['campana'];
    $porcentaje = $_POST['porcentaje'];

    $resultado = guardarPremios($premios, $premios_config);

    if($resultado){
        $tabla_ofertas = $wpdb->prefix . 'raffle_config';

        $datos = array(
            'campana_permitida' => $campana,
            'porcentaje_premio' => $porcentaje
        );

        $formatos = array(
            '%s',
            '%d'
        );

        $wpdb->insert($tabla_ofertas, $datos, $formatos);


        if ($wpdb->last_error) {
            wp_send_json(array('success' => false));
        } else {
            wp_send_json(array('success' => true));
        }
    }else{
        wp_send_json(array('success' => false));
    }
    

}
add_action('wp_ajax_crearCampana', 'crearCampana');

function existeCampana()
{
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "SELECT campana_permitida FROM $tabla";
    $count = $wpdb->get_results($consulta_sql);

    if (!empty($count)) {
        return true;
    } else {
        return false;
    }
}

function buscarCampana()
{
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_config';

    $consulta_sql = "SELECT * FROM $tabla";
    $campana = $wpdb->get_results($consulta_sql);

    wp_send_json(array('success' => true, 'campana' => $campana[0]->campana_permitida, 'porcentaje' => $campana[0]->porcentaje_premio));

}
add_action('wp_ajax_buscarCampana', 'buscarCampana');

function rellenarTablaPremios()
{

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
            $fila->premio_global,
            $fila->probabilidad
        );
        // Agregar la oferta al array
        $premios[] = $premio;
    }

    wp_send_json(array('success' => true, 'premios' => $premios));

}
add_action('wp_ajax_rellenarTablaPremios', 'rellenarTablaPremios');

function getPremiosConseguidos() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes_user';

    $consulta_sql = "SELECT * FROM $tabla";
    $premios = $wpdb->get_results($consulta_sql);

    return $premios;
}

function rp_getPremioById($id) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes';

    $consulta_sql = "SELECT * FROM $tabla WHERE id = $id";
    $premios = $wpdb->get_results($consulta_sql);
 
    return $premios[0];
}

function rp_bajarCantidadPremio($premio) {

    global $wpdb;

    $cantidad = $premio[2];
    $cantidad = $cantidad-1;

    $tabla_ofertas = $wpdb->prefix . 'raffle_prizes';
    
    // Construir la condición WHERE
    $condicion = array('id' => $premio[0]);

    $premio[2] = $cantidad;

    $premioNuevo = array(
        'id' => $premio[0],
        'nombre' => $premio[1],
        'cantidad' => $premio[2],
        'descripcion' => $premio[3],
        'premio_global' => $premio[4],
        'probabilidad' => $premio[5]
    );
    
    // Realizar la actualización en la base de datos
    $resultado = $wpdb->update($tabla_ofertas, $premioNuevo, $condicion);
}

function rp_getPremiosByUser($user_id) {

    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes_user';

    $consulta_sql = "SELECT * FROM $tabla WHERE user_id = $user_id";
    $premios = $wpdb->get_results($consulta_sql);

    return $premios;

}

function buscar_premios_sorteo() {
    $premiosObj = rp_getPremiosByUser($_POST['user_id']);
    $premios = array();

    foreach($premiosObj as $premio){

        $premioData = rp_getPremioById($premio->idPremio);
        array_push($premios, $premioData);
    }

    wp_send_json(array('success' => true, 'premios' => $premios));
}
add_action('wp_ajax_buscar_premios_sorteo', 'buscar_premios_sorteo');

function getPremioConseguido($idPremio, $user_id) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes_user';

    $consulta_sql = "SELECT * FROM $tabla WHERE idPremio = $idPremio AND user_id = $user_id";
    $premios = $wpdb->get_results($consulta_sql);

    return $premios;
}
function user_premio_sorteo() {

    $ids = $_POST['valoresCheckbox'];
    $user_id = $_POST['user_id'];

    foreach($ids as $id){
        $premio = getPremioConseguido($id, $user_id);
        canjear_premio($premio);
    }

    wp_send_json(array('success' => true));
}
add_action('wp_ajax_user_premio_sorteo', 'user_premio_sorteo');

function canjear_premio($premio) {
    global $wpdb;

    $tabla_ofertas = $wpdb->prefix . 'raffle_prizes_user';

    $premioData = $premio[0];
    
    // Construir la condición WHERE
    $condicion = array('id' => $premioData->id);

    $premioNuevo = array(
        'id' => $premioData->id,
        'idPremio' => $premioData->idPremio,
        'user_id' => $premioData->user_id,
        'canjeado' => 1,
        'fecha_canjeado' => date("Y-m-d")
    );
    
    // Realizar la actualización en la base de datos
    return $resultado = $wpdb->update($tabla_ofertas, $premioNuevo, $condicion);
}

function getTodosPremiosCanjeados() {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes_user';

    $consulta_sql = "SELECT * FROM $tabla WHERE canjeado = 1";
    $premios = $wpdb->get_results($consulta_sql);

    return $premios;
}

function getTodosPremiosConseguidosByComercio($idAsociado) {
    global $wpdb;
    $tabla = $wpdb->prefix . 'raffle_prizes';

    $consulta_sql = "SELECT id FROM $tabla WHERE idAsociado = $idAsociado";
    $premios_db = $wpdb->get_results($consulta_sql);
    $premios_ids = array();

    foreach($premios_db as $premio_db) {

        array_push($premios_ids, $premio_db->id);
    }

    $ids_str = implode(",", $premios_ids);

    $tabla2 = $wpdb->prefix . 'raffle_prizes_user';
    $consulta_sql2 = "SELECT * FROM $tabla2 WHERE idPremio IN ($ids_str)";
    $premios_conseguidos = $wpdb->get_results($consulta_sql2);
    $premios_array = array();

    foreach($premios_conseguidos as $premio_conseguido) {

        $premios_array[] = array(
            'id' => $premio_conseguido->id,
            'idPremio' => $premio_conseguido->idPremio,
            'user_id' => $premio_conseguido->user_id,
            'canjeado' => $premio_conseguido->canjeado,
            'fecha_canjeado' => $premio_conseguido->fecha_canjeado
        );

    }

    return $premios_conseguidos;
}

function cancelarCampana(){
    // Obtiene el objeto global $wpdb
    global $wpdb;

    $tabla = $wpdb->prefix . 'raffle_config';
    // Realiza la consulta para borrar todos los registros de la tabla
    $resultado = $wpdb->query( "TRUNCATE TABLE $tabla" );

    $tabla = $wpdb->prefix . 'raffle_prizes';
    // Realiza la consulta para borrar todos los registros de la tabla
    $resultado = $wpdb->query( "TRUNCATE TABLE $tabla" );

    $tabla = $wpdb->prefix . 'raffle_prizes_user';
    // Realiza la consulta para borrar todos los registros de la tabla
    $resultado = $wpdb->query( "TRUNCATE TABLE $tabla" );

    wp_send_json(array('success' => true));
}
add_action('wp_ajax_cancelarCampana', 'cancelarCampana');

function getComercios() {

    $campana = $_POST['campana'];
    $comercios = getUsersCampana($campana);
    $comercios_data = array();

    foreach($comercios as $comercio){
        
        $comercios_data[] = array(
                                'ID' => $comercio->data->ID,
                                'nombre' => $comercio->data->display_name
                            );
    }

    
    wp_send_json(array('success' => true, 'comercios' => $comercios_data));
}
add_action('wp_ajax_getComercios', 'getComercios');

function getPremiosConfig() {
    $cofig_premios = array();

    $ruta_carpeta = plugin_dir_path(__FILE__) . 'config/';

    // Obtener la lista de archivos en la carpeta
    $archivos = scandir($ruta_carpeta);

    // Array para almacenar los nombres de los archivos JSON sin la extensión
    $archivos_json = array();

    // Iterar sobre los archivos
    foreach ($archivos as $archivo) {
        // Verificar si el archivo es un archivo JSON
        if (pathinfo($archivo, PATHINFO_EXTENSION) == 'json') {
            // Obtener el nombre del archivo sin la extensión y almacenarlo en el array
            $nombre_archivo = pathinfo($archivo, PATHINFO_FILENAME);
            $archivos_json[] = $nombre_archivo;
        }
    }

    foreach($archivos_json as $archivo_json){
        // Lee el contenido del archivo JSON
        $ruta = plugins_url('/', __FILE__) . 'config/'. $archivo_json .'.json';
        $json = file_get_contents($ruta);

        // Decodifica el JSON y lo convierte en un array
        $array = json_decode($json, true);

        $config_premios[] = $array;
    }

    return $config_premios;
    
}

function getPremioConfigById($idPremio) {
    $premios_config = getPremiosConfig();

    foreach($premios_config as $config) {
        if($config['id'] == $idPremio){
            return $config;
        }
    }
}