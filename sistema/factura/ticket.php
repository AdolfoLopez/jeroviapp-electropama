<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
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

		@font-face {
			font-family: 'Roboto', sans-serif;
			src: url('fonts/Roboto-Medium.ttf') format('truetype');
			font-style: normal;
			font-weight: normal;
		}

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		p,
		label,
		span,
		table {
			font-family: 'Roboto', sans-serif;
			font-size: 9pt;
		}

		.borde_titulo {
			border-top: 1px solid #000;
			border-bottom: 1px solid #000;
			border-style: dotted;
			border-left: none;
			border-right: none;
		}

		.h2 {
			font-family: 'Roboto', sans-serif;
			font-size: 18pt;
		}

		.h3 {
			font-family: 'Roboto', sans-serif;
			font-size: 14pt;
			display: block;
			/* background: #0a4661; */
			/* color: #fff; */
			text-align: center;
			padding: 3px;
			margin-bottom: 5px;
		}

		#page_pdf {
			width: 95%;
			margin: 0px auto 1px auto;
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
			width: 95%;
			text-align: center;
			height: auto;
		}

		.info_factura {
			width: 95%;
		}

		.info_cliente {
			/* width: 100%; */
		}

		.datos_cliente {
			/* width: 100%; */
		}

		.datos_cliente tr td {
			/* width: 20%; */
		}

		.datos_cliente {
			/* padding: 10px 10px 0 10px; */
		}

		.datos_cliente label {
			/* width: 75px; */
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

		/* .round {
			border-radius: 10px;
			border: 1px solid #44ee01;
			overflow: hidden;
			padding-bottom: 15px;
		} */

		.round p {
			padding: 0 10px;
		}

		#factura_detalle {
			border-collapse: collapse;
		}

		#factura_detalle thead th {
			/* background: #058167; */
			/* color: #fff; */
			padding: 5px;
			border-top: 1px solid #000;
			border-bottom: 1px solid #000;
			border-style: dotted;
			border-left: none;
			border-right: none;

		}

		/* #detalle_productos tr:nth-child(even) {
			background: #ededed;
		} */

		#detalle_totales span {
			font-family: 'Roboto', sans-serif;
		}

		.nota {
			font-size: 4pt;
		}

		.label_gracias {
			font-family: 'Roboto', sans-serif;
			font-weight: bold;
			font-style: italic;
			text-align: center;
			margin-top: 10px;
		}

		.label_gracias2 {
			margin-bottom: 30px;
			color: #000;
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
		<table id="factura_head">
			<tr>
				<!-- <td class="logo_factura">
					<div>
						 <img src="img/logo.png">
						<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
					</div>
				</td> -->
				<td class="info_empresa">
					<?php
					if ($result_config > 0) {
						$iva = $configuracion['iva'];
					?>
						<div>
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
							<!-- <p><?php echo $configuracion['razon_social']; ?></p> -->
							<p><?php echo $configuracion['direccion']; ?></p>
							<p>R.U.C: <?php echo $configuracion['ci_ruc']; ?></p>
							<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
							<p>Email: <?php echo $configuracion['email']; ?></p>
						</div>
					<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="info_factura">
					<div>
						<!-- <span class="h3">Factura</span> -->
						<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
						<p>No. Venta: <strong><?php echo $factura['idVenta']; ?></strong></p>
						<p>Fecha: <?php echo $factura['fecha']; ?></p>
						<p>Hora: <?php echo $factura['hora']; ?></p>
						<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
						<p>Obra: <?php echo $factura['nombreObra']; ?></p>
					</div>
				</td>
			</tr>
		</table>
		<table id="info_factura">
			<tr>
				<td class="info_cliente">

					<span class="h3 borde_titulo">Cliente</span>
					<p>C.I/RUC: <?php echo $factura['ci_ruc']; ?></p>
					<p>Teléfono: <?php echo $factura['celular1']; ?></p>
					<p>Nombre: <?php echo $factura['nombre']; ?></p>
					<p>Dirección: <?php echo $factura['direccion']; ?></p>
	</div>
	</td>

	</tr>
	</table>

	<table id="factura_detalle">
		<thead>
			<tr>
				<th>Cant.</th>
				<th class="textleft">Descripción</th>
				<th class="textright">Precio Unitario.</th>
				<th class="textright"> Precio Total</th>
			</tr>
		</thead>
		<tbody id="detalle_productos">

			<?php

			if ($result_detalle > 0) {

				while ($row = mysqli_fetch_assoc($query_productos)) {
			?>
					<tr>
						<td class="textcenter"><?php echo $row['cant']; ?></td>
						<td><?php echo $row['nombre']; ?></td>
						<td class="textright"><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
						<td class="textright"><?php echo number_format(($row['precio_total'] - (($row['precio_total'] * $row['porc_desc']) / 100)), 0, ",", "."); ?></td>
					</tr>
			<?php
					$precio_total = ($row['precio_total'] - (($row['precio_total'] * $row['porc_desc']) / 100));
					$subtotal = round($subtotal + $precio_total, 2);
				}
			}

			// $impuesto 	= round($subtotal * ($iva / 100), 2);
			$impuesto 	= round($subtotal / 11, 2);
			$tl_sniva 	= round($subtotal - $impuesto, 2);
			$total 		= round($tl_sniva + $impuesto, 2);
			?>
		</tbody>
		<tfoot id="detalle_totales" class="borde_titulo">
			<tr>
				<td colspan="3" class="textright"><span>SUBTOTAL Gs.</span></td>
				<td class="textright"><span><?php echo number_format($tl_sniva, 0, ",", "."); ?></span></td>
			</tr>
			<tr>
				<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
				<td class="textright"><span><?php echo number_format($impuesto, 0, ",", ".");  ?></span></td>
			</tr>
			<tr>
				<td colspan="3" class="textright"><span>TOTAL Gs.</span></td>
				<td class="textright"><span><?php echo number_format($total, 0, ",", "."); ?></span></td>
			</tr>
		</tfoot>
	</table>
	<div>
		<!-- <p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p> -->
		<h4 class="label_gracias">¡Gracias por su compra!</h4>
		<h4 class="label_gracias2">.</h4>
	</div>
	</div>
</body>

</html>