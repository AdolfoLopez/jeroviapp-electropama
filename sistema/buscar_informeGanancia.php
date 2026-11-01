<?php
session_start();
include "../conexion.php";

//$busqueda = '';
$fecha_de = '';
$fecha_a  = '';

if (isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])) {
    if ($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == '') {
        header("location: informeGanancia.php");
    }
}

if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a  = $_REQUEST['fecha_a'];

    // $buscar = '';

    if ($fecha_de > $fecha_a) {
        header('location: informeGanancia.php');
    } elseif ($fecha_de == $fecha_a) {
        $where = "fecha LIKE '$fecha_de%'";
        // $where2 = "fechaPago LIKE '$fecha_de%'";
        // $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    } else {
        $f_de = $fecha_de . ' 00:00:00';
        $f_a = $fecha_a . ' 23:59:59';
        $where = "(fecha BETWEEN '$f_de' AND '$f_a')";
        // $where2 = "(fechaPago BETWEEN '$f_de' AND '$f_a')";
        // $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Informe Ganancia</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Informes de Ganancias</h1>


        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_informeGanancia.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>Total Ventas</th>
                <th>Total Compras</th>
                <th>Total Gastos</th>
                <th>Ganancias</th>
                
            </tr>
            <?php
            
            $queryTotalVentas = mysqli_query($conection,
            "SELECT SUM(totalVenta) as sumaVentas
            FROM ventas
            WHERE $where
            AND status = 1;");
            $resultTotalVentas = mysqli_num_rows($queryTotalVentas);
            $dataTotalVentas = mysqli_fetch_array($queryTotalVentas);
            // mysqli_close($conection);

            $queryTotalGastos = mysqli_query($conection,
            "SELECT SUM(totalGasto) as sumaGastos 
            FROM gastos
            WHERE $where
            AND status = 1;");
            $resultTotalGastos = mysqli_num_rows($queryTotalGastos);
            $dataTotalGastos = mysqli_fetch_array($queryTotalGastos);
            // mysqli_close($conection);

            $queryTotalCompras = mysqli_query($conection,
            "SELECT SUM(totalCompra) as sumaCompra 
            FROM compras
            WHERE $where
            AND status = 1;");
            $resultTotalCompras = mysqli_num_rows($queryTotalCompras);
            $dataTotalCompras = mysqli_fetch_array($queryTotalCompras);
            mysqli_close($conection);
             ?>
                 <tr>
                     <td><?php echo number_format($dataTotalVentas['sumaVentas'], 0, ",", "."); ?></td>
                     <td><?php echo number_format($dataTotalCompras['sumaCompra'], 0, ",", "."); ?></td>
                     <td><?php echo number_format($dataTotalGastos['sumaGastos'], 0, ",", "."); ?></td>
                     <td><?php echo number_format(($dataTotalVentas['sumaVentas'] - $dataTotalCompras['sumaCompra']  - $dataTotalGastos['sumaGastos']), 0, ",", "."); ?></td>
                     <!-- <td><?php echo $data['clientes']; ?></td>
                     <td><?php echo $data['fechas']; ?></td>
                     <td><?php echo 'Gs. ' . number_format($entrada, 0, ",", "."); ?></td>
                     <td><?php echo 'Gs. ' . number_format($salida, 0, ",", "."); ?></td> -->
                 </tr>
          
            
        </table>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html> 