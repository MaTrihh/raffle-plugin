<?php
function raffle_plugin_user_prizes_page() {
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        #myModal {
            display: none;
        }

        h2 {
            margin-top: 5px;
        }
    </style>
    <h2>Premios Conseguidos</h2>
    <div class="containerDiv">
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre del Premio</th>
                    <th>Descripcion del Premio</th>
                    <th>Canjeado</th>
                    <th>Fecha Canjeo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $premios = getTodosPremiosCanjeados();

                foreach ($premios as $premio) {
                    $premioDatos = rp_getPremioById($premio->idPremio);
                    ?>
                    <tr>
                        <td>
                            <?php echo get_userdata($premio->user_id)->first_name . ' ' . get_userdata($premio->user_id)->last_name; ?>
                        </td>
                        <td>
                            <?php echo $premioDatos->nombre; ?>
                        </td>

                        <td>
                            <?php echo $premioDatos->descripcion; ?>
                        </td>


                        <td>
                            <?php echo ($premio->canjeado == 0) ? 'Sin canjear' : 'Canjeado'; ?>
                        </td>

                        <td>
                            <?php echo $premio->fecha_canjeado ?>
                        </td>

                        <td>
                            <button type="button">Editar</button>
                            <button type="button">Borrar</button>
                        </td>
                    </tr>
                    <?php
                }

                ?>
            </tbody>
        </table>
    </div>
    <?php
}