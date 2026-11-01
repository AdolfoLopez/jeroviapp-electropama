<?php
$subtotal     = 0;
$iva          = 0;
$impuesto     = 0;
$tl_sniva   = 0;
$total         = 0;
//print_r($configuracion); 
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

        /* #detalle_productos tr:nth-child(even) {
            background: #ededed;
        } */

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
    <!-- <?php echo $anulada; ?> -->
    <div id="page_pdf">
        <h3 style="text-align: center;">EPM ELECTROPAMA</h3>
        <h5 style="text-align: center;">ESTADO DE CUENTA DEL CLIENTE</h5>
        <table id="factura_head">

            <tr>
                <!-- <td class="logo_factura">
                    <div> -->
                <!-- <img src="img/logo.png"> -->
                <!-- <span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span> -->
                <!-- </div>
                </td> -->
                <!-- <td class="info_empresa">
                    <?php
                    if ($result_config > 0) {
                        $iva = $configuracion['iva'];
                    ?>
                        <div>
                            <span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
                            <p><?php echo $configuracion['razon_social']; ?></p>
                            <p><?php echo $configuracion['direccion']; ?></p>
                            <p>NIT: <?php echo $configuracion['ci_ruc']; ?></p>
                            <p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
                            <p>Email: <?php echo $configuracion['email']; ?></p>
                        </div>
                    <?php
                    }
                    ?>
                </td> -->
                <!-- <td class="info_factura">
                    <div class="round">
                        <span class="h3">Factura</span>
                        <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p>
                        <p>Fecha: <?php echo $factura['fecha']; ?></p>
                        <p>Hora: <?php echo $factura['hora']; ?></p>
                        <p>Vendedor: <?php echo $factura['vendedor']; ?></p>
                    </div>
                </td> -->
            </tr>
        </table>
        <table id="factura_cliente">
            <tr>
                <td class="info_cliente">
                    <div class="round">
                        <span class="h3">Datos del cliente</span>
                        <table class="datos_cliente">
                            <tr>
                                <td><label>NOMBRE:</label>
                                    <p><?php echo $cuenta['nombre']; ?></p>
                                </td>

                                <td><label>TELÉFONO:</label>
                                    <p><?php echo $cuenta['telefono1']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><label>C.I/RUC:</label>
                                    <p><?php echo $cuenta['ci_ruc']; ?></p>
                                </td>

                                <td><label>DIRECCIÓN:</label>
                                    <p><?php echo $cuenta['direccion']; ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>


            </tr>
            <tr>
                <td class="info_cliente">
                    <div class="round">
                        <span class="h3">En concepto de</span>
                        <table class="datos_cliente">
                            <tr>
                                <td class="textleft">No. Venta</td>
                                <td class="textleft">No. Factura</td>
                                <td class="textleft">Nombre del Producto</td>
                                <td class="textleft">Cant.</td>
                                <td class="textleft">Precio Venta</td>
                                <td class="textleft">Sub-Total</td>
                                <td class="textleft">Obra</td>
                            </tr>
                            <?php

                            if ($result_producto > 0) {

                                while ($row_producto = mysqli_fetch_assoc($query_productos)) {

                            ?>
                                    <tr>
                                        <td class="textleft"><?php echo $row_producto['idVenta']; ?></td>
                                        <td class="textleft"><?php echo $row_producto['noFacturaLegal']; ?></td>
                                        <td width="150"><?php echo $row_producto['nombre']; ?></td>
                                        <td width="150"><?php echo $row_producto['cantidadVendida']; ?></td>
                                        <td class="textleft"><?php echo $row_producto['precioVendido']; ?></td>
                                        <td class="textleft"><?php echo $row_producto['precio_total']; ?></td>
                                        <td class="textleft"><?php echo $row_producto['nombreObra']; ?></td>
                                    </tr>
                            <?php

                                }
                            }
                            ?>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <table id="factura_detalle">
            <thead>
                <tr>
                    <th class="textleft">No. Venta</th>
                    <th class="textleft">Vencimiento</th>
                    <th class="textleft">Último cobro</th>
                    <th class="textright">Atraso</th>
                    <th class="textright">Cuotas</th>
                    <th class="textright">Monto Cuotas</th>
                    <th class="textright">Intereses Moratorio</th>
                    <th class="textright">Pagado</th>
                    <th class="textright">Saldo</th>
                </tr>
            </thead>
            <tbody id="detalle_productos">

                <?php
                if ($result_detalle > 0) {
                    $numFactura = 0;
                    while ($row = mysqli_fetch_assoc($query_cuotas)) {
                        if ($numFactura != $row['idVenta']) {
                            $numFactura = $row['idVenta'];

                            $resultado = $row['diasAtrasados'];

                            if ($resultado > 0) {
                                $resultado = $resultado;
                            } else {
                                $resultado = 0;
                            }

                            if ($row['status'] == 1 && $row['diasAtrasadosActual'] >= 0) {
                                $resultado = $row['diasAtrasadosActual'];
                                $saldo = ($row['monto'] + $row['interesMoratorioActual']) - $row['montoRecibido'];
                                $interesMoratorio = $row['interesMoratorioActual'];
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0 && $row['montoRecibido'] == 0) {
                                $saldo = $row['monto'] - $row['saldo'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0) {
                                $saldo = $row['monto'] - $row['montoRecibido'];
                            }
                            if ($row['status'] == 2 && $resultado >= 0) {
                                $saldo = $row['saldo'];
                                $interesMoratorio = $row['interesMoratorio'];
                            }
                ?>
                            <tr>
                                <td class="textleft" style="font-size: 14px; font-weight: 300;"><?php echo $row['idVenta']; ?></td>
                                <!-- <td class="textleft"></td>
                                <td class="textleft"></td>
                                <td class="textright"></td>
                                <td class="textright"></td>
                                <td class="textright"></td>
                                <td class="textright"></td>
                                <td class="textright"></td>
                                <td class="textright"></td> -->
                            </tr>

                            <tr>
                                <td class="textleft"></td>
                                <td class="textleft"><?php echo $row['fechaVencimiento']; ?></td>
                                <td class="textleft"><?php echo $row['fechaPago']; ?></td>
                                <td class="textright"><?php echo $resultado = $resultado > 0 ? $resultado : '0'; ?></td>
                                <td class="textright"><?php echo $row['noCuota'] . '/' . $row['cantCuota']; ?></td>
                                <td class="textright"><?php echo number_format(($row['monto']), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($interesMoratorio = $interesMoratorio > 0 ?  $interesMoratorio : '0'), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($row['montoRecibido']), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($saldo  = $saldo >= 0 ? $saldo  : ''), 0, ",", ".") . ' Gs.';  ?></td>

                            </tr>

                        <?php
                            $totalSaldo = $totalSaldo + $saldo;
                            $totalCuotas = $totalCuotas + $row['monto'];
                            $totalPagado = $totalPagado + $row['montoRecibido'];
                        } else {
                            $resultado = $row['diasAtrasados'];

                            if ($resultado > 0) {
                                $resultado = $resultado;
                            } else {
                                $resultado = 0;
                            }

                            if ($row['status'] == 1 && $row['diasAtrasadosActual'] >= 0) {
                                $resultado = $row['diasAtrasadosActual'];
                                $saldo = ($row['monto'] + $row['interesMoratorioActual']) - $row['montoRecibido'];
                                $interesMoratorio = $row['interesMoratorioActual'];
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0 && $row['montoRecibido'] == 0) {
                                $saldo = $row['monto'] - $row['saldo'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0) {
                                $saldo = $row['monto'] - $row['montoRecibido'];
                            }
                            if ($row['status'] == 2 && $resultado >= 0) {
                                $saldo = $row['saldo'];
                                $interesMoratorio = $row['interesMoratorio'];
                            }
                        ?>
                            <tr>
                                <td class="textleft"></td>
                                <td class="textleft"><?php echo $row['fechaVencimiento']; ?></td>
                                <td class="textleft"><?php echo $row['fechaPago']; ?></td>
                                <td class="textright"><?php echo $resultado = $resultado > 0 ? $resultado : '0'; ?></td>
                                <td class="textright"><?php echo $row['noCuota'] . '/' . $row['cantCuota']; ?></td>
                                <td class="textright"><?php echo number_format(($row['monto']), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($interesMoratorio = $interesMoratorio > 0 ?  $interesMoratorio : '0'), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($row['montoRecibido']), 0, ",", ".") . ' Gs.'; ?></td>
                                <td class="textright"><?php echo number_format(($saldo  = $saldo >= 0 ? $saldo  : ''), 0, ",", ".") . ' Gs.';  ?></td>

                            </tr>

                <?php
                            $totalSaldo = $totalSaldo + $saldo;
                            $totalCuotas = $totalCuotas + $row['monto'];
                            $totalPagado = $totalPagado + $row['montoRecibido'];
                            // $subtotal = round($subtotal + $precio_total, 2);
                        }
                    }
                }

                // $impuesto     = round($subtotal * ($iva / 100), 2);
                // $tl_sniva     = round($subtotal - $impuesto, 2);
                // $total         = round($tl_sniva + $impuesto, 2);
                $resultado = 0;
                $interesMoratorio = 0;
                $saldo = 0;
                ?>
            </tbody>
            <tfoot id="detalle_totales" style="border-top: 1px solid black;">
                <tr>
                    <td></td>
                    <td></td>

                    <td></td>

                    <td></td>
                    <td></td>

                    <td class="textright"><?php echo number_format(($totalCuotas), 0, ",", ".") . ' Gs.'; ?></td>
                    <td></td>
                    <td class="textright"><?php echo number_format(($totalPagado), 0, ",", ".") . ' Gs.'; ?></td>
                    <td class="textright"><?php echo  number_format(($totalSaldo), 0, ",", ".") . ' Gs.';  ?></td>
                </tr>
                <!-- <tr>
                    <td class="textright"><span>MONTO TOTAL</span></td>
                    <td class="textright"><span><?php echo $totalCuotas . ' Gs.'; ?></span></td>
                </tr>
                <tr>
                    <td class="textright"><span>SALDO</span></td>
                    <td class="textright"><span><?php echo $totalSaldo . ' Gs.'; ?></span></td>
                </tr> -->
                <!-- <tr>
                    <td colspan="3" class="textright"><span>TOTAL Q.</span></td>
                    <td class="textright"><span><?php echo $total; ?></span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>TOTAL CUOTAS</span></td>
                    <td class="textright"><span><?php echo $totalCuotas; ?></span></td>
                </tr> -->
            </tfoot>
        </table>
        <!-- <div> -->
        <!-- <p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p> -->
        <!-- <h4 class="label_gracias">¡Gracias por su compra!</h4> -->
        <!-- </div> -->

    </div>

</body>

</html>