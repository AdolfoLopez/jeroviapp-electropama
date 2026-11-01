<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
require_once '../dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (empty($_REQUEST['pr']) || empty($_REQUEST['f'])) {
	echo "No es posible generar la factura de Compra - en archivo generarFacturaCompra.";
} else {
	$codProveedor = $_REQUEST['pr'];
	$noFactura = $_REQUEST['f'];
	$anulada = '';

	$query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
	$result_config  = mysqli_num_rows($query_config);
	if ($result_config > 0) {
		$configuracion = mysqli_fetch_assoc($query_config);
	}

	$query = mysqli_query($conection,
	"SELECT
	c.idCompra,
	DATE_FORMAT(c.fecha, '%d/%m/%Y') as fecha,
	DATE_FORMAT(c.fecha,'%H:%i:%s') as hora,
	c.idProveedor,
	c.status,
	u.nombre as vendedor,
	p.ruc,
	p.nombre,
	p.telefono,
	p.direccion
	FROM compras c
	INNER JOIN usuarios u ON (c.idUsuario = u.idUsuario)
	INNER JOIN proveedores p ON (c.idProveedor = p.idProveedor)
	WHERE c.idCompra = $noFactura AND c.idProveedor = $codProveedor AND c.status != 10;");

	$result = mysqli_num_rows($query);
	if ($result > 0) {
		$factura = mysqli_fetch_assoc($query);
		$no_factura = $factura['idCompra'];

		if ($factura['status'] == 2) {
			$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
		}

		$query_productos = mysqli_query($conection,
		"SELECT
		p.nombre,
		dc.cant,
		dc.precioCompra,
		(dc.cant * dc.precioCompra) as precio_total
		FROM compras c
		INNER JOIN detallesCompra dc ON (c.idCompra = dc.noCompra)
		INNER JOIN productos p ON (dc.idProducto = p.idProducto)
		WHERE c.idCompra = $no_factura ");
		$result_detalle = mysqli_num_rows($query_productos);

		ob_start();
		include(dirname('__FILE__') . '/facturaCompra.php');
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('letter', 'portrait');
		//$dompdf->setPaper('B6', 'landscape');
		//$dompdf->setPaper('C6', 'landscape');
		$dompdf->setPaper(array(0,0,360,360), 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		ob_get_clean();
		$dompdf->stream('factura_' . $noFactura . '.pdf', array('Attachment' => 0));
		exit;
	}
}

//TAMANO PAPEL
//12.5CM
//14CM