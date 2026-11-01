<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
//print_r($configuracion); 

include "../js/numLetras.php";

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
		table,
		div {
			font-family: "BrixSansRegular";
			font-size: 11px;
		}

		.div1 {
			
			position: absolute;
			/* border: 1px solid #000; */
			width: 98%;
			min-height: 1550px;
			max-height: 1550;
			margin-bottom: 25px;
			top: 5px;
			left: 5px;
		}

		.div2 {
			position: absolute;
			/* border: 1px solid #000; */
			width: 95%;
			top: 395px;
			left: 5px;
		}

		.div3 {
			position: absolute;
			/* border: 1px solid #000; */
			min-width: 1500px;
			max-width: 1500px;
			width: 1020px;
			min-height: 1550px;
			max-height: 1550;
			top: 790px;
			left: 5px;

		}

		/*INICIO - Encabezado de la factura*/
		.fecha_factura{
			position: absolute;
			top: 35px;
			left: 160px;
		}

		.razonSocial {
			position: absolute;
			top: 50px;
			left: 165px;
		}

		.ruc {
			position: absolute;
			top: 67px;
			left: 152px;
		}

		.direccion {
			position: absolute;
			top: 82px;
			left: 170px;
		}

		.telefono {
			position: absolute;
			top: 67px;
			left: 270px;
		}

		.condicion_venta{
			position: absolute;
			top: 92px;
			left: 508px;
		}

		.condicion_venta_credito{
			position: absolute;
			top: 92px;
			left: 585px;
		}

		/* .vendedor {
			position: relative;
			top: 50px;
			left: 500px;
		} */

		/* .email {
			border: 1px solid #ccc;

			position: relative;
			top: 50px;
			left: 50px;
		} */
		/*FIN - Encabezado de la factura*/

		.lista_productos {
			position: absolute;
			top: 127px;
			left: 50px;
		}

		.sub_total_iva_10{
			position: absolute;
			top: 205px;
			left: 660px;
		}


		.total {
			position: absolute;
			top: 235px;
			left: 660px;
		}


		.iva_10{
			position: absolute;
			top: 240px;
			left: 175px;
		}

		.total_iva{
				position: absolute;
				top: 240px;
				left: 292px;
		}

		.monto_letras{
			position:absolute;
			width: 250px;
			top: 210px;
			left: 110px;
		}

	

	</style>
</head>

<body>
	<div class="div1">
		<?php
		if ($result_config > 0) {
			$iva = $configuracion['iva'];
		?>
			<!-- <div >
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
						</div> -->
			<!-- <div class="">
							<p><?php echo $configuracion['razon_social']; ?></p>
						</div>
						<div class="">
							<?php echo $configuracion['direccion']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['ci_ruc']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['telefono']; ?>
						</div>
						<div class="email">
							<?php echo $configuracion['email']; ?>
						</div> -->
		<?php
		}
		?>
		<!-- </td> -->
		<!-- <td class="info_factura"> -->
		<!-- <div> -->
		<!-- <span class="h3">Factura</span> -->
		<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		<!-- <p>No.: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		
		<div class="fecha_factura">
			<?php echo $factura['fecha']; ?>
		</div>

		<div class="razonSocial">
			<p><?php echo $factura['nombre']; ?></p>
		</div>

		<div class="ruc">
			<p><?php echo $factura['ci_ruc']; ?></p>
		</div>

		<div class="direccion">
			<p><?php echo $factura['direccion']; ?></p>
		</div>

		<div class="telefono">
			<p><?php echo "Cel.: " . $factura['celular1']; ?></p>
		</div>

		<?php
		if ($factura['tipo'] == 1) {	
		?>
		<div class="condicion_venta">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<?php
		if ($factura['tipo'] == 2) {	
		?>
		<div class="condicion_venta_credito">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<!-- <p>Hora: <?php echo $factura['hora']; ?></p> -->
		<!-- <div class="vendedor">
			<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
		</div> -->


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
		
		<!-- </td> -->
		<!-- <td><label></label> -->
		
		<!-- </td> -->
		<!-- </tr> -->
		<!-- <tr> -->
		<!-- <td><label>Nombre:</label> -->
		
		<!-- </td> -->
		<!-- <td><label>Dirección:</label> -->
		
		<!-- </td> -->
		<!-- </tr> -->
		<!-- </table> -->
		<!-- </div> -->
		<!-- </td> -->

		<!-- </tr> -->
		<!-- </table> -->
		<div class="lista_productos">
			<table>
				<tbody>
					<?php
					if ($result_detalle > 0) {
						while ($row = mysqli_fetch_assoc($query_productos)) {
					?>
							<tr>
								<td style="width: 35px;" class="td_cant"><?php echo $row['cant']; ?></td>
								<td style="width:350px;"><?php echo $row['nombre']; ?></td>
								<td style="width:97px;" ><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
								<td style="width:97px;" ><?php echo " "; ?></td>
								<td style="width:70px;" ><?php echo " "; ?></td>
								<!-- <td><?php echo number_format($row['precio_total'], 0, ",", "."); ?></td> -->
								<td class="textright"><?php echo number_format(($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100)), 0, ",", "."); ?></td>

							</tr>
					<?php
							// $precio_total = $row['precio_total'];
							$precio_total = ($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100));
							$subtotal = round($subtotal + $precio_total, 2);
						}
					}
					$iva_10 	= round($subtotal / 11, 2);
					// $tl_sniva 	= round($subtotal - $impuesto, 2);
					$total 		= round($subtotal, 2);
					$total_iva  = round($iva_10,2);
					?>
				</tbody>

						<!-- <span class="total_sin_iva"><?php echo number_format($tl_sniva, 0, ",", "."); ?></span> -->
						<span class="sub_total_iva_10"><?php echo number_format($total, 0, ",", "."); ?></span>
						<span class="iva_10"><?php echo number_format($iva_10, 0, ",", ".");  ?></span>
						<span class="total_iva"><?php echo number_format($total_iva, 0, ",", ".");  ?></span>
						<span class="total"><?php echo number_format($total, 0, ",", "."); ?></span>
						
						<p class="monto_letras"><?php echo convertirNumeroLetra($total);  ?></p>
				
			</table>
		</div>
		
	</div>
