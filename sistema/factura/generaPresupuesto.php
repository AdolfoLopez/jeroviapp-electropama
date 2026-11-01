<?php

//print_r($_REQUEST);
//exit;
//echo base64_encode('2');
//exit;
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
//require_once '../pdf/vendor/autoload.php';
require_once '../dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (empty($_REQUEST['cl']) || empty($_REQUEST['f'])) {
	echo "No es posible generar la factura.";
} else {
	$codCliente = $_REQUEST['cl'];
	$noFactura = $_REQUEST['f'];
	$anulada = '';


	$query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
	$result_config  = mysqli_num_rows($query_config);
	if ($result_config > 0) {
		$configuracion = mysqli_fetch_assoc($query_config);
		echo 'hola query config';
	}


	// $query = mysqli_query($conection, "SELECT v.idVenta, DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora, v.idCliente, v.status,
	// 											 v.nombre as vendedor,
	// 											 cl.ci_ruc, cl.nombre, cl.telefono1,cl.direccion
	// 										FROM ventas v
	// 										INNER JOIN usuarios u
	// 										ON v.idUsuario = u.idUsuario
	// 										INNER JOIN clientes cl
	// 										ON v.idCliente = cl.idCliente
	// 										WHERE v.idVenta = $noFactura AND v.idCliente = $idCliente  AND v.status != 10 ");

	// $query = mysqli_query($conection, "SELECT v.idVenta, DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora, v.idCliente, v.status,
	// u.nombre as vendedor,
	// cl.ci_ruc, cl.nombre, cl.telefono1,cl.direccion
	// FROM ventas v
	// INNER JOIN usuarios u
	// ON v.idUsuario = u.idUsuario
	// INNER JOIN clientes cl
	// ON v.idCliente = cl.idCliente
	// WHERE v.idVenta = $noFactura AND v.idCliente = $codCliente  AND v.status != 10; ");

	$query = mysqli_query($conection, "SELECT p.idPresupuesto , DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(p.fecha,'%H:%i:%s') as  hora, p.idCliente, p.status, p.tipo,
	u.nombre as vendedor,
	cl.ci_ruc, cl.nombre, cl.telefono1,cl.direccion
	FROM presupuesto p
	INNER JOIN usuarios u ON (p.idUsuario = u.idUsuario)
	INNER JOIN clientes cl ON (p.idCliente = cl.idCliente)
WHERE p.idPresupuesto = $noFactura AND p.idCliente = $codCliente  AND p.status != 10; ");


	$result = mysqli_num_rows($query);
	if ($result > 0) {

		$factura = mysqli_fetch_assoc($query);
		$no_factura = $factura['idPresupuesto'];

		if ($factura['status'] == 2) {
			$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
		}

		// $query_productos = mysqli_query($conection, "SELECT p.nombre,dt.cant,dt.precioVenta,(dt.cant * dt.precioVenta) as precio_total
		// 												FROM ventas f
		// 												INNER JOIN detallesVenta dt
		// 												ON f.idVenta = dt.noVenta
		// 												INNER JOIN productos p
		// 												ON dt.idProducto = p.idProducto
		// 												WHERE f.idVenta = $no_factura ");

		$query_productos = mysqli_query($conection, "SELECT p.nombre,dp.cant,dp.precioVenta,(dp.cant * dp.precioVenta) as precio_total FROM presupuesto pr
			INNER JOIN detallespresupuesto dp ON (pr.idPresupuesto = dp.noPresupuesto)
			INNER JOIN productos p ON (dp.idProducto = p.idProducto)
			WHERE pr.idPresupuesto = $no_factura;");
		$result_detalle = mysqli_num_rows($query_productos);

		ob_start();
		include(dirname('__FILE__') . '/presupuesto.php');
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		ob_get_clean();
		$dompdf->stream('factura_' . $noFactura . '.pdf', array('Attachment' => 0));
		exit;
	}
}
