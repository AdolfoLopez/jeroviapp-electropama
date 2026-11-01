<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
$total_desc = 0;
$total_gral = 0;
//print_r($configuracion); 



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<!-- <link rel="stylesheet" href="style.css"> -->
	<style>
		@import url("fonts/BrixSansRegular.css");
		@import url("fonts/BrixSansBlack.css");

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		p,
		label,
		span,
		table {
			font-family: "BrixSansRegular";
			font-size:11pt;
		}

		.borde_titulo{
			
			border-top: 1px solid #000;
			border-bottom: 1px solid #000;
			border-style: dotted;
			border-left: none;
			border-right: none;
		}

		.h2 {
			font-family: "BrixSansBlack";
			font-size: 16pt;
		}

		.h3 {
			font-family: "BrixSansBlack";
			font-size: 12pt;
			display: block;
			/* background: #0a4661; */
			/* color: #fff; */
			text-align: center;
			padding: 1px;
			margin-bottom: 3px;
		}

		#page_pdf {
			width: 95%;
			margin: 0px auto 1px auto;
		}

		#factura_head,
		#factura_cliente,
		#factura_detalle {
			width: 100%;
			margin-bottom: 2px;
		}

		.logo_factura {
			width: 25%;
		}

		.info_empresa {
			width: 50%;
			text-align: center;
			height: 90px;
		}

		.info_factura {
			width: 50%;
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
			padding: 5px 5px 0 10px;
		}

		.datos_cliente label {
			width: 55px;
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
			padding: 3px;
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
			font-family: "BrixSansBlack";
		}

		.nota {
			font-size: 9pt;
		}

		.label_gracias {
			font-family: verdana;
			font-weight: bold;
			font-style: italic;
			text-align: center;
			margin-top: 10px;
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
				<td class="info_factura">
					<div>
						<!-- <span class="h3">Factura</span> -->
						<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
						<p style="font-size: 24px;">NOTA DE PEDIDO</p>
						<p>No.: <strong><?php echo $factura['idVenta']; ?></strong></p>
						<p>Fecha: <?php echo $factura['fecha']; ?></p>
						<p>Hora: <?php echo $factura['hora']; ?></p>
						<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
					</div>
				</td>
			</tr>
		</table>
		<table id="factura_cliente">
			<tr>
				<td class="info_cliente">
					<div class="round">
						<span class="h3 borde_titulo">Datos del Cliente</span>
						<table class="datos_cliente">
							<tr>
								<td><label>C.I/RUC:</label>
									<p><?php echo $factura['ci_ruc']; ?></p>
								</td>
								<td><label>Teléfono:</label>
									<p><?php echo $factura['celular1']; ?></p>
								</td>
							</tr>
							<tr>
								<td><label>Nombre:</label>
									<p><?php echo $factura['nombre']; ?></p>
								</td>
								<td><label>Dirección:</label>
									<p><?php echo $factura['direccion']; ?></p>
								</td>
							</tr>
						</table>
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
					<th class="textright">Desc.</th>
					<th class="textright"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

				<?php

				if ($result_detalle > 0) {

					while ($row = mysqli_fetch_assoc($query_productos)) {
						if($row['porc_desc'] == null || $row['porc_desc'] == 0){
							$desc = '';
						}else{
							$desc = $row['porc_desc'].'%';
						}
				?>
						<tr>
							<td class="textcenter"><?php echo $row['cant']; ?></td>
							<td><?php echo $row['nombre']; ?></td>
							<td class="textright"><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
							<td class="textright"><?php echo $desc; ?></td>
							<td class="textright"><?php echo number_format(($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100)), 0, ",", "."); ?></td>
						</tr>
				<?php
						// $precio_total = $row['precio_total'];
						$precio_total = ($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100));
						$total_desc = $total_desc + (($row['precio_total'] * $row['porc_desc'])/100);
						$total_gral = $total_gral + ($row['precioVenta'] * $row['cant']);
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto, 2);
				$total 		= round($tl_sniva + $impuesto, 2);
				?>
			</tbody>
			<tfoot id="detalle_totales" class="borde_titulo">
				<tr>
					<td colspan="4" class="textright"><span>SUBTOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($total_gral, 0, ",", "."); ?></span></td>
				</tr>
				<tr>
					<td colspan="4" class="textright"><span>TOTAL DESC.</span></td>
					<td class="textright"><span><?php echo number_format($total_desc, 0, ",", ".");  ?></span></td>
				</tr>
				<tr>
					<td colspan="4" class="textright"><span>TOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($total, 0, ",", ".") ; ?></span></td>
				</tr>
			</tfoot>
		</table>
		<div>
			<!-- <p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p> -->
			<h4 class="label_gracias">¡Gracias por su compra!</h4>
		</div>

	</div>

</body>

</html>