<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
require_once '../dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
$result_config  = mysqli_num_rows($query_config);
if ($result_config > 0) {
	$configuracion = mysqli_fetch_assoc($query_config);
}

$query = mysqli_query(
	$conection,
	"SELECT
	p.referencia, 
	p.nombre, 
	sum(dv.cant) as cantVendida
	from ventas v
	INNER JOIN detallesventa dv ON(dv.noVenta = v.idVenta)
	INNER JOIN productos p ON(p.idProducto = dv.idProducto)
	INNER JOIN cuotas c ON(c.idVenta = v.idVenta)
	WHERE
	c.status = 2
	GROUP BY p.nombre  
	ORDER BY cantVendida  DESC
	LIMIT 50; "
);


$result = mysqli_num_rows($query);

	ob_start();
	include(dirname('__FILE__') . '/listadoMasVendidos.php');
	$html = ob_get_clean();

	// instantiate and use the dompdf class
	$dompdf = new Dompdf();

	$dompdf->loadHtml($html);
	// (Optional) Setup the paper size and orientation
	//$dompdf->setPaper('letter', 'portrait');
	//$dompdf->setPaper('B6', 'landscape');
	//$dompdf->setPaper('C6', 'landscape');
	$dompdf->setPaper('A4', 'portrait'); //tamaño cuadrado factura fermin
	// $dompdf->setPaper('letter', 'portrait');//tamaño cuadrado factura fermin

	// Render the HTML as PDF
	$dompdf->render();
	// Output the generated PDF to Browser
	ob_get_clean();
	$dompdf->stream('listado_' . 'mas vendidos' . '.pdf', array('Attachment' => 0));
	exit;

