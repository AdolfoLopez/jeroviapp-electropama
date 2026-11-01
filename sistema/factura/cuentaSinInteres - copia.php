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
    <title>Estado de Cuenta de Ciente sin Interes</title>
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
        <h5 style="text-align: center;">ESTADO DE CUENTA DE CLIENTE SIN INTERES</h5>
        <table id="factura_head">
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
                                <td class="textleft">Referencia</td>
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
                                        <td class="textleft"><?php echo $row_producto['referencia']; ?></td>
                                        <td><?php echo $row_producto['nombre']; ?></td>
                                        <td><?php echo $row_producto['cantidadVendida']; ?></td>
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
                    <th class="textright">Cuotas</th>
                    <th class="textright">Monto Cuotas</th>
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
                            $resultado = 0;
                            if ($resultado > 0) {
                                $resultado = $resultado;
                            } else {
                                $resultado = 0;
                            }
                            if ($row['status'] == 1 && $row['diasAtrasadosActual'] >= 0) {
                                // $resultado = $row['diasAtrasadosActual'];
                                $resultado = 0;
                                // $saldo = ($row['monto'] + $row['interesMoratorioActual']) - $row['montoRecibido'];
                                $saldo = ($row['monto'] + 0) - $row['montoRecibido'];
                                // $interesMoratorio = $row['interesMoratorioActual'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0 && $row['montoRecibido'] == 0) {
                                $saldo = $row['monto'] - $row['saldo'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0) {
                                $saldo = $row['monto'] - $row['montoRecibido'];
                            }
                            if ($row['status'] == 2 && $resultado >= 0) {
                                $saldo = $row['saldo'];
                                // $interesMoratorio = $row['interesMoratorio'];
                                $interesMoratorio = 0;
                            }
                ?>
                            <tr>
                                <td class="textleft" style="font-size: 14px; font-weight: 300;"><?php echo $row['idVenta']; ?></td>
                            </tr>

                            <tr>
                                <td class="textleft"></td>
                                <td class="textleft"><?php echo $row['fechaVencimiento']; ?></td>
                                <td class="textleft"><?php echo $row['fechaPago']; ?></td>
                                <!-- <td class="textright"><?php echo $resultado = $resultado > 0 ? $resultado : '0'; ?></td> -->
                                <td class="textright"><?php echo $row['noCuota'] . '/' . $row['cantCuota']; ?></td>
                                <td class="textright"><?php echo number_format(($row['monto']), 0, ",", ".") . ' Gs.'; ?></td>
                                <!-- <td class="textright"><?php echo number_format(($interesMoratorio = $interesMoratorio > 0 ?  $interesMoratorio : '0'), 0, ",", ".") . ' Gs.'; ?></td> -->
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
                                // $saldo = ($row['monto'] + $row['interesMoratorioActual']) - $row['montoRecibido'];
                                $saldo = ($row['monto'] + 0) - $row['montoRecibido'];
                                // $interesMoratorio = $row['interesMoratorioActual'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0 && $row['montoRecibido'] == 0) {
                                $saldo = $row['monto'] - $row['saldo'];
                                $interesMoratorio = 0;
                            } elseif ($row['status'] == 1 && $row['diasAtrasadosActual'] < 0) {
                                $saldo = $row['monto'] - $row['montoRecibido'];
                            }
                            if ($row['status'] == 2 && $resultado >= 0) {
                                $saldo = $row['saldo'];
                                // $interesMoratorio = $row['interesMoratorio'];
                                $interesMoratorio = 0;
                            }
                        ?>
                            <tr>
                                <td class="textleft"></td>
                                <td class="textleft"><?php echo $row['fechaVencimiento']; ?></td>
                                <td class="textleft"><?php echo $row['fechaPago']; ?></td>
                                <!-- <td class="textright"><?php echo $resultado = $resultado > 0 ? $resultado : '0'; ?></td> -->
                                <td class="textright"><?php echo $row['noCuota'] . '/' . $row['cantCuota']; ?></td>
                                <td class="textright"><?php echo number_format(($row['monto']), 0, ",", ".") . ' Gs.'; ?></td>
                                <!-- <td class="textright"><?php echo number_format(($interesMoratorio = $interesMoratorio > 0 ?  $interesMoratorio : '0'), 0, ",", ".") . ' Gs.'; ?></td> -->
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
                    <td class="textright"><?php echo number_format(($totalCuotas), 0, ",", ".") . ' Gs.'; ?></td>
                    <td class="textright"><?php echo number_format(($totalPagado), 0, ",", ".") . ' Gs.'; ?></td>
                    <td class="textright"><?php echo  number_format(($totalSaldo), 0, ",", ".") . ' Gs.';  ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>