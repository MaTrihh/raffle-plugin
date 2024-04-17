<?php

function save_code_shortcode()
{
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .rp_container {
            text-align: center;
        }

        .code-inputs {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-bottom: 20px;
        }

        .code-input {
            width: 60px;
            height: 80px !important;
            text-align: center;
            border: 2px solid #ccc !important;
            border-radius: 8px !important;
            background-color: white !important;
            font-size: 24px !important;
        }

        input[type="text"]::-webkit-input-placeholder {
            font-size: 24px;
        }

        input[type="text"]::-moz-placeholder {
            font-size: 24px;
        }

        input[type="text"]:-ms-input-placeholder {
            font-size: 24px;
        }

        input[type="text"]:-moz-placeholder {
            font-size: 24px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: transparent;
            margin: auto;
            padding: 20px;
            width: 50%;
            height: 600px;
            /* Ajusta el tamaño de la ventana modal según tus necesidades */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Estilo para la ventana modal */
        #modalPremio {
            display: none;
            /* Por defecto, la ventana modal está oculta */
            position: fixed;
            /* Se posiciona en la ventana del navegador */
            z-index: 1;
            /* Asegura que la ventana modal esté sobre todo el contenido */
            left: 0;
            top: 0;
            width: 100%;
            /* La ventana modal ocupa toda la pantalla */
            height: auto;
            overflow: auto;
            /* Permite hacer scroll si el contenido de la ventana modal es largo */
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo semi-transparente */
        }

        /* Estilo para el contenido de la ventana modal */
        #modalPremio .modal-content2 {
            background-color: #fefefe;
            /* Fondo blanco */
            margin: 15% auto;
            /* Centra la ventana modal verticalmente y la deja con un margen en los lados */
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            max-width: 400px;
            /* Ancho máximo de la ventana modal */
        }

        /* Estilo para el botón de cerrar */
        #modalPremio .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #modalPremio .close:hover,
        #modalPremio .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Estilo para los párrafos que contienen la información del premio */
        #modalPremio .premio-info p {
            margin-bottom: 10px;
            /* Espacio entre los párrafos */
        }
    </style>
    <script>
        jQuery(document).ready(function () {
            var bandera = true;
            // Cerrar la ventana modal al terminar de reproducir el GIF
            jQuery("#gif").on("load", function () {
                if(bandera){
                    setTimeout(function () {
                        jQuery("#myModal").fadeOut();
                        jQuery("#gif").attr("src", "");
                        jQuery("#modalPremio").fadeIn();
                    }, 10000);
                }else{
                    setTimeout(function () {
                        jQuery("#myModal").fadeOut();
                        jQuery("#gif").attr("src", "");
                    }, 10000);
                }
            });

            jQuery('myModal').on('hidden.bs.modal', function (e) {
                if(bandera){
                    jQuery("#modalPremio").fadeIn();
                }
                jQuery("#gif").attr("src", "");
            });

            // Cerrar la ventana modal al hacer clic en la "x" o fuera de la ventana
            jQuery(".close, .modal").click(function () {
                jQuery(this).fadeOut();
                jQuery("#gif").attr("src", "");
            });

            jQuery('#saveBtn').on('click', function () {
                var code1 = jQuery('#code1').val();
                var code2 = jQuery('#code2').val();
                var code3 = jQuery('#code3').val();
                var code4 = jQuery('#code4').val();

                var code = code1 + code2 + code3 + code4;

                var user_id = jQuery('#user_id').val();

                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "canjearCodigo",
                        codigo: code,
                        user_id: user_id
                    },
                    success: function (response) {
                        if (response.error == 0) {
                            var url = "https://alcalacentro.es/wp-content/plugins/raffle-plugin/includes/prizes_img/"; // Obtener el valor actual de src
                            var newUrl = '/' + response.id + '.gif'; // Concatenar el nuevo valor a la URL existente

                            url = url + newUrl; // Concatenar el nuevo fragmento a la URL existente
                            jQuery("#gif").attr("src", url);
                            jQuery("#nombre").html(response.nombre);
                            jQuery("#descripcion").html(response.descripcion);
                            if(response.comercio != 0){
                                jQuery("#comercio").html("Puedes cangearlo en Electrosat Castillo");
                            }else{
                                jQuery("#comercio").html(response.comercio);
                            }
                            
                            jQuery("#caducidad").html(response.fecha_caducidad);
                            jQuery("#myModal").fadeIn();

                        } else {
                            bandera = false;
                            var url = "https://alcalacentro.es/wp-content/plugins/raffle-plugin/includes/prizes_img/"; // Obtener el valor actual de src
                            var newUrl = '/' + 0 + '.gif'; // Concatenar el nuevo valor a la URL existente

                            url = url + newUrl; // Concatenar el nuevo fragmento a la URL existente
                            jQuery("#gif").attr("src", url);
                            jQuery("#myModal").fadeIn();
                        }
                    },
                    error: function (error) {
                        // Manejar errores si los hay
                        console.error(error);
                    }
                });
            });
        });
    </script>
    <div class="rp_container">
        <h1>Canjea tu Codigo</h1>
        <div class="code-inputs">
            <input type="text" class="code-input" id="code1" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code2" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code3" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code4" maxlength="1" placeholder="-">
        </div>
        <input type="hidden" name="user_id" id="user_id" value="<?php echo get_current_user_id(); ?>">
        <button id="saveBtn" class="btn btn-primary">Canjear Codigo</button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <div id="gifContainer">
                <!-- Aquí se mostrará el GIF -->
                <img id="gif" src="<?php echo esc_url(plugins_url('raffle-plugin/includes/prizes_img')); ?>"
                    alt="Cargando..." width="1000" height="600">
            </div>
        </div>
    </div>

    <div id="modalPremio" class="modal">
        <div class="modal-content2">
            <span class="close">&times;</span>
            <h2>Detalles del Premio</h2>
            <div class="premio-info">
                <p><strong>Nombre del premio:</strong> </p><p id="nombre"></p>
                <p><strong>Descripción:</strong></p> <p id="descripcion"></p>
                <p><strong>Comercio donde canjearlo:</strong></p> <p id="comercio"></p>
                <p><strong>Fecha de caducidad:</strong></p> <p id="caducidad"></p>
            </div>
        </div>
    </div>
    <?php
}
add_shortcode('save_code', 'save_code_shortcode');