<?php
$impuesto 	= 0;
$tl_sniva 	= 0;
$total = 0;
$total_iva = 0;
$iva_10 = 0;
$subtotal = 0;
?>

<div class="div2">
		<?php
		if ($result_config > 0) {
			$iva = $configuracion['iva'];
		?>
			<!-- <div >
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
						</div> -->
			<!-- <div class="">
							<p><?php echo $configuracion['razon_social']; ?></p>
						</div>
						<div class="">
							<?php echo $configuracion['direccion']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['ci_ruc']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['telefono']; ?>
						</div>
						<div class="email">
							<?php echo $configuracion['email']; ?>
						</div> -->
		<?php
		}
		?>
		<!-- </td> -->
		<!-- <td class="info_factura"> -->
		<!-- <div> -->
		<!-- <span class="h3">Factura</span> -->
		<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		<!-- <p>No.: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		
		<div class="fecha_factura">
			<?php echo $factura['fecha']; ?>
		</div>

		<div class="razonSocial">
			<p><?php echo $factura['nombre']; ?></p>
		</div>

		<div class="ruc">
			<p><?php echo $factura['ci_ruc']; ?></p>
		</div>

		<div class="direccion">
			<p><?php echo $factura['direccion']; ?></p>
		</div>

		<div class="telefono">
			<p><?php echo "Cel.: " . $factura['celular1']; ?></p>
		</div>

		<?php
		if ($factura['tipo'] == 1) {	
		?>
		<div class="condicion_venta">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<?php
		if ($factura['tipo'] == 2) {	
		?>
		<div class="condicion_venta_credito">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<!-- <p>Hora: <?php echo $factura['hora']; ?></p> -->
		<!-- <div class="vendedor">
			<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
		</div> -->


		
		<div class="lista_productos">
			<table>
				<tbody>
					<?php
					if ($result_detalle2 > 0) {
						while ($row = mysqli_fetch_assoc($query_productos2)) {
					?>
							<tr>
							<td style="width: 35px;" class="td_cant"><?php echo $row['cant']; ?></td>
								<td style="width:350px;"><?php echo $row['nombre']; ?></td>
								<td style="width:97px;" ><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
								<td style="width:97px;" ><?php echo " "; ?></td>
								<td style="width:70px;" ><?php echo " "; ?></td>
								<!-- <td><?php echo number_format($row['precio_total'], 0, ",", "."); ?></td> -->
								<td class="textright"><?php echo number_format(($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100)), 0, ",", "."); ?></td>

							</tr>
					<?php
							// $precio_total = $row['precio_total'];
							$precio_total = ($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100));

							$subtotal = round($subtotal + $precio_total, 2);
						}
					}
					$iva_10 	= round($subtotal / 11, 2);
					// $tl_sniva 	= round($subtotal - $impuesto, 2);
					$total 		= round($subtotal, 2);
					$total_iva  = round($iva_10,2);
					?>
				</tbody>

						<!-- <span class="total_sin_iva"><?php echo number_format($tl_sniva, 0, ",", "."); ?></span> -->
						<span class="sub_total_iva_10"><?php echo number_format($total, 0, ",", "."); ?></span>
						<span class="iva_10"><?php echo number_format($iva_10, 0, ",", ".");  ?></span>
						<span class="total_iva"><?php echo number_format($total_iva, 0, ",", ".");  ?></span>
						<span class="total" id="total"><?php echo number_format($total, 0, ",", "."); ?></span>
				
						<p class="monto_letras"><?php echo convertirNumeroLetra($total);  ?></p>
				
			</table>
		</div>
		<!-- <span class="monto_letras" id="texto"> <?php echo $numero_en_letreas;   ?></span>
		<span class="monto_letras" id="texto2">o</span> -->
		
		
	</div>

	<?php
