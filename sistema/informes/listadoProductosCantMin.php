<?php
$subtotal     = 0;
$iva          = 0;
$impuesto     = 0;
$tl_sniva     = 0;
$total        = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* @import url("fonts/BrixSansRegular.css");
        @import url("fonts/BrixSansBlack.css"); */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        p,
        label,
        span,
        table {
            /* font-family: "BrixSansRegular"; */
            font-size: 9pt;
        }

        .h2 {
            /* font-family: "BrixSansBlack"; */
            font-size: 16pt;
            text-align: center;
        }

        .h3 {
            /* font-family: "BrixSansBlack"; */
            font-size: 12pt;
            display: block;
            background: #ccc;
            color: #000;
            text-align: center;
            padding: 3px;
            margin-bottom: 5px;
        }

        #page_pdf {
            width: 95%;
            margin: 15px auto 10px auto;
        }

        #factura_head,
        #factura_cliente,
        #factura_detalle {
            width: 100%;
            margin-bottom: 10px;
        }

        .logo_factura {
            width: 25%;
        }

        .info_empresa {
            width: 50%;
            text-align: center;
            height: 100px;
        }

        .info_factura {
            width: 25%;
        }

        .info_cliente {
            width: 100%;
        }

        .datos_cliente {
            width: 100%;
        }

        .datos_cliente tr td {
            width: 50%;
        }

        .datos_cliente {
            padding: 10px 10px 0 10px;
        }

        .datos_cliente label {
            width: 75px;
            display: inline-block;
        }

        .datos_cliente p {
            display: inline-block;
        }

        .textright {
            text-align: right;
        }

        .textleft {
            text-align: left;
        }

        .textcenter {
            text-align: center;
        }

        .round {
            border-radius: 10px;
            border: 1px solid #ececec;
            overflow: hidden;
            padding-bottom: 15px;
        }

        .round p {
            padding: 0 15px;
        }

        #factura_detalle {
            border-collapse: collapse;
        }

        #factura_detalle thead th {
            background: #ccc;
            color: #000;
            padding: 5px;
        }

        #detalle_productos tr:nth-child(even) {
            background: #ededed;
        }

        #detalle_totales span {
            font-family: "BrixSansBlack";
        }

        .nota {
            font-size: 8pt;
        }

        .label_gracias {
            font-family: verdana;
            font-weight: bold;
            font-style: italic;
            text-align: center;
            margin-top: 20px;
        }

        .anulada {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
        }
    </style>
</head>

<body>
    <div id="page_pdf">
        <h3 style="text-align: center;">ELECTROPAMA</h3>
        <h5 style="text-align: center;">LISTADO DE PRODUCTOS CON EXISTENCIA MINIMA</h5>
        <table id="factura_detalle">
            <thead>
                <tr>
                    <th class="textleft">REFERENCIA</th>
                    <th class="textleft">NOMBRE PRODUCTO</th>
                    <th class="textleft">CANT. MIN</th>
                    <th class="textleft">EXISTENCIA</th>

                </tr>
            </thead>
            <tbody id="detalle_productos">

                <?php
                if ($result > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                      ?>  
                            <tr>
                                
                                <td class="textleft"><?php echo $row['referencia']; ?></td>
                                <td class="textleft"><?php echo $row['nombre']; ?></td>
                                <td class="textleft"><?php echo $row['cantMin']; ?></td>
                                <td class="textleft"><?php echo $row['existencia']; ?></td>

                            </tr>

                    <?php
                        }     
                    } 
                    ?>
            </tbody>
        </table>
    </div>
</body>

</html>