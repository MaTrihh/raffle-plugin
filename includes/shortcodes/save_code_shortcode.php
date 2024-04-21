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

        .btnCerrar {
            background-color: #4CAF50;
            /* Color de fondo */
            border: none;
            /* Sin borde */
            color: white;
            /* Color del texto */
            padding: 15px 32px;
            /* Espacio de relleno */
            text-align: center;
            /* Alineación del texto */
            text-decoration: none;
            /* Sin decoración de texto */
            display: inline-block;
            /* Mostrar como elemento en línea */
            font-size: 16px;
            /* Tamaño de fuente */
            margin: 4px 2px;
            /* Margen */
            cursor: pointer;
            /* Cursor de tipo puntero */
            border-radius: 8px;
            /* Radio de borde para esquinas redondeadas */
            transition-duration: 0.4s;
            /* Duración de la transición */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            /* Sombra */
        }

        .btnCerrar:hover {
            background-color: #45a049;
            /* Cambio de color de fondo al pasar el mouse */
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

        .modal-content2 {
            background-color: #B1F3B8;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            max-width: 600px;
            /* Ancho máximo de la ventana */
            position: fixed;
            z-index: 1;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
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

        #modalPremio img {
            max-width: 70%;
            margin-bottom: 10px;
            display: inline-block;
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
            text-align: center;
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

            function mostrarTodo() {
                jQuery(".premio-info").css('display', 'block');
                jQuery("#detalles").css('display', 'block');
            }

            function apagarTodo() {
                jQuery(".premio-info").css('display', 'none');
                jQuery("#detalles").css('display', 'none');
            }

            jQuery('.btnCerrar').on('click', function () {
                jQuery("#modalPremio").css("display", 'none');
                location.reload();
            });

            jQuery('.close').on('click', function () {
                jQuery("#modalPremio").css("display", 'none');
                location.reload();
            });

            jQuery('#modalPremio').on('hidden.bs.modal', function (e) {
                location.reload();
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
                            var newUrl = response.foto_url; // Concatenar el nuevo valor a la URL existente

                            url = url + newUrl; // Concatenar el nuevo fragmento a la URL existente
                            mostrarTodo();
                            jQuery("#foto").attr("src", url);
                            jQuery("#descripcion").html(response.descripcion);
                            if (response.comercio != 0) {
                                jQuery("#comercio").html("Puedes cangearlo en el Punto de Control de C/Bolivia, 2");
                            } else {
                                jQuery("#comercio").html(response.comercio);
                            }

                            jQuery("#caducidad").html(response.fecha_caducidad);
                            jQuery("#modalPremio").css("display", 'block');

                        } else {
                            apagarTodo();
                            bandera = false;
                            var url = "https://alcalacentro.es/wp-content/plugins/raffle-plugin/includes/prizes_img/"; // Obtener el valor actual de src
                            var newUrl = 'sin_premio.jpg'; // Concatenar el nuevo valor a la URL existente

                            url = url + newUrl; // Concatenar el nuevo fragmento a la URL existente
                            jQuery("#foto").attr("src", url);
                            jQuery("#modalPremio").css("display", 'block');
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

    <div id="modalPremio">
        <div class="modal-content2">
            <span class="close">&times;</span>
            <img src="" id="foto" alt="Imagen del premio">
            <h2 id="detalles">Detalles del Premio</h2>
            <div class="premio-info">
                <p><strong>Descripción:</strong></p>
                <p id="descripcion"></p>
                <p><strong>Comercio donde canjearlo:</strong></p>
                <p id="comercio"></p>
                <p><strong>Fecha de caducidad:</strong></p>
                <p id="caducidad"></p>
                <button class="btnCerrar">Cerrar</button>
            </div>
        </div>
    </div>
    <?php
}
add_shortcode('save_code', 'save_code_shortcode');