<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Compras</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1> <i class="far fa-newspaper"></i> Lista de Compras</h1>
        <a href="nueva_compra.php" class="btn_new"><i class="fas fa-plus"></i> Nueva Compra</a>

        <!-- <form action="buscar_compra.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. Compra">
            <input type="submit" value="Buscar" class="btn_search">
        </form> -->
        <!-- <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_compra.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div> -->

        <table>
            <tr>
                <th>No.</th>
                <th>Fecha / Hora</th>
                <th>Proveedor</th>
                <th>Comprador</th>
                <!-- <th>Estado</th> -->
                <th class="textright">Total compra</th>
                <th class="textright">Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM compras WHERE status != 10");
            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];
            $por_pagina = 40;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT c.idCompra, c.fecha,c.totalCompra, c.idProveedor,c.status,pr.nombre as proveedor, u.nombre as comprador FROM compras c INNER JOIN proveedores pr ON c.idProveedor = pr.idProveedor INNER JOIN usuarios u ON c.idUsuario = u.idUsuario WHERE c.status != 10 ORDER BY c.fecha DESC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['status'] == 1) {
                        $status = '<span class="sinPago">Sin Pago</span>';
                    } elseif ($data['status'] == 2) {
                        $status = '<span class="pagada">Pagado</span>';
                    } else {
                        $status = '<span class="anulada">Anulada</span>';
                    }
            ?>
                    <tr id="row_<?php echo $data['idCompra']; ?>">
                        <td><?php echo $data['idCompra']; ?></td>
                        <td><?php echo $data['fecha']; ?></td>
                        <td><?php echo $data['proveedor']; ?></td>
                        <td><?php echo $data['comprador']; ?></td>
                        <!-- <td class="estado"><?php echo $status; ?></td> -->
                        <td class="textright totalfactura"><span>Gs.</span><?php echo number_format($data['totalCompra'], 0, ",", "."); ?></td>

                        <td>
                            <div class="div_acciones">
                                <!-- <div>
                                    <button class="btn_view view_factura" type="button" cl="<?php echo $data['idProveedor'] ?>" f="<?php echo $data['idCompra'] ?>"><i class="fas fa-eye"></i></button>
                                </div> -->

                                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                    if ($data['status'] == 1) {
                                ?>
                                        <div class="div_factura">
                                            <button class="btn_anular anular_factura" fac="<?php echo $data['idCompra'] ?>"><i class="fas fa-ban"></i></button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="div_factura">
                                            <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
                                        </div>
                                <?php }
                                } ?>

                                <button type="button" class="imprimir_factura_compra" fac="<?php echo $data['idCompra'] ?>" prov="<?php echo $data['idProveedor'] ?>"><i class="fas fa-print iconSearch"></i></button>

                            </div>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
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