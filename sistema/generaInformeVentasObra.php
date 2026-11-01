<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}

include "../../conexion.php";
require_once '../dompdf/dompdf/autoload.inc.php';
require_once '../dompdf/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';

use Dompdf\Dompdf;

$fecha_de	= '';
$fecha_a	= '';
$b_obra		= '';
$b_pagado	= '';
$where		= '';

if (isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])) {
	if ($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == '') {
		header("location: informeVentasObras.php");
	}
}

if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
	$fecha_de 	= $_REQUEST['fecha_de'];
	$fecha_a  	= $_REQUEST['fecha_a'];
	$b_obra		= $_REQUEST['obra'];
	$b_pagado	= $_REQUEST['pagado'];
	print_r($fecha_de . $fecha_a . $b_obra . $b_pagado);

	if ($fecha_de > $fecha_a) {
		header('location: informeVentasObras.php');
		//fecha iguales - pagado: diferente a todo - obra: diferente a todo
	} elseif ($fecha_de == $fecha_a && $b_pagado != 'TODOS' && $b_obra != 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha LIKE '$fecha_de%' AND c.status = '$b_pagado' AND o.ID_OBRA = '$b_obra'";
		//fecha iguales - pagado: diferente a todo - obra: igual a todo
	} elseif ($fecha_de == $fecha_a && $b_obra == 'TODOS' && $b_pagado != 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha LIKE '$fecha_de%' AND c.status = '$b_pagado'";
		//fecha iguales - pagado: igual a todo - obra: diferente a todo
	} elseif ($fecha_de == $fecha_a && $b_obra != 'TODOS' && $b_pagado == 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha LIKE '$fecha_de%' AND o.ID_OBRA = '$b_obra'";
		//fecha iguales - pagado: igual a todo - obra: igual a todo
	} elseif ($fecha_de == $fecha_a && $b_obra == 'TODOS' && $b_pagado == 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha LIKE '$fecha_de%'";
	} elseif ($fecha_de < $fecha_a && $b_pagado != 'TODOS' && $b_obra != 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = '$b_pagado' AND o.ID_OBRA = '$b_obra'";
	} elseif ($fecha_de < $fecha_a && $b_obra == 'TODOS' && $b_pagado != 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = '$b_pagado'";
	} elseif ($fecha_de < $fecha_a && $b_obra != 'TODOS' && $b_pagado == 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND o.ID_OBRA = '$b_obra'";
	} elseif ($fecha_de < $fecha_a &&  $b_obra == 'TODOS' && $b_pagado == 'TODOS') {
		$f_de = $fecha_de . ' 00:00:00';
		$f_a = $fecha_a . ' 23:59:59';
		$where = "v.fecha BETWEEN '$f_de' AND '$f_a'";
	}






	// elseif ($fecha_de == $fecha_a && $b_categorias == 'TODOS' && $b_subCategorias != 'TODOS') {
	// 	$where = "v.fecha LIKE '$fecha_de%' AND c.status = '2' AND sca.id = '$b_subCategorias'";

	// } 
	// elseif ($fecha_de == $fecha_a && $b_subCategorias == 'TODOS' && $b_categorias == 'TODOS') {
	// 	$where = "v.fecha LIKE '$fecha_de%' AND c.status = '2'";
	// } elseif ($b_categorias == 'TODOS' && $b_subCategorias <> 'TODOS') {
	// 	$f_de = $fecha_de . ' 00:00:00';
	// 	$f_a = $fecha_a . ' 23:59:59';
	// 	$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = 2 AND sca.id = '$b_subCategorias'";
	// } elseif ($b_subCategorias == 'TODOS' && $b_categorias <> 'TODOS') {
	// 	$f_de = $fecha_de . ' 00:00:00';
	// 	$f_a = $fecha_a . ' 23:59:59';
	// 	$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = 2 AND ca.idCategoria = '$b_categorias'";
	// } elseif ($b_subCategorias == 'TODOS' && $b_categorias == 'TODOS') {
	// 	$f_de = $fecha_de . ' 00:00:00';
	// 	$f_a = $fecha_a . ' 23:59:59';
	// 	$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = 2 ";
	// } elseif ($b_subCategorias <> 'TODOS' && $b_categorias <> 'TODOS') {
	// 	$f_de = $fecha_de . ' 00:00:00';
	// 	$f_a = $fecha_a . ' 23:59:59';
	// 	$where = "v.fecha BETWEEN '$f_de' AND '$f_a' AND c.status = 2 AND ca.idCategoria = '$b_categorias' AND sca.id = '$b_subCategorias'";
	// }
}

$query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
$result_config  = mysqli_num_rows($query_config);
if ($result_config > 0) {
	$configuracion = mysqli_fetch_assoc($query_config);
}

$query	= mysqli_query(
	$conection,
	"SELECT 
v.idVenta, 
DATE_FORMAT(v.fecha, '%d-%m-%Y') AS fecha, 
p.referencia, 
p.nombre AS nombreProducto, 
dv.precioVenta, 
dv.cant, 
c.status as pagado, 
o.NOMBRE AS nombreObra, 
DATE_FORMAT(c.fechaPago, '%d-%m-%Y') AS fechaPago,  
(dv.precioVenta * dv.cant) AS subTotal 
FROM ventas v 
INNER JOIN detallesventa dv ON(v.idVenta = dv.noVenta) 
INNER JOIN productos p ON(p.idProducto = dv.idProducto) 
INNER JOIN cuotas c ON(c.idVenta = v.idVenta) 
INNER JOIN OBRA o ON(o.ID_OBRA = v.ID_OBRA)
WHERE $where"
);

$result = mysqli_num_rows($query);
// printf($result);

$nombrePdf = 'adigsoft_' . md5(date('d-m-Y H:m:s'));

ob_start();
include(dirname('__FILE__') . '/informeVentasObrasPdf.php');
$html = ob_get_clean();
// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait'); //tamaño cuadrado factura fermin
// $dompdf->set_option('defaultMediaType', 'all');
// $dompdf->set_option('isFontSubsettingEnabled', true);


// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
ob_get_clean();
$dompdf->stream($nombrePdf . '.pdf', array('Attachment' => 0));
exit;
