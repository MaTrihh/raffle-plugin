<?php

function show_prizes_comercio_shortcode()
{
    $premios = getTodosPremiosConseguidosByComercio(get_current_user_id(  ));
    ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Estilo adicional */
        .table-container {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: #f3f4f6;
            color: #333;
            border-radius: 10px;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #6c757d;
            color: #fff;
            font-weight: bold;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
    </style>
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-container">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Titulo del Premio</th>
                                    <th>Descripcion</th>
                                    <th>Canjeado</th>
                                    <th>Fecha de Canjeo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    foreach($premios as $premio){
                                        $premioData = rp_getPremioById($premio->idPremio);
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $premioData->nombre; ?></td>
                                            <td><?php echo $premioData->descripcion; ?></td>
                                            <td><?php echo $premio->canjeado; ?></td>
                                            <td><?php echo ($premio->fecha_canjeado) == NULL ? 'No canjeado' : $premio->fecha_canjeado ; ?></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                        //<td> echo $premio->estado; </td>
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
}
add_shortcode('show_prizes_comercio', 'show_prizes_comercio_shortcode');