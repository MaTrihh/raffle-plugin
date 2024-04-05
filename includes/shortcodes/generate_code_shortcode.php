<?php

function generate_code_shortcode()
{

    $codigo = generarCodigo(4);

    $numeros = separarDigitos($codigo)

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

        .opacity-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .opacity-enabled {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
    <script>
        jQuery(document).ready(function () {
            jQuery('#habilitarBtn').on('click', function () {
                jQuery(".code-inputs").removeClass("opacity-disabled");
                jQuery(".code-inputs").addClass("opacity-enabled");
            
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "getCodigo"
                    },
                    success: function (response) {
                        if (response.success) {
                            var codigo = response.code;
                            var codigoArr = codigo.split("");

                            jQuery("#code1").val(codigoArr[0]);
                            jQuery("#code2").val(codigoArr[1]);
                            jQuery("#code3").val(codigoArr[2]);
                            jQuery("#code4").val(codigoArr[3]);
                        } else {
                            alert("Ha habido algun error, pruebe mas tarde");
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
        <h1>Generar Codigo</h1>
        <div class="code-inputs opacity-disabled">
            <input type="text" class="code-input" id="code1" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code2" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code3" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code4" maxlength="1" placeholder="-">
        </div>
        <button id="habilitarBtn" class="btn btn-primary">Generar Codigo</button>
    </div>
    <?php

}
add_shortcode('generate_code', 'generate_code_shortcode');