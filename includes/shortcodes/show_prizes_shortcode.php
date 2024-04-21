<?php

function show_prizes_shortcode()
{
    $premios = rp_getPremiosByUser(get_current_user_id());

    ?>
    <style>
        .containerBackRaf {
            display: flex;
            flex-wrap: wrap;
            /* Cambiado a wrap para que los elementos se envuelvan */
            justify-content: space-between;
            /* Cambiado a space-between para que los elementos estén distribuidos uniformemente */
            padding: 20px;
            text-align: center;
        }

        .cardBackRaf {
            width: calc(33.33% - 20px);
            /* Calculamos el ancho de cada tarjeta restando el margen */
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-right: 20px;
            /* Agregamos un margen derecho para separar las tarjetas */
        }

        /* Estilo para la última tarjeta de cada fila para evitar el margen derecho */
        .cardBackRaf:nth-child(3n) {
            margin-right: 0;
        }


        @media (max-width: 768px) {
            .cardBackRaf {
                width: calc(50% - 20px);
                /* Cambiamos a dos tarjetas por fila en pantallas más pequeñas */
            }

            /* Estilo para la última tarjeta de cada fila para evitar el margen derecho en pantallas más pequeñas */
            .cardBackRaf:nth-child(2n) {
                margin-right: 0;
            }
        }
    </style>
    <div class="containerBack">
    <?php

    $fecha_actual = date('Y-m-d');

    foreach ($premios as $premioData) {
        $premio = rp_getPremioById($premioData->idPremio);
        

        if($fecha_actual < $premioData->fecha_caducidad && $premioData->canjeado != 1){      
            

                if($premioData->idPremio == 4 || $premioData->idPremio == 5){
                    $foto = "https://alcalacentro.es/wp-content/plugins/raffle-plugin/includes/prizes_img/";

                    if($premioData->idPremio == 4){
                        $foto .= 'bolsa.jpg';
                    }else{
                        $foto .= 'puntos.jpg';
                    }
                }

                ?>
            
                <div class="cardBack">
                    <img src="<?php echo ($premioData->idAsociado != 0 && $premioData->idAsociado != 43) ? get_user_meta($premioData->idAsociado, 'logotipo', true) : $foto; ?>"
                        id="cardImg">
                    <div class="card-content">
                        <h2 class="card-title" id="cardTitle">
                            <?php echo $premio->nombre; ?>
                        </h2>
                        <p class="card-description" id="cardDescription">
                            <?php echo $premio->descripcion; ?>
                        </p>
                        <p class="card-description" id="cardDescription">
                            Premio canjeable en:
                            <?php echo ($premioData->idAsociado != 0) ? get_userdata($premioData->idAsociado)->first_name : "Canjeable en el Punto de Control de C/Bolivia, 2"; ?>
                        </p>
                        <p class="card-description" id="cardDescription">
                            Fecha de Validez hasta: <?php echo $premioData->fecha_caducidad; ?>
                        </p>
                    </div>
                </div>
            
            <?php
        }
    }
    ?></div><?php
}
add_shortcode('show_prizes', 'show_prizes_shortcode');