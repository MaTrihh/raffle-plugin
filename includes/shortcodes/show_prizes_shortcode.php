<?php

function show_prizes_shortcode()
{
    $premios = rp_getPremiosByUser(get_current_user_id());
    
    foreach($premios as $premioData){
        $premio = rp_getPremioById($premioData->idPremio)
        ?>
        <div class="containerBack">
            <div class="cardBack">
                <img src="<?php echo get_user_meta($premio->idAsociado, 'logotipo', true); ?>" id="cardImg">
                <div class="card-content">
                    <h2 class="card-title" id="cardTitle">
                        <?php echo $premio->nombre; ?>
                    </h2>
                    <p class="card-description" id="cardDescription">
                        <?php echo $premio->descripcion; ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}
add_shortcode('show_prizes', 'show_prizes_shortcode');