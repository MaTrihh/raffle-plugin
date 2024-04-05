<?php

function save_code_shortcode() {
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

    </style>
    <script>
        jQuery(document).ready(function () {
            jQuery('#saveBtn').on('click', function () {
                var code1 = jQuery('#code1').val();
                var code2 = jQuery('#code2').val();
                var code3 = jQuery('#code3').val();
                var code4 = jQuery('#code4').val();

                var code = code1 + code2 + code3 + code4;

                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "canjearCodigo",
                        codigo: code
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("eono");
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
        <h1>Canjea tu Codigo</h1>
        <div class="code-inputs">
            <input type="text" class="code-input" id="code1" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code2" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code3" maxlength="1" placeholder="-">
            <input type="text" class="code-input" id="code4" maxlength="1" placeholder="-">
        </div>
        <button id="saveBtn" class="btn btn-primary">Canjear Codigo</button>
    </div>
    <?php
}
add_shortcode( 'save_code', 'save_code_shortcode' );