$impuesto 	= 0;
$tl_sniva 	= 0;
$total = 0;
$total_iva = 0;
$iva_10 = 0;
$subtotal = 0;
?>


	<div class="div3">
		<?php
		if ($result_config > 0) {
			$iva = $configuracion['iva'];
		?>
			<!-- <div >
							<span class="h2"><?php echo strtoupper($configuracion['nombre']); ?></span>
						</div> -->
			<!-- <div class="">
							<p><?php echo $configuracion['razon_social']; ?></p>
						</div>
						<div class="">
							<?php echo $configuracion['direccion']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['ci_ruc']; ?>
						</div>
						<div class="">
							<?php echo $configuracion['telefono']; ?>
						</div>
						<div class="email">
							<?php echo $configuracion['email']; ?>
						</div> -->
		<?php
		}
		?>
		<!-- </td> -->
		<!-- <td class="info_factura"> -->
		<!-- <div> -->
		<!-- <span class="h3">Factura</span> -->
		<!-- <p>No. Factura: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		<!-- <p>No.: <strong><?php echo $factura['idVenta']; ?></strong></p> -->
		
		<div class="fecha_factura">
			<?php echo $factura['fecha']; ?>
		</div>

		<div class="razonSocial">
			<p><?php echo $factura['nombre']; ?></p>
		</div>

		<div class="ruc">
			<p><?php echo $factura['ci_ruc']; ?></p>
		</div>

		<div class="direccion">
			<p><?php echo $factura['direccion']; ?></p>
		</div>

		<div class="telefono">
			<p><?php echo "Cel.: " . $factura['celular1']; ?></p>
		</div>

		<?php
		if ($factura['tipo'] == 1) {	
		?>
		<div class="condicion_venta">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<?php
		if ($factura['tipo'] == 2) {	
		?>
		<div class="condicion_venta_credito">
			<p>X</p>
		</div>
		<?php
		}
		?>

		<!-- <p>Hora: <?php echo $factura['hora']; ?></p> -->
		<!-- <div class="vendedor">
			<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
		</div> -->


		
		<!-- </table> -->
		<div class="lista_productos">
			<table>
				<tbody>
					<?php
					if ($result_detalle3 > 0) {
						while ($row = mysqli_fetch_assoc($query_productos3)) {
					?>
							<tr>
							<td style="width: 35px;" class="td_cant"><?php echo $row['cant']; ?></td>
								<td style="width:350px;"><?php echo $row['nombre']; ?></td>
								<td style="width:97px;" ><?php echo number_format($row['precioVenta'], 0, ",", "."); ?></td>
								<td style="width:97px;" ><?php echo " "; ?></td>
								<td style="width:70px;" ><?php echo " "; ?></td>
								<!-- <td><?php echo number_format($row['precio_total'], 0, ",", "."); ?></td> -->
								<td class="textright"><?php echo number_format(($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100)), 0, ",", "."); ?></td>

							</tr>
					<?php
							// $precio_total = $row['precio_total'];
							$precio_total = ($row['precio_total'] - (($row['precio_total'] * $row['porc_desc'])/100));

							$subtotal = round($subtotal + $precio_total, 2);
						}
					}
					$iva_10 	= round($subtotal / 11, 2);
					// $tl_sniva 	= round($subtotal - $impuesto, 2);
					$total 		= round($subtotal, 2);
					$total_iva  = round($iva_10,2);
					?>
				</tbody>

						<!-- <span class="total_sin_iva"><?php echo number_format($tl_sniva, 0, ",", "."); ?></span> -->
						<span class="sub_total_iva_10"><?php echo number_format($total, 0, ",", "."); ?></span>
						<span class="iva_10"><?php echo number_format($iva_10, 0, ",", ".");  ?></span>
						<span class="total_iva"><?php echo number_format($total_iva, 0, ",", ".");  ?></span>
						<span class="total"><?php echo number_format($total, 0, ",", "."); ?></span>
						<p class="monto_letras"><?php echo convertirNumeroLetra($total);  ?></p>

			</table>
		</div>
	</div>


</body>

</html>

