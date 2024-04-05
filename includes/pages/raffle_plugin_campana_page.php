<?php
function raffle_plugin_campana_page()
{
    ?>
    <!-- Agregar el enlace a Bootstrap JS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Estilos adicionales si los necesitas */
        .contenedor {
            margin-top: 50px;
        }

        #configuracion-campana{
            text-align: left;
        }

        #premios-campana{
            text-align: right;
        }
    </style>
    <div class="container contenedor">
        <div class="row">
            <div class="col-md-8" id="configuracion-campana">
                <!-- Formulario para la configuración de la campaña -->
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre de la campaña:</label>
                        <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre de la campaña">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" rows="3"></textarea>
                    </div>
                    <!-- Puedes agregar más campos según sea necesario -->
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
            <div class="col-md-4" id="premios-campana">
                <!-- Tabla para mostrar los premios -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Premio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí puedes agregar filas según los premios -->
                        <tr>
                            <td>Premio 1</td>
                            <td>10</td>
                            <td>Descripción del premio 1</td>
                            <td>$100</td>
                        </tr>
                        <!-- Puedes agregar más filas según sea necesario -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center">
            <!-- Botón para crear la campaña -->
            <button type="button" class="btn btn-success">Crear Campaña</button>
        </div>
    </div>

    
    <?php
}