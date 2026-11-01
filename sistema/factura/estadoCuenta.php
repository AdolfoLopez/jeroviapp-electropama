<?php
session_start();
if (empty($_SESSION['active'])) {
    header('location: ../');
}

include "../../conexion.php";
//require_once '../pdf/vendor/autoload.php';
require_once '../dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (empty($_REQUEST['cl'])) {
    echo "No es posible generar la factura.";
} else {
    $codCliente = $_REQUEST['cl'];
    // $noFactura = $_REQUEST['f'];
    $anulada = '';


    $query_config   = mysqli_query($conection, "SELECT * FROM configuracion");
    $result_config  = mysqli_num_rows($query_config);
    if ($result_config > 0) {
        $configuracion = mysqli_fetch_assoc($query_config);
        echo 'SIN REGISTRO';
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

    //sql para traer las informaciones de la venta
    $query = mysqli_query($conection, "SELECT
    v.idVenta,
    DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha,
    DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora,
    v.idCliente,
    v.status,
    u.nombre as vendedor,
    cl.ci_ruc,
    cl.nombre,
    cl.telefono1,
    cl.direccion
    FROM ventas v
    INNER JOIN usuarios u
    ON v.idUsuario = u.idUsuario
    INNER JOIN clientes cl
    ON v.idCliente = cl.idCliente
    WHERE v.idCliente = $codCliente and v.status != 3");




    $result = mysqli_num_rows($query);
    if ($result > 0) {

        $cuenta = mysqli_fetch_assoc($query);
        $no_factura = $cuenta['idVenta'];

        if ($cuenta['status'] == 2) {
            $anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
        }

        $query_productos = mysqli_query($conection, "SELECT f.idVenta, p.nombre,dt.cant,dt.precioVenta,(dt.cant * dt.precioVenta) as precio_total, dt.cant as cantidadVendida, dt.precioVenta as precioVendido, ob.NOMBRE AS nombreObra 
        FROM ventas f
        INNER JOIN detallesVenta dt
        ON f.idVenta = dt.noVenta
        INNER JOIN productos p
        ON dt.idProducto = p.idProducto
       INNER JOIN clientes cl ON f.idCliente = cl.idCliente
       LEFT JOIN OBRA ob ON(ob.ID_OBRA = f.ID_OBRA)
       WHERE f.idCliente =  $codCliente and f.status != 3 ");

        $query_cuotas = mysqli_query($conection, "SELECT
        c.id,
        c.idVenta,
        c.cantCuota,
        c.noCuota,
        c.monto,
        DATE_FORMAT(c.fechaVencimiento, '%d/%m/%Y') as fechaVencimiento,
        DATE_FORMAT(c.fechaPago, '%d/%m/%Y') as fechaPago,
        c.status,
        c.montoRecibido,
        c.idUsuario,
        c.saldo,
        c.montoRecibido,
        v.idUsuario,
        v.idCliente,
        u.nombre as usuario,
        cl.nombre as cliente,
        cl.ci_ruc,
        cl.telefono1,
        cl.direccion,
        TIMESTAMPDIFF(DAY, c.fechaVencimiento, c.fechaPago) AS diasAtrasados,
        TIMESTAMPDIFF(DAY, c.fechaVencimiento, c.fechaPago) * (c.monto * 0.0011) as interesMoratorio,
        TIMESTAMPDIFF(DAY, c.fechaVencimiento, curdate()) AS diasAtrasadosActual,
        TIMESTAMPDIFF(DAY, c.fechaVencimiento, curdate()) * (c.monto * 0.0011) as interesMoratorioActual



        FROM cuotas c
        INNER JOIN ventas v ON c.idVenta = v.idVenta
        INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
        INNER JOIN clientes cl ON v.idCliente = cl.idCliente 
        WHERE v.idCliente = $codCliente and v.status != 3");


        $result_detalle = mysqli_num_rows($query_cuotas);
        $result_producto = mysqli_num_rows($query_productos);

        ob_start();
        include(dirname('__FILE__') . '/cuenta.php');
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
