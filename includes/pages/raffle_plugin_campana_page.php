<?php
function raffle_plugin_campana_page()
{
    if(!existeCampana()){
        
        ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            jQuery(document).ready(function () {

                var premios = [];

                jQuery('#addPremio').on('click', function () {
                    jQuery('#addPremioModal').modal('show');
                });

                jQuery('#crearPremioBtn').on('click', function () {
                    jQuery('#addPremioModal').modal('hide');

                    var nombre = jQuery("#nombre").val();
                    var cantidad = jQuery("#cantidad").val();
                    var descripcion = jQuery("#descripcion").val();
                    var probabilidad = jQuery("#probabilidad").val();
                    if(jQuery("#global").prop('checked')){
                        var premio_global = "Si";
                        var premio_val = 1;
                    }else{
                        var premio_global = "No";
                        var premio_val = 0;
                    }

                    var premio = [nombre, cantidad, descripcion, premio_val, probabilidad];

                    premios.push(premio);

                    var tr = jQuery('<tr>').append(
                        jQuery('<td>').text(nombre),
                        jQuery('<td>').text(cantidad),
                        jQuery('<td>').text(descripcion),
                        jQuery('<td>').text(premio_global),
                        jQuery('<td>').text(probabilidad + '%')
                    );

                    tr.appendTo(".table tbody");
                });

                jQuery( "#infoPremio" ).on( "submit", function( event ) {
                    event.preventDefault();
                });

                jQuery('#crearCampanaBtn').on('click', function () {

                    var campana = jQuery('#selectorCampanas').val();
                    var porcentaje = jQuery('#porcentaje_premio').val();

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "crearCampana",
                            premios: premios,
                            campana: campana,
                            porcentaje: porcentaje
                        },
                        success: function (response) {
                            if (response.success) {
                                alert("Campaña creada con exito");
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
        <style>
            .contenedor {
                margin-top: 50px;
            }

            #configuracion-campana {
                text-align: left;
            }

            #premios-campana {
                text-align: right;
            }
        </style>
        <div class="container contenedor">
            <div class="row">
                <div class="col-md-8" id="configuracion-campana">
                    <form>
                        <div class="form-group">
                            <label for="selectorCampanas">Usuarios permitidos en la campaña:</label>
                            <select id="selectorCampanas">
                                <option value="vacio"></option>
                                <option value="todos">Todas las Campañas</option>
                                <option value="pago_anual">Pago Cuota Anual</option>
                                <option value="cuota_comercio10">Cuota Comercio10</option>
                                <option value="campana_san_valentin">Campaña San Valentin</option>
                                <option value="campana_dia_del_padre">Campaña Dia del Padre</option>
                                <option value="campana_primavera">Campaña Primavera</option>
                                <option value="campana_especial">Campaña Especial</option>
                                <option value="campana_dia_de_la_madre">Dia de la madre</option>
                                <option value="campana_verano">Campaña Verano</option>
                                <option value="campana_vuelta_al_cole">Campaña Vuelta al cole</option>
                                <option value="campana_halloween">Campaña Halloween</option>
                                <option value="campana_black_friday">Campaña Black Friday</option>
                                <option value="campana_navidad">Campaña Navidad</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="porcentaje_premio">Porcentaje de premio:</label>
                            <input type="text" name="porcentaje_premio" id="porcentaje_premio"></input>
                        </div>
                    </form>
                </div>
                <div class="col-md-4" id="premios-campana">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Premio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Premio Global</th>
                                <th scope="col">Probabilidad</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success" id="addPremio">Añadir Premio</button>
                </div>
            </div>
            <div class="row justify-content-center">
                <button type="button" id="crearCampanaBtn" class="btn btn-success">Crear Campaña</button>
            </div>
        </div>

        <div class="modal" id="addPremioModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Añadir Premio</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="infoPremio">
                            <label for="nombre">Nombre del Premio:</label>
                            <input type="text" name="nombre" id="nombre"></input>

                            </br></br>

                            <label for="cantidad">Cantidad del Premio:</label>
                            <input type="text" name="cantidad" id="cantidad"></input>

                            </br></br>

                            <label for="descripcion">Descripci�n del Premio:</label>
                            <input type="text" name="descripcion" id="descripcion"></input>

                            </br></br>

                            <label for="global">Premio Global:</label>
                            <input type="checkbox" id="global" name="global" value="true">

                            </br></br>

                            <label for="probabilidad">Probabilidad del Premio:</label>
                            <input type="text" name="probabilidad" id="probabilidad"></input>

                            </br></br>

                            <input type="submit" class="btn btn-primary" id="crearPremioBtn" value="Crear Premio"></input>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }else{
        ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            jQuery(document).ready(function () {

                var premios = [];

                jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "buscarCampana"
                        },
                        success: function (response) {
                            if (response.success) {
                                jQuery('#selectorCampanas').val(response.campana);
                                jQuery('#porcentaje_premio').val(response.porcentaje);
                            }
                        },
                        error: function (error) {
                            // Manejar errores si los hay
                            console.error(error);
                        }
                });

                jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "rellenarTablaPremios"
                        },
                        success: function (response) {
                            if (response.success) {
                                var premios = response.premios;

                                premios.forEach(premio => {
                                    var tr = jQuery('<tr>').append(
                                        jQuery('<td>').text(premio[1]),
                                        jQuery('<td>').text(premio[2]),
                                        jQuery('<td>').text(premio[3]),
                                        jQuery('<td>').text(premio[4]),
                                        jQuery('<td>').text(premio[5] + '%')
                                    );

                                    tr.appendTo(".table tbody");
                                });
                            }
                        },
                        error: function (error) {
                            // Manejar errores si los hay
                            console.error(error);
                        }
                });

                jQuery('#addPremio').on('click', function () {
                    jQuery('#addPremioModal').modal('show');
                });

                jQuery('#crearPremioBtn').on('click', function () {
                    jQuery('#addPremioModal').modal('hide');

                    var nombre = jQuery("#nombre").val();
                    var cantidad = jQuery("#cantidad").val();
                    var descripcion = jQuery("#descripcion").val();
                    var probabilidad = jQuery("#probabilidad").val();
                    if(jQuery("#global").prop('checked')){
                        var premio_global = "Si";
                        var premio_val = 1;
                    }else{
                        var premio_global = "No";
                        var premio_val = 0;
                    }

                    var premio = [nombre, cantidad, descripcion, probabilidad];

                    premios.push(premio);

                    var tr = jQuery('<tr>').append(
                        jQuery('<td>').text(nombre),
                        jQuery('<td>').text(cantidad),
                        jQuery('<td>').text(descripcion),
                        jQuery('<td>').text(premio_global),
                        jQuery('<td>').text(probabilidad + '%')
                    );

                    tr.appendTo(".table tbody");

                });

                jQuery( "#infoPremio" ).on( "submit", function( event ) {
                    event.preventDefault();
                });

                jQuery('#crearCampanaBtn').on('click', function () {

                    var campana = jQuery('#selectorCampanas').val();

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "crearCampana",
                            premios: premios,
                            campana: campana
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

                jQuery('#cancelarCampanaBtn').on('click', function () {

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "cancelarCampana"
                        },
                        success: function (response) {
                            if (response.success) {
                                alert("Campaña Cancelada");
                                location.reload();
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
        <style>
            .contenedor {
                margin-top: 50px;
            }

            #configuracion-campana {
                text-align: left;
            }

            #premios-campana {
                text-align: right;
            }
        </style>
        <div class="container contenedor">
            <div class="row">
                <div class="col-md-8" id="configuracion-campana">
                    <form>
                        <div class="form-group">
                            <label for="selectorCampanas">Usuarios permitidos en la campaña:</label>
                            <select id="selectorCampanas">
                                <option value="vacio"></option>
                                <option value="todos">Todas las Campañas</option>
                                <option value="pago_anual">Pago Cuota Anual</option>
                                <option value="cuota_comercio10">Cuota Comercio10</option>
                                <option value="campana_san_valentin">Campaña San Valentin</option>
                                <option value="campana_dia_del_padre">Campaña Dia del Padre</option>
                                <option value="campana_primavera">Campaña Primavera</option>
                                <option value="campana_especial">Campaña Especial</option>
                                <option value="campana_dia_de_la_madre">Dia de la madre</option>
                                <option value="campana_verano">Campaña Verano</option>
                                <option value="campana_vuelta_al_cole">Campaña Vuelta al cole</option>
                                <option value="campana_halloween">Campaña Halloween</option>
                                <option value="campana_black_friday">Campaña Black Friday</option>
                                <option value="campana_navidad">Campaña Navidad</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="porcentaje_premio">Porcentaje de premio:</label>
                            <input type="text" name="porcentaje_premio" id="porcentaje_premio"></input>
                        </div>
                    </form>
                </div>
                <div class="col-md-4" id="premios-campana">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Premio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Premio Global</th>
                                <th scope="col">Probabilidad</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row justify-content-center">
                <button type="button" id="cancelarCampanaBtn" class="btn btn-success">Cancelar Campaña</button>
            </div>
        </div>

        <div class="modal" id="addPremioModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Añadir Premio</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="infoPremio">
                            <label for="nombre">Nombre del Premio:</label>
                            <input type="text" name="nombre" id="nombre"></input>

                            </br></br>

                            <label for="cantidad">Cantidad del Premio:</label>
                            <input type="text" name="cantidad" id="cantidad"></input>

                            </br></br>

                            <label for="descripcion">Descripci�n del Premio:</label>
                            <input type="text" name="descripcion" id="descripcion"></input>

                            </br></br>

                            <label for="global">Premio Global:</label>
                            <input type="checkbox" id="global" name="global" value="true">

                            </br></br>

                            <label for="probabilidad">Probabilidad del Premio:</label>
                            <input type="text" name="probabilidad" id="probabilidad"></input>

                            </br></br>

                            <input type="submit" class="btn btn-primary" id="crearPremioBtn" value="Crear Premio"></input>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }
}