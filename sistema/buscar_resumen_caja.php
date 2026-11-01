<?php
session_start();
include "../conexion.php";

//$busqueda = '';
$fecha_de = '';
$fecha_a  = '';

//if (isset($_REQUEST['busqueda']) && $_REQUEST['busqueda'] == '') {
//    header("location: ventas.php");
//}

if (isset($_REQUEST['fecha_de']) || isset($_REQUEST['fecha_a'])) {
    if ($_REQUEST['fecha_de'] == '' || $_REQUEST['fecha_a'] == '') {
        header("location: resumen_caja.php");
    }
}



//if (!empty($_REQUEST['busqueda'])) {
//    if (!is_numeric($_REQUEST['busqueda'])) {
//        header("location: ventas.php");
//    }
//    $busqueda = strtolower($_REQUEST['busqueda']);
//    $where    = "idVenta = $busqueda";
//    $buscar   = "busqueda = $busqueda";
//}

if (!empty($_REQUEST['fecha_de']) && !empty($_REQUEST['fecha_a'])) {
    $fecha_de = $_REQUEST['fecha_de'];
    $fecha_a  = $_REQUEST['fecha_a'];

    $buscar = '';

    if ($fecha_de > $fecha_a) {
        header('location: resumen_caja.php');
    } elseif ($fecha_de == $fecha_a) {
        $where = "fecha LIKE '$fecha_de%'";
        $where2 = "fechaPago LIKE '$fecha_de%'";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    } else {
        $f_de = $fecha_de . ' 00:00:00';
        $f_a = $fecha_a . ' 23:59:59';
        $where = "(fecha BETWEEN '$f_de' AND '$f_a')";
        $where2 = "(fechaPago BETWEEN '$f_de' AND '$f_a')";
        $buscar = "fecha_de=$fecha_de&fecha_a=$fecha_a";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Resumen de Caja</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Resumen de Caja</h1>

        <!-- <form action="buscar_venta.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
            <input type="submit" value="Buscar" class="btn_search">
        </form> -->

        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_resumen_caja.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" value="<?php echo $fecha_de; ?>" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" value="<?php echo $fecha_a; ?>" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>No. Documento</th>
                <th>Cliente / Proveedor</th>
                <th>Fecha</th>
                <th>Entrada</th>
                <th>Salida</th>
                <!-- <th>Estado</th>
                <th class="textright">Total Factura</th>
                <th class="textright">Acciones</th> -->
            </tr>
            <?php
            //paginacion
            // $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM ventas WHERE status != 10");
            // $result_register = mysqli_fetch_array($query_register);
            // $total_registro = $result_register['total_registro'];
            // $por_pagina = 10;
            // if (empty($_GET['pagina'])) {
            //     $pagina = 1;
            // } else {
            //     $pagina = $_GET['pagina'];
            // }
            //$desde = ($pagina - 1) * $por_pagina;
            //$total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "((SELECT 
            c.idVenta as documentos,
            c.montoRecibido as monto,
            c.fechaPago AS fechas,
            cl.nombre AS clientes 
            FROM cuotas c
            INNER JOIN ventas v ON (c.idVenta = v.idVenta)
            INNER JOIN clientes cl ON (v.idCliente = cl.idCliente)
            WHERE $where2)
            UNION
            (SELECT 
            g.noDocumento, 
            dg.monto * -1, 
            g.fecha, 
            dg.descripcion 
            FROM gastos g
            INNER JOIN detallesGasto dg ON (g.idGasto = dg.idGasto)
            WHERE $where
            ))");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            // if ($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
                $entrada = '';
                $salida = '';
                if ($data['monto'] >= 0) {
                    $entrada = $data['monto'];
                    $salida = 0;
                } else {
                    $salida = $data['monto'];
                    $entrada = 0;
                }

            ?>
                <tr>
                    <td><?php echo $data['documentos']; ?></td>
                    <td><?php echo $data['clientes']; ?></td>
                    <td><?php echo $data['fechas']; ?></td>
                    <td><?php echo 'Gs. ' . number_format($entrada, 0, ",", "."); ?></td>
                    <td><?php echo 'Gs. ' . number_format($salida, 0, ",", "."); ?></td>
                </tr>
            <?php

                $totalEntrada = $totalEntrada + $entrada;
                $totalSalida = $totalSalida + $salida;
            }
            // }
            ?>
            <tfoot style="border-top: 1px solid #000;">
                <tr style="font-size: 16px;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo 'Gs. ' .  number_format($totalEntrada, 0, ",", ".");  ?></td>
                    <td><?php echo 'Gs. ' .  number_format($totalSalida, 0, ",", "."); ?></td>

                </tr>
                <tr style=" font-size: 20px;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class=" textright">En caja:</td>
                    <td><?php echo 'Gs. ' . number_format(($totalEntrada + $totalSalida), 0, ",", "."); ?></td>
                </tr>
            </tfoot>
        </table>
        <!-- <div class=" paginador">
                    <ul>
                        <?php
                        if ($pagina != 1) {
                        ?>
                            <li><a href="?pagina=<?php echo 1; ?>">|< </a>
                            </li>
                            <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                                    << </a>
                            </li>
                        <?php
                        }
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            if ($i == $pagina) {
                                echo '<li class="pageSelected">' . $i . '</li>';
                            } else {
                                echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                        if ($pagina != $total_paginas) {
                        ?>
                            <li><a href="?pagina=<?php echo $pagina + 1; ?>">>> </a></li>
                            <li><a href="?pagina=<?php echo $total_paginas ?>">>| </a></li>
                        <?php
                        }
                        ?>
                    </ul>
                    </div> -->
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>