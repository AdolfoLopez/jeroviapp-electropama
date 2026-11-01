<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
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
	}

	$query = mysqli_query($conection, "SELECT v.idVenta, DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora, v.idCliente, v.status,
	u.nombre as vendedor,
	cl.ci_ruc, cl.nombre, cl.celular1,cl.direccion
	FROM ventas v
	INNER JOIN usuarios u
	ON v.idUsuario = u.idUsuario
	INNER JOIN clientes cl
	ON v.idCliente = cl.idCliente
	WHERE v.idVenta = $noFactura AND v.idCliente = $codCliente  AND v.status != 10; ");

	$result = mysqli_num_rows($query);
	if ($result > 0) {
		$factura = mysqli_fetch_assoc($query);
		$no_factura = $factura['idVenta'];

		if ($factura['status'] == 2) {
			$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
		}

		$query_productos = mysqli_query($conection, "SELECT p.nombre,dt.cant,dt.precioVenta,(dt.cant * dt.precioVenta) as precio_total, dt.porc_desc
														FROM ventas f
														INNER JOIN detallesVenta dt
														ON f.idVenta = dt.noVenta
														INNER JOIN productos p
														ON dt.idProducto = p.idProducto
														WHERE f.idVenta = $no_factura ");
		$result_detalle = mysqli_num_rows($query_productos);

		ob_start();
		include(dirname('__FILE__') . '/nota_pedido_24x14cm.php');
		$html = ob_get_clean();
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		//$paper_size = array(0,0,284,0);
		// $dompdf->setPaper(array(0, 0, 680, 700), 'portrait');
		$dompdf->setPaper('A4', 'portrait');

		// $dompdf->set_paper($paper_size,'portrait');
		//$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		ob_get_clean();
		$dompdf->stream('notaPedido_' . $noFactura . '.pdf', array('Attachment' => 0));
		exit;
	}
}
