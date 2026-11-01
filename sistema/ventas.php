<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Ventas</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Lista de Ventas</h1>
        <a href="nueva_venta.php" class="btn_new">Nueva Ventas</a>

        <form action="buscar_venta.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_venta.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>No. Venta</th>
                <th>Obra</th>

                <!-- <th>No. Factura Legal</th> -->
                <th>Fecha / Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <!-- <th>Estado</th> -->
                <th class="textright">Total Factura</th>
                <th class="textright">Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM ventas WHERE status != 10");
            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];
            $por_pagina = 10;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT v.idVenta, ob.nombre AS nombreObra, v.fecha, v.noFacturaLegal, v.totalVenta, v.idCliente, v.status, u.nombre as vendedor, cl.nombre as cliente FROM ventas v INNER JOIN usuarios u ON v.idUsuario = u.idUsuario INNER JOIN clientes cl ON v.idCliente = cl.idCliente LEFT JOIN OBRA ob ON(ob.ID_OBRA = v.ID_OBRA)   WHERE v.status != 10 ORDER BY v.fecha DESC LIMIT $desde,$por_pagina");

            $queryTotalVenta = mysqli_query($conection, "SELECT sum(totalVenta) as total FROM ventas LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr id="row_<?php echo $data['idVenta']; ?>">
                        <td><?php echo $data['idVenta']; ?></td>
                        <td><?php echo $data['nombreObra']; ?></td>
                        <!-- <td><?php echo $data['noFacturaLegal']; ?></td> -->
                        <td><?php echo $data['fecha']; ?></td>
                        <td><?php echo $data['cliente']; ?></td>
                        <td><?php echo $data['vendedor']; ?></td>
                        <!-- <td class="estado"><?php echo $status; ?></td> -->
                        <td class="textright totalfactura"><span>Gs.</span><?php echo number_format($data['totalVenta'], 0, ",", "."); ?></td>
                        <td>
                            <div class="div_acciones">
                                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                    if ($data['status'] == 1) {
                                ?>
                                        <div class="div_factura">
                                            <button class="btn_anular anular_facturaVenta" facventa="<?php echo $data['idVenta'] ?>"><i class="fas fa-ban"></i></button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="div_factura">
                                            <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
                                        </div>
                                <?php }
                                } ?>
                                <button class="imprimir_factura_venta" type="button" cl="<?php echo $data['idCliente'] ?>" f="<?php echo $data['idVenta'] ?>"></i>Imprimir Factura</button>- - -

                                <button class="imprimir_ticket_venta" type="button" cl2="<?php echo $data['idCliente'] ?>" f2="<?php echo $data['idVenta'] ?>"></i>Imprimir Ticket</button>
                            </div>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
        <!-- Total: <?php echo $queryTotalVenta; ?> -->
        <div class="paginador">
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
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>