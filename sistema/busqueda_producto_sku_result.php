<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Productos</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <?php
        $busqueda = '';
       $total = '';

        if (empty($_REQUEST['busqueda'])) {
            header("Location: busqueda_compra_sku.php");
        }

        if (!empty($_REQUEST['busqueda'])) {
            $busqueda = strtolower($_REQUEST['busqueda']);
            $where = "(p.referencia LIKE '%$busqueda%') AND p.status = 1";
            $buscar = 'busqueda=' . $busqueda;
        }

        ?>

        <h1><i class="fas fa-cube"></i> Busqueda cant. comprada por Referencia Producto</h1>

        <form action="busqueda_producto_sku_result.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php $busqueda ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>No. Compra</th>
                <th>Fecha / Hora</th>
                <th>Referencia</th>
                <th>Nombre Producto</th>
                <th>Proveedor</th>
                <th>Cant. Comprada</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro 
                                                        FROM productos as p WHERE $where ");

            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];

            $por_pagina = 25;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT 
            c.idCompra,
            date_format(c.fecha, '%d-%m-%Y') as fecha,
            p.referencia,
            p.nombre as nombreProducto,
            pro.nombre as nombreProveedor,
            dc.cant
            from compras c
            INNER JOIN detallesCompra dc ON (dc.noCompra = c.idCompra)
            INNER JOIN productos p ON (p.idProducto = dc.idProducto)
            INNER JOIN proveedores pro ON (pro.idProveedor = c.idProveedor)
            WHERE $where
            ORDER BY p.idProducto DESC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $total = ((int)$total + (int)$data['cant']);
            ?>
                    <tr>
                        <td><?php echo $data['idCompra']; ?></td>
                        <td><?php echo $data['fecha']; ?></td>
                        <td><?php echo $data['referencia']; ?></td>
                        <td><?php echo $data['nombreProducto']; ?></td>
                        <td><?php echo $data['nombreProveedor']; ?></td>
                        <td><?php echo $data['cant']; ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
        <h3>Cantidad Comprada: <?php echo (int)$total; ?></h3>
        <?php
        if ($total_paginas != 0) {
        ?>
            <div class="paginador">
                <ul>
                    <?php
                    if ($pagina != 1) {
                    ?>
                        <li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar; ?>">|< </a>
                        </li>
                        <li><a href="?pagina=<?php echo $pagina - 1; ?>&<?php echo $buscar; ?>">
                                << </a>
                        </li>
                    <?php
                    }
                    for ($i = 1; $i <= $total_paginas; $i++) {
                        if ($i == $pagina) {
                            echo '<li class="pageSelected">' . $i . '</li>';
                        } else {
                            echo '<li><a href="?pagina=' . $i . '&' . $buscar . '">' . $i . '</a></li>';
                        }
                    }
                    if ($pagina != $total_paginas) {
                    ?>
                        <li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar; ?>">>> </a></li>
                        <li><a href="?pagina=<?php echo $total_paginas ?>&<?php echo $buscar; ?>">>| </a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        <?php  } ?>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>