<?php
$totalVenta     = 0;
$totalCompra    = 0;
$totalCant      = 0;
$totalGral      = 0;

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
        @import url(../fonts/Montserrat-Regular.css);
        @import url(../fonts/Montserrat-Light.css);

        @font-face {
            font-family: "MontserratLight";
            font-style: normal;
            font-weight: normal;
            /* src: url(https://miservidor.com/fonts/mifuente.ttf) format("truetype"); */
            src: url(../fonts/Montserrat-Light.ttf) format("truetype");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "MontserratLight";
        }

        p,
        label,
        span,
        table {
            font-family: "MontserratLight";
            font-size: 8pt;
        }

        .h2 {
            font-family: "MontserratLight" !important;
            font-size: 12pt;
            text-align: center;
        }

        .h3 {
            font-family: "MontserratLight" !important;
            font-size: 10pt;
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
            font-family: Arial;
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
            /* background: #ccc; */
            background: #fff;
            color: #000;
            padding: 5px;
            border-top: 1px solid #ececec;
            border-bottom: 1px solid #ececec;
        }

        #detalle_productos tr:nth-child(even) {
            /* background: #ededed; */
            background: rgb(251, 251, 251);

        }

        #detalle_totales span {
            font-family: "MontserratRegular";
        }

        .nota {
            font-size: 8pt;
        }

        .label_gracias {
            font-family: "Montserrat";
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
        <h3 style="text-align: center;"><?php echo strtoupper($configuracion['nombre']); ?></h3>
        <h5 style="text-align: center;">INFORMES VENTA/OBRA</h5>
        <table id="factura_detalle">
            <thead>
                <tr>
                    <th style="width:23px" class="textleft">OBRA</th>
                    <th class="textcenter">No. VENTA</th>
                    <th class="textleft">FECHA VENTA</th>
                    <th class="textcenter">REFERENCIA</th>
                    <th style="width:200px" class="textleft">NOMBRE PRODUCTO</th>
                    <th class="textright">CANT.</th>
                    <th class="textright">PRECIO VENTA</th>
                    <th class="textright">SUB-TOTAL</th>
                    <th class="textright">PAGADO</th>
                    <th class="textright">FECHA PAGO</th>
                </tr>
            </thead>
            <tbody id="detalle_productos">
                <?php
                $aux                = '';
                $subTotal           = 0;
                $subTotalCant       = 0;
                $subTotalCompra     = 0;
                $subTotalGanancia   = 0;
                $total              = 0;
                $totalCompra        = 0;
                $totalVenta         = 0;
                $totalCant          = 0;
                $aux2               = $row['nombreObra'];
                if ($result > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        if ($row['pagado'] == 2) {
                            $pagado = 'SI';
                        } else {
                            $pagado = 'NO';
                        }
                        $total          = $total + $row['subTotal'];
                        $totalVenta     = $totalVenta + $row['subTotal'];
                        $totalCant      = $totalCant + $row['cant'];
                        $aux2           =  $row['nombreObra'];
                        if ($aux ==  $aux2) {
                            $subTotal           = $subTotal + $row['subTotal'];
                            $subTotalCant       = $subTotalCant + $row['cant'];
                ?>
                            <tr>
                                <td style="width:80px" class="textleft"><?php echo '' ?></td>
                                <td class="textcenter"><?php echo $row['idVenta'] ?></td>
                                <td class="textleft"><?php echo $row['fecha']; ?></td>
                                <td class="textcenter"><?php echo $row['referencia']; ?></td>
                                <td style="width:80px"><?php echo $row['nombreProducto']; ?></td>
                                <td style="text-align: right;"><?php echo $row['cant']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['precioVenta'], 0, ",", " "); ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['subTotal'], 0, ",", " "); ?></td>
                                <td class="textcenter"><?php echo $pagado ?></td>
                                <td style="text-align: right;"><?php echo $row['fechaPago']; ?></td>
                            </tr>
                        <?php
                        } else if ($subTotal > 0) {
                        ?>
                            <tr>
                                <td style="width:80px"><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td style="width:80px"><?php echo ''; ?></td>
                                <td style="font:700; text-align: right; border-top: 1px solid #000; margin-bottom: 5px;"><?php echo $subTotalCant; ?></td>
                                <td><?php echo ''; ?></td>
                                <td style="font:700; text-align: right; border-top: 1px solid #000; margin-bottom: 5px;"><?php echo number_format($subTotal, 0, ",", "."); ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                            </tr>
                            <?php
                            $subTotal           = 0;
                            $subTotalCant       = 0;
                            $subTotalCompra     = 0;
                            $subTotalGanancia   = 0;
                            ?>
                            <tr>
                                <td style="width:80px"><?php echo $row['nombreObra']; ?></td>
                                <td class="textcenter"><?php echo ''; ?></td>
                                <td class="textleft"><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td style="width:80px"><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                            </tr>
                            <tr>
                                <td style="width:80px" class="textleft"><?php echo '' ?></td>
                                <td class="textcenter"><?php echo $row['idVenta']; ?></td>
                                <td class="textleft"><?php echo $row['fecha']; ?></td>
                                <td class="textcenter"><?php echo $row['referencia']; ?></td>
                                <td style="width:80px"><?php echo $row['nombreProducto']; ?></td>
                                <td style="text-align: right;"><?php echo $row['cant']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['precioVenta'], 0, ",", " "); ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['subTotal'], 0, ",", " "); ?></td>
                                <td class="textcenter"><?php echo $pagado ?></td>
                                <td style="text-align: right;"><?php echo $row['fechaPago']; ?></td>
                            </tr>
                        <?php
                            $aux = $row['nombreObra'];
                            $subTotal = $subTotal + $row['subTotal'];
                            $subTotalCant   = $subTotalCant + $row['cant'];
                            // $subTotalCompra = $subTotalCompra + $row['precioCompra'];
                            // $subTotalGanancia   = $subTotalGanancia + $row['ganancias'];
                        } else {

                        ?>
                            <tr>
                                <td style="width:80px"><?php echo $row['nombreObra']; ?></td>
                                <td class="textcenter"><?php echo ''; ?></td>
                                <td class="textleft"><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td style="width:80px"><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                                <td><?php echo ''; ?></td>
                            </tr>
                            <tr>
                                <td style="width:80px"><?php echo ''; ?></td>
                                <td class="textcenter"><?php echo $row['idVenta']; ?></td>
                                <td class="textleft"><?php echo $row['fecha']; ?></td>
                                <td class="textcenter"><?php echo $row['referencia']; ?></td>
                                <td style="width:80px"><?php echo $row['nombreProducto']; ?></td>
                                <td style="text-align: right;"><?php echo $row['cant']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['precioVenta'], 0, ",", " "); ?></td>
                                <td style="text-align: right;"><?php echo number_format($row['subTotal'], 0, ",", " "); ?></td>
                                <td class="textcenter"><?php echo $pagado ?></td>
                                <td style="text-align: right;"><?php echo $row['fechaPago']; ?></td>
                            </tr>
                        <?php
                            $aux = $row['nombreObra'];
                            $subTotal = $subTotal + $row['subTotal'];
                            $subTotalCant   = $subTotalCant + $row['cant'];
                        }
                        ?>
                <?php
                    }
                }
                ?>
                <tr>
                    <td style="width:80px"><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                    <td style="width:80px"><?php echo ''; ?></td>
                    <td style="font:700; text-align: right; border-top: 1px solid #000; margin-bottom: 5px;"><?php echo $subTotalCant; ?></td>
                    <td><?php echo ''; ?></td>
                    <td style="font:700; text-align: right; border-top: 1px solid #000; margin-bottom: 5px;"><?php echo number_format($subTotal, 0, ",", "."); ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo ''; ?></td>
                </tr>
                <?php
                //}
                ?>
            </tbody>
            <tfoot id="detalle_totales" style="border-top: 1px solid #ececec; font:700;">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="textcenter"><?php echo 'TOTAL:  '; ?></td>
                    <td style="text-align: right;"><?php echo $totalCant; ?></td>
                    <td></td>
                    <td style="text-align: right;"><?php echo number_format(($total), 0, ",", " "); ?></td>
                    <td></td>
                    <td></td>

                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>