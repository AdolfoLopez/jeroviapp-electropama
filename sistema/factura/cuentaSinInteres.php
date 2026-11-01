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

        @page {
            margin: 2cm;
            size: letter portrait;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 9pt;
            background: #fff;
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
            margin-bottom: 10px;
        }

        .h3 {
            /* font-family: "BrixSansBlack"; */
            font-size: 12pt;
            display: block;
            background: #ccc;
            color: #000;
            text-align: center;
            padding: 3px;
            margin-bottom: 8px;
            page-break-after: avoid;
            margin-top: 0;
        }

        #page_pdf {
            width: 100%;
            margin: 0;
            padding: 0;
            position: relative;
        }

        #factura_head,
        #factura_cliente,
        #factura_detalle {
            width: 100%;
            margin: 15px 0 10px 0;
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

        .round {
            position: relative;
            border-radius: 10px;
            border: 1px solid #ececec;
            padding: 10px;
            width: 100%;
            margin: 0 0 15px 0;
            background: #fff;
            page-break-inside: auto;
        }

        .round:first-of-type {
            margin-top: 0;
        }

        .round:not(:first-of-type) {
            margin-top: 20px;
        }

        .round p {
            padding: 0 15px;
        }

        .datos_cliente {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 0 10px 0;
            background: #fff;
            page-break-inside: auto;
        }

        .datos_cliente tr {
            break-inside: avoid;
            background: #fff;
        }

        .datos_cliente td {
            padding: 4px;
            border: none;
            font-size: 8pt;
            word-wrap: break-word;
            vertical-align: top;
            background: #fff;
        }

        #factura_detalle {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 0 0;
            background: #fff;
            page-break-inside: auto;
        }

        #factura_detalle thead {
            display: table-header-group;
            background: #fff;
        }

        #factura_detalle tbody {
            display: table-row-group;
            background: #fff;
        }

        #factura_detalle tfoot {
            display: table-footer-group;
            background: #fff;
        }

        #factura_detalle tr {
            break-inside: avoid;
            background: #fff;
        }

        #factura_detalle thead th,
        #factura_detalle tbody td {
            padding: 4px;
            border: none;
            font-size: 8pt;
            word-wrap: break-word;
            background: #fff;
        }

        #factura_detalle thead th {
            background: #ccc;
            color: #000;
        }

        #detalle_totales {
            border-top: none;
            font-weight: bold;
        }

        #detalle_totales td {
            padding: 5px;
            border: none;
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

        /* Estilos para las secciones */
        .section {
            position: relative;
            margin: 0 2cm;
            padding: 0;
            page-break-inside: auto;
            min-height: 100%;
        }

        .section:first-child {
            padding-top: 2cm;
        }

        /* Ajustes específicos para las columnas */
        .datos_cliente tr td:nth-child(1) { width: 7%; }
        .datos_cliente tr td:nth-child(2) { width: 12%; }
        .datos_cliente tr td:nth-child(3) { width: 24%; }
        .datos_cliente tr td:nth-child(4) { width: 7%; }
        .datos_cliente tr td:nth-child(5) { width: 15%; }
        .datos_cliente tr td:nth-child(6) { width: 15%; }
        .datos_cliente tr td:nth-child(7) { width: 20%; }

        /* Forzar saltos de página */
        .page-break {
            page-break-before: always;
            break-before: page;
            margin: 0;
            padding: 0;
            height: 0;
            border: none;
            display: block;
        }

        /* Asegurar que las tablas no se corten */
        table {
            break-inside: auto;
            background: #fff;
        }

        tr {
            break-inside: avoid;
            background: #fff;
        }

        thead {
            display: table-header-group;
            background: #fff;
        }

        tfoot {
            display: table-footer-group;
            background: #fff;
        }

        /* Ajustes para el encabezado del documento */
        h3 {
            margin: 0 0 10px 0;
            font-size: 14pt;
            page-break-after: avoid;
            background: #fff;
            text-align: center;
        }

        h5 {
            margin: 0 0 15px 0;
            font-size: 12pt;
            page-break-after: avoid;
            background: #fff;
            text-align: center;
        }

        /* Ajustes para el contenido de las tablas */
        .datos_cliente tr:first-child td {
            background: #f5f5f5;
            font-weight: bold;
            page-break-after: avoid;
        }

        /* Asegurar que los encabezados de tabla se repitan */
        #factura_detalle thead tr {
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        /* Ajustes para el pie de tabla */
        #detalle_totales {
            page-break-inside: avoid;
        }

        /* Ajustes para las etiquetas y contenido */
        .datos_cliente label {
            display: inline-block;
            width: 70px;
            font-weight: bold;
            font-size: 8pt;
        }

        .datos_cliente p {
            display: inline-block;
            margin: 0;
            padding: 0;
            font-size: 8pt;
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

        /* Eliminar los ajustes de margen después de salto de página */
        .section:after {
            content: none;
        }

        .page-break:before {
            content: none;
        }

        .section {
            width: 100%;
        }

        h3, h5 {
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- <?php echo $anulada; ?> -->
    <div id="page_pdf">
        <div class="section">
            <h3>EPM ELECTROPAMA</h3>
            <h5>ESTADO DE CUENTA DE CLIENTE SIN INTERES</h5>
            
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
        </div>

        <div class="page-break"></div>

        <div class="section">
            <div class="round">
                <span class="h3">En concepto de</span>
                <table class="datos_cliente">
                    <tr>
                        <td>No. Venta</td>
                        <td>Referencia</td>
                        <td>Nombre del Producto</td>
                        <td>Cant.</td>
                        <td>Precio Venta</td>
                        <td>Sub-Total</td>
                        <td>Obra</td>
                    </tr>
                    <?php
                    if ($result_producto > 0) {
                        while ($row_producto = mysqli_fetch_assoc($query_productos)) {
                    ?>
                            <tr>
                                <td><?php echo $row_producto['idVenta']; ?></td>
                                <td><?php echo $row_producto['referencia']; ?></td>
                                <td><?php echo $row_producto['nombre']; ?></td>
                                <td><?php echo $row_producto['cantidadVendida']; ?></td>
                                <td><?php echo $row_producto['precioVendido']; ?></td>
                                <td><?php echo $row_producto['precio_total']; ?></td>
                                <td><?php echo $row_producto['nombreObra']; ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="page-break"></div>

        <div class="section">
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
    </div>
</body>

</html>