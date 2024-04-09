<?php
function save_prize_shortcode() {
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .div {
            font-family: Arial, sans-serif;
            margin-top: 15%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-canjear {
            margin-left: 3%;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }
    </style>

    <script>
        jQuery(document).ready(function () {
            jQuery(".buscar-ofertas-btn").on("click", function () {

                var dni = jQuery('#dni').val();
                var idAsociado = jQuery('#idAsociado').val();
                var user_id;

                //Peticion AJAX para sacar el id del usuario
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "get_user_by_dni", // Acción que indica qué función de PHP llamar
                        dni: dni
                    },
                    success: function (response) {
                        if (response.success) {
                            user_id = response.id;

                            //Peticion AJAX para sacar los premios del usuario
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "buscar_premios_sorteo", // Acción que indica qué función de PHP llamar
                                    user_id: user_id
                                },
                                success: function (response) {
                                    if (response.success) {

                                        jQuery('#user_id').val(user_id);
                                        var premios = response.premios;
                                        console.log(premios);
                                        jQuery(".modal-body").empty();
                                        jQuery(".modal-header").text("Premios");

                                        // Construir la tabla

                                        var table = '<table class="table table-bordered table-striped">';
                                        table += '<thead class="thead-dark"><tr><th></th><th>Nombre del Premio</th><th>Descripcion</th></tr></thead>';
                                        table += '<tbody>';

                                        premios.forEach(function (premio, index) {
                                            if(idAsociado == premio.idAsociado){

                                                table += '<tr>';
                                                table += '<td><input type="checkbox" name="rp_premios_canjeado[]" value="' + premio.id + '"></input></td>';
                                                table += '<td>' + premio.nombre + '</td>';
                                                table += '<td>' + premio.descripcion + '</td>';
                                                table += '</tr>';
                                            }
                                        });

                                        table += '</tbody></table>';

                                        // Agregar la tabla al contenido de la modal
                                        jQuery(".modal-body").append(table);

                                        jQuery(".modal").modal("show");
                                    } else {
                                        alert("Este usuario no tiene ofertas");
                                    }
                                },
                                error: function (error) {
                                    // Manejar errores si los hay
                                    console.error(error);
                                }
                            });
                        } else {
                            alert("Este usuario no existe");
                        }
                    },
                    error: function (error) {
                        // Manejar errores si los hay
                        console.error(error);
                    }
                });

            });

            // Función que se ejecuta al hacer clic en el botón 'Canjear'
            jQuery('#rp-form').submit(function (event) {

                // Evitar que el formulario se envíe
                event.preventDefault();

                // Crear un array para almacenar los valores de los checkboxes marcados
                var valoresCheckbox = [];
                var user_id = jQuery('#user_id').val();

                // Recorrer los checkboxes marcados y agregar sus valores al array
                jQuery('#rp-form input[type="checkbox"][name="rp_premios_canjeado[]"]:checked').each(function () {
                    valoresCheckbox.push(jQuery(this).val());
                });

                if(valoresCheckbox.length != 0){
                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: "user_premio_sorteo", // Acción que indica qué función de PHP llamar
                            valoresCheckbox: valoresCheckbox,
                            user_id: user_id
                        },
                        success: function (response) {
                            if (response.success) {
                                alert("Premio canjeado con exito!");
                                location.reload();
                            } else {
                                alert("Ha habido algun error, pruebe mas tarde");
                            }
                        },
                        error: function (error) {
                            // Manejar errores si los hay
                            console.error(error);
                        }
                    })


                    // Cierra la ventana modal
                    jQuery('.modal').modal('hide');
                }
            });
        });

    </script>

    <div class="div">
        <form method="POST">
            <h2>Canjear Ofertas</h2>
            <h2>Buscar Usuario:</h2>
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" placeholder="Ingrese un DNI" required>
            <input type="hidden" id="idAsociado" name="idAsociado" value="<?php echo get_current_user_id(); ?>">
            <button class="buscar-ofertas-btn" type="button">Buscar</button>
        </form>
    </div>

    <div class="modal" id="miModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Encabezado del Modal -->
                <div class="modal-header">
                    <h4 class="modal-title" id="nombre"></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <form id="rp-form">
                    <!-- Contenido del Modal -->
                    <div class="modal-body">




                    </div>
                    <input type="hidden" id="user_id" name="user_id"></input>
                    <button type="submit" class="btn btn-primary btn-canjear">Canjear</button>

                </form>
                <!-- Pie del Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrarModalBtn">Cerrar</button>
                </div>


            </div>
        </div>
    </div>
    <?php
}
add_shortcode('save_prize', 'save_prize_shortcode');