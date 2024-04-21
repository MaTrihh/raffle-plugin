<?php
function raffle_plugin_campana_page()
{
    if (!existeCampana()) {

        ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            jQuery(document).ready(function () {

                var premios = [];
                var premios_config = [];

                jQuery('#selectorCampanas').change(function() {
                    // Obtener el valor seleccionado del select
                    var valorSeleccionado = jQuery(this).val();

                    if (valorSeleccionado === 'vacio') {
                        // Si el valor seleccionado es vacío
                        jQuery('#addPremio').prop('disabled', true);
                    } else {
                        // Si el valor seleccionado no es vacío
                        jQuery('#addPremio').prop('disabled', false);
                    }
                });

                jQuery('#addPremioModal').on('hidden.bs.modal', function (e) {
                    jQuery("#tbody").empty();
                    jQuery('.loader').show();
                    jQuery('.comerciosInformacion').css('display', 'flex');
                    jQuery('.info').hide();
                });

                // Delegación de eventos para el cambio de estado de los checkboxes
                jQuery(document).on('change', '.checkPremio', function () {
                    // Si el checkbox está marcado
                    if (jQuery(this).prop('checked')) {
                        // Habilitar el botón
                        let id = 'text' + jQuery(this).val();
                        jQuery('#' + id).prop('disabled', true);
                        jQuery('#' + id).prop('placeholder', '');
                    } else {
                        // Deshabilitar el botón
                        let id = 'text' + jQuery(this).val();
                        jQuery('#' + id).prop('disabled', false);
                        jQuery('#' + id).prop('placeholder', 'Introducir Cantidad');
                    }
                });

                jQuery('#global').change(function () {
                    // Si el checkbox está marcado
                    if (jQuery(this).prop('checked')) {
                        // Habilitar el botón
                        jQuery('#editarComercios').prop('disabled', false);
                        jQuery('#normal').prop('checked', false);
                    } else {
                        // Deshabilitar el botón
                        jQuery('#editarComercios').prop('disabled', true);
                        jQuery('#normal').prop('checked', true);
                    }
                });

                jQuery('#normal').change(function () {
                    // Si el checkbox está marcado
                    if (jQuery(this).prop('checked')) {
                        // Habilitar el botón
                        jQuery('#editarComercios').prop('disabled', true);
                        jQuery('#global').prop('checked', false);
                    } else {
                        // Deshabilitar el botón
                        jQuery('#editarComercios').prop('disabled', false);
                        jQuery('#global').prop('checked', true);
                    }
                });

                jQuery('.checkPremio').change(function () {
                    // Si el checkbox está marcado
                    if (jQuery(this).prop('checked')) {
                        // Habilitar el botón
                        let id = 'text' + jQuery(this).val();
                        jQuery('#' + id).prop('disabled', true);
                        jQuery('#' + id).prop('placeholder', '');
                    } else {
                        // Deshabilitar el botón
                        let id = 'text' + jQuery(this).val();
                        jQuery('#' + id).prop('disabled', false);
                        jQuery('#' + id).prop('placeholder', 'Introducir Cantidad');
                    }
                });

                jQuery('#addPremio').on('click', function () {
                    jQuery('#addPremioModal').modal('show');
                });

                jQuery('#editarComercios').on('click', function () {
                    jQuery('#addPremioModal').modal('hide');
                    jQuery('#editPremioModal').modal('show');

                    var campana = jQuery('#selectorCampanas').val();

                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "getComercios",
                            campana: campana
                        },
                        success: function (response) {
                            let comercios = response.comercios;

                            jQuery.each(comercios, function (index, comercio) {
                                let tr = `<tr>
                                                            <td>
                                                                <span class="nombre-comercio">${comercio.nombre}</span>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" id="checkbox${comercio.ID}" class="checkPremio" name="checkbox${comercio.ID}" value="${comercio.ID}"></input>
                                                            </td>
                                                            <td>
                                                                <input type="text" id="text${comercio.ID}" name="text${comercio.ID}" placeholder="Introducir Cantidad"></input>
                                                            </td>
                                                        </tr>`;


                                jQuery("#tbody").append(tr);
                                
                                jQuery('.loader').hide();
                                jQuery('.comerciosInformacion').css('display', 'block');
                                jQuery('.info').show();
                            });
                        },
                        error: function (error) {
                            // Manejar errores si los hay
                            console.error(error);
                        }
                    });
                });

                jQuery('#guardarEdicionBt').on('click', function () {
                    var checkboxesUnchecked = jQuery('#editPremioConfig input[type="checkbox"]:not(:checked)');
                    var inputsTextEnabled = jQuery('#editPremioConfig input[type="text"]:not([disabled])');
                    var premio_config = [];

                    checkboxesUnchecked.each(function() {
                        var cantidad = jQuery('#text'+jQuery(this).val()).val();
                        if(jQuery('#text'+jQuery(this).val()).val() == ''){
                            var cantidad = 0;
                        }

                        var config = {
                            comercioID: jQuery(this).val(),
                            cantidad: cantidad

                        };
                        premio_config.push(config);
                    });

                    premios_config.push(premio_config);

                    jQuery('#addPremioModal').modal('show');
                    jQuery('#editPremioModal').modal('hide');
                    
                });


                jQuery('#crearPremioBtn').on('click', function () {
                    jQuery('#addPremioModal').modal('hide');

                    var nombre = jQuery("#nombre").val();
                    var cantidad = jQuery("#cantidad").val();
                    var descripcion = jQuery("#descripcion").val();
                    var foto = jQuery('#archivos').val();
                    var probabilidad = jQuery("#probabilidad").val();
                    if (jQuery("#global").prop('checked')) {
                        var premio_global = "Si";
                        var premio_val = 1;
                    } else {
                        var premio_global = "No";
                        var premio_val = 0;
                    }

                    var premio = [nombre, cantidad, descripcion, foto, premio_val, probabilidad];

                    premios.push(premio);

                    var tr = jQuery('<tr>').append(
                        jQuery('<td>').text(nombre),
                        jQuery('<td>').text(cantidad),
                        jQuery('<td>').text(descripcion),
                        jQuery('<td>').text(foto),
                        jQuery('<td>').text(premio_global),
                        jQuery('<td>').text(probabilidad + '%')
                    );

                    tr.appendTo(".table tbody");
                });

                jQuery("#infoPremio").on("submit", function (event) { 
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
                            porcentaje: porcentaje,
                            premios_config: premios_config
                        },
                        success: function (response) {
                            if (response.success) {
                                alert("Campaña creada con exito");
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

            #global {
                display: inline-block;
                margin-right: 10px;
            }

            #editarComercios {
                display: inline-block;
            }

            .modal-body-scrollable {
                max-height: 500px;
                /* Altura máxima del contenedor */
                overflow-y: auto;
                /* Habilita el scroll vertical cuando el contenido es más grande que el contenedor */
            }

            .nombre-comercio {
                font-family: Arial, sans-serif;
                /* Utiliza una fuente sans-serif profesional */
                font-size: 18px;
                /* Tamaño de fuente legible */
                font-weight: bold;
                /* Texto en negrita para destacar */
                color: #333;
                /* Color de texto oscuro para mejor legibilidad */
                text-transform: capitalize;
                /* Capitaliza el texto para una apariencia más pulida */
                letter-spacing: 0.5px;
                /* Espaciado entre letras para mejorar la legibilidad */
                /* Opcional: Añade decoración de texto */
                /* text-decoration: underline; */
            }

            .newtons-cradle {
                --uib-size: 50px;
                --uib-speed: 1.2s;
                --uib-color: #474554;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                width: var(--uib-size);
                height: var(--uib-size);
            }

            .newtons-cradle__dot {
                position: relative;
                display: flex;
                align-items: center;
                height: 100%;
                width: 25%;
                transform-origin: center top;
            }

            .newtons-cradle__dot::after {
                content: '';
                display: block;
                width: 100%;
                height: 25%;
                border-radius: 50%;
                background-color: var(--uib-color);
            }

            .newtons-cradle__dot:first-child {
                animation: swing var(--uib-speed) linear infinite;
            }

            .newtons-cradle__dot:last-child {
                animation: swing2 var(--uib-speed) linear infinite;
            }

            @keyframes swing {
                0% {
                    transform: rotate(0deg);
                    animation-timing-function: ease-out;
                }

                25% {
                    transform: rotate(70deg);
                    animation-timing-function: ease-in;
                }

                50% {
                    transform: rotate(0deg);
                    animation-timing-function: linear;
                }
            }

            @keyframes swing2 {
                0% {
                    transform: rotate(0deg);
                    animation-timing-function: linear;
                }

                50% {
                    transform: rotate(0deg);
                    animation-timing-function: ease-out;
                }

                75% {
                    transform: rotate(-70deg);
                    animation-timing-function: ease-in;
                }
            }

            .info {
                display: none;
            }

            .comerciosInformacion {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
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
                                <th scope="col">foto</th>
                                <th scope="col">Premio Global</th>
                                <th scope="col">Probabilidad</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success" id="addPremio" disabled>Añadir Premio</button>
                </div>
            </div>
            <div class="row justify-content-center">
                <button type="button" id="crearCampanaBtn" class="btn btn-success">Crear Campaña</button>
            </div>
        </div>

        <div class="modal" id="editPremioModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Premio</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body modal-body-scrollable">

                        <div class="comerciosInformacion">

                            <div class="loader">
                                <div class="newtons-cradle">
                                    <div class="newtons-cradle__dot"></div>
                                    <div class="newtons-cradle__dot"></div>
                                    <div class="newtons-cradle__dot"></div>
                                    <div class="newtons-cradle__dot"></div>
                                </div>
                            </div>

                            <div class="info">
                                <form id="editPremioConfig">
                                    <div class="container">
                                        <table class="table">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">Comercio</th>
                                                    <th scope="col">Sin Premio</th>
                                                    <th scope="col">Cantidad de Premios</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">

                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="guardarEdicionBt">Guardar</button>
                    </div>

                </div>
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

                            <?php echo generarSelectArchivosJPG(); ?>

                            </br></br>

                            <div>
                                <label for="global">Premio Global:</label>
                                <input type="checkbox" id="global" name="global">
                                <button id="editarComercios" class="btn btn-primary" disabled>Editar Comercios</button>
                            </div>


                            </br>

                            <label for="normal">Premio Normal:</label>
                            <input type="checkbox" id="normal" name="normal" checked>

                            </br></br>

                            <label for="probabilidad">Probabilidad del Premio:</label>
                            <input type="text" name="probabilidad" id="probabilidad"></input>

                            </br></br>

                            <input type="submit" class="btn btn-primary" id="crearPremioBtn" value="Crear Premio"></input>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarPremioBt" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
        <?php
    } else {
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

                jQuery('#global').change(function () {
                    // Si el checkbox está marcado
                    if (jQuery(this).prop('checked')) {
                        // Habilitar el botón
                        jQuery('#editarComercios').prop('disabled', false);
                    } else {
                        // Deshabilitar el botón
                        jQuery('#editarComercios').prop('disabled', true);
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
                    if (jQuery("#global").prop('checked')) {
                        var premio_global = "Si";
                        var premio_val = 1;
                    } else {
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

                jQuery("#infoPremio").on("submit", function (event) {
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

            #global {
                display: inline-block;
                margin-right: 10px;
            }

            #editarComercios {
                display: inline-block;
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

                            <div>
                                <label for="global">Premio Global:</label>
                                <input type="checkbox" id="global" name="global">
                                <button id="editarComercios" class="btn btn-primary" disabled>Editar Comercios</button>
                            </div>


                            </br>

                            <label for="normal">Premio Normal:</label>
                            <input type="checkbox" id="normal" name="normal" checked>

                            </br></br>

                            <label for="probabilidad">Probabilidad del Premio:</label>
                            <input type="text" name="probabilidad" id="probabilidad"></input>

                            </br></br>

                            <input type="submit" class="btn btn-primary" id="crearPremioBtn" value="Crear Premio"></input>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cerrarPremioBtn" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
}