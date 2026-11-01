<?php
session_start();
include "../conexion.php";

$busqueda = '';
$fecha_de = '';
$fecha_a  = '';

if (isset($_REQUEST['busqueda']) && $_REQUEST['busqueda'] == '') {
    header("location: ventas_por_mes.php");
}

if (isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])) {
    if ($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == '') {
        header("location: ventas_por_mes.php");
    }
}



if (!empty($_REQUEST['busqueda'])) {
    if (!is_numeric($_REQUEST['busqueda'])) {
        header("location: ventas_por_mes.php");
    }
    $busqueda = strtolower($_REQUEST['busqueda']);
    $where    = "idVenta = $busqueda AND status != 3";
    $buscar   = "busqueda = $busqueda";
}

if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a  = $_REQUEST['fecha_a'];

    $buscar = '';

    if ($fecha_de > $fecha_a) {
        header('location: ventas_por_mes.php');
    } elseif ($fecha_de == $fecha_a) {
        $where = "fecha LIKE '$fecha_de%' AND status != 3";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    } else {
        $f_de = $fecha_de . ' 00:00:00';
        $f_a = $fecha_a . ' 23:59:59';
        $where = "fecha BETWEEN '$f_de' AND '$f_a' AND status != 3";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Ventas por mes</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Ventas por mes</h1>


        <!-- <form action="buscar_ventas_por_ventas.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura" value="<?php echo $busqueda; ?> ">
            <input type="submit" value="Buscar" class="btn_search">
        </form> -->

        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_ventas_por_mes.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>Mes</th>
                <th>Total</th>

            </tr>
            <?php
            //paginacion
            // $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM ventas WHERE $where");
            // $result_register = mysqli_fetch_array($query_register);
            //$total_registro = $result_register['total_registro'];
            //$por_pagina = 10;
            //if (empty($_GET['pagina'])) {
            //    $pagina = 1;
            //} else {
            //    $pagina = $_GET['pagina'];
            //}
            //$desde = ($pagina - 1) * $por_pagina;
            //$total_paginas = ceil($total_registro / $por_pagina);

            $query = mysqli_query($conection, "SET lc_time_names = 'es_ES'");
            $query = mysqli_query($conection, "SELECT DATE_FORMAT(fecha, '%m - %M') AS mes,
            SUM(totalVenta) as total
            FROM ventas
            WHERE $where
            GROUP BY DATE_FORMAT(fecha, '%m - %M')
            ORDER BY DATE_FORMAT(fecha, '%m - %M')");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $totalVentas = $totalVentas + $data['total'];
                    // if ($data['status'] == 1) {
                    //     $status = '<span class="sinPago">Sin Pago</span>';
                    // } elseif ($data['status'] == 2) {
                    //     $status = '<span class="pagada">Pagado</span>';
                    // } else {
                    //     $status = '<span class="anulada">Anulada</span>';
                    // }

            ?>
                    <tr>
                        <td><?php echo strtoupper($data['mes']); ?></td>
                        <td>Gs. <?php echo number_format($data['total'], 0, ",", "."); ?></td>
                        <td><?php echo $data['fecha']; ?></td>
                    </tr>
            <?php
                }
            }
            ?>
            <tr style="font-size: 20px;">
                <td>Total:</td>
                <td>Gs. <?php echo number_format($totalVentas, 0, ",", "."); ?></td>
            </tr>
        </table>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>