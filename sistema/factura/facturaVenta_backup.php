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
			font-size: 11px;
		}


		#page_pdf {
			display: ;
			width: 95%;
			margin: 15px auto 10px auto;
			/* height: 200px; */
			border: 1px solid #000;
			min-height: 2000px;
			max-height: 2000px;
			position: relative;
			top: 15px;
			left: 10px;
		}




	</style>
</head>

<body>
	<!-- <?php echo $anulada; ?> -->
	<div id="page_pdf">
		<div class="razonSocial">
			<p>Razon social</p>
		</div>
		<!-- <table id="factura_head"> -->
			<!-- <tr> -->
				<!-- <td class="logo_factura">
					<div>
						 <img src="img/logo.png">
						<span class="h2"></span>
					</div>
				</td> -->
				<!-- <td class="info_empresa"> -->
					<?php
					if ($result_config > 0) {
						$iva = $configuracion['iva'];
					?>
						<!-- <div >
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
						</div> -->
						<div class="razonSocial">
							<!-- <p><?php echo $configuracion['razon_social']; ?></p> -->
						</div>
						<div class="direccion">
							<?php echo $configuracion['direccion']; ?>
						</div>
						<div class="ruc">
							<?php echo $configuracion['ci_ruc']; ?>
						</div>
						<div class="telefono">
							<?php echo $configuracion['telefono']; ?>
						</div>
						<div class="email">
							<?php echo $configuracion['email']; ?>
						</div>
					<?php
					}
					?>
				<!-- </td> -->
				<!-- <td class="info_factura"> -->
					<!-- <div> -->
						<!-- <span class="h3">Factura</span> -->
						<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
						<!-- <p>No.: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
						<!-- <p>Fecha: <?php echo $factura['fecha']; ?></p> -->
						<!-- <p>Hora: <?php echo $factura['hora']; ?></p> -->
						<div class="vendedor">
						<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
						</div>
					<!-- </div> -->
				<!-- </td> -->
			<!-- </tr> -->
		<!-- </table> -->
		<!-- <table id="factura_cliente"> -->
			<!-- <tr> -->
				<!-- <td class="info_cliente"> -->
					<!-- <div class="round"> -->
						<!-- <span class="h3 borde_titulo">Cliente</span> -->
						<!-- <table class="datos_cliente"> -->
							<!-- <tr> -->
								<!-- <td><label></label> -->
									<p><?php echo $factura['ci_ruc']; ?></p>
								<!-- </td> -->
								<!-- <td><label></label> -->
									<p><?php echo $factura['telefono1']; ?></p>
								<!-- </td> -->
							<!-- </tr> -->
							<!-- <tr> -->
								<!-- <td><label>Nombre:</label> -->
									<p><?php echo $factura['nombre']; ?></p>
								<!-- </td> -->
								<!-- <td><label>Dirección:</label> -->
									<p><?php echo $factura['direccion']; ?></p>
								<!-- </td> -->
							<!-- </tr> -->
						<!-- </table> -->
					<!-- </div> -->
				<!-- </td> -->

			<!-- </tr> -->
		<!-- </table> -->

		<table id="factura_detalle">
			<!-- <thead>
				<tr>
					<th>Cant.</th>
					<th class="textleft">Descripción</th>
					<th class="textright">Precio Unitario.</th>
					<th class="textright"> Precio Total</th>
				</tr>
			</thead> -->
			<tbody id="detalle_productos">

				<?php

				if ($result_detalle > 0) {

					while ($row = mysqli_fetch_assoc($query_productos)) {
				?>
						<tr>
							<td class="textcenter"><?php echo $row['cant']; ?></td>
							<td><?php echo $row['nombre']; ?></td>
							<td class="textright"><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
							<td class="textright"><?php echo number_format($row['precio_total'], 0, ",", "."); ?></td>
						</tr>
				<?php
						$precio_total = $row['precio_total'];
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
					<td colspan="3" class="textright"><span>SUBTOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($tl_sniva, 0, ",", "."); ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo number_format($impuesto, 0, ",", ".");  ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($total, 0, ",", ".") ; ?></span></td>
				</tr>
			</tfoot>
		</table>
		<div>
			<!-- <p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p> -->
			<h4 class="label_gracias">¡Gracias por su compra!</h4>
		</div>

	</div>
	<div id="page_pdf">
		<table>
		
			<tbody>

				<?php

				if ($result_detalle > 0) {

					while ($row2 = mysqli_fetch_assoc($query_productos)) {
				?>
						<tr>
							<td class="textcenter"><?php echo $row2['cant']; ?></td>
							<td><?php echo $row2['nombre']; ?></td>
							<td class="textright"><?php echo number_format($row2['precioVenta'], 0, ",", "."); ?></td>
							<td class="textright"><?php echo number_format($row2['precio_total'], 0, ",", "."); ?></td>
						</tr>
				<?php
						$precio_total = $row2['precio_total'];
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
					<td colspan="3" class="textright"><span>SUBTOTAL Gs.</span></td>
					<td class="textright"><span><?php echo number_format($tl_sniva, 0, ",", "."); ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo number_format($impuesto, 0, ",", ".");  ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL Gs.</span></td>
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