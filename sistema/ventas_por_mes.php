<?php
session_start();
include "../conexion.php";
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
        <h1><i class="far fa-newspaper"></i> Ventas por mes </h1>
        <a href="nueva_venta.php" class="btn_new">Nueva Ventas</a>

        <!-- <form action="buscar_ventas_por_mes.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
            <input type="submit" value="Buscar" class="btn_search">
        </form> -->

        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_ventas_por_mes.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
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
            //$query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM ventas WHERE status != 10");
            //$result_register = mysqli_fetch_array($query_register);
            //$total_registro = $result_register['total_registro'];
            //$por_pagina = 10;
            //if (empty($_GET['pagina'])) {
            //    $pagina = 1;
            //} else {
            //    $pagina = $_GET['pagina'];
            //}
            //$desde = ($pagina - 1) * $por_pagina;
            //$total_paginas = ceil($total_registro / $por_pagina);
            $totalVentas = 0;
            $query = mysqli_query($conection, "SET lc_time_names = 'es_ES'");
            $query = mysqli_query($conection, "SELECT DATE_FORMAT(fecha, '%m - %M') AS mes,
            SUM(totalVenta) as total
            FROM ventas
            WHERE status != 3
            GROUP BY DATE_FORMAT(fecha, '%m - %M')
            ORDER BY DATE_FORMAT(fecha, '%m - %M')
            ");


            //$queryTotalVenta = mysqli_query($conection, "SELECT sum(totalVenta) as total FROM ventas LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $totalVentas = $totalVentas + $data['total'];
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