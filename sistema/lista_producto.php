<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Productos</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="fas fa-cube"></i> Lista de Productos</h1>
        <a href="registro_producto.php" class="btn_new"><i class="fas fa-plus"></i> Crear Producto</a>

        <form action="buscar_producto.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Referencia</th>
                <th>Cod. Barra</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>
                    <?php
                    $query_categorias = mysqli_query($conection, "SELECT idCategoria, nombre FROM categorias WHERE status = 1 ORDER BY nombre ASC");
                    $result_categorias = mysqli_num_rows($query_categorias);
                    ?>
                    <select name="categorias" id="search_categorias">
                        <option value="" selected>CATEGORIAS</option>
                        <?php if ($result_categorias > 0) {
                            while ($categorias = mysqli_fetch_array($query_categorias)) {
                        ?>
                                <option value="<?php echo $categorias['idCategoria']; ?>"><?php echo $categorias['nombre']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </th>
                <?php
                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                ?>
                    <th>Precio Costo</th>
                <?php
                }
                ?>
                <th>Precio Venta</th>
                <th>Precio Venta 2</th>
                <th>Precio Venta 3</th>
                <th>Precio Venta 4</th>
                <th>Existencia</th>
                <th>
                    <?php
                    $query_proveedor = mysqli_query($conection, "SELECT idProveedor, nombre FROM proveedores WHERE status = 1 ORDER BY nombre ASC");
                    $result_proveedor = mysqli_num_rows($query_proveedor);
                    ?>
                    <select name="proveedor" id="search_proveedor">
                        <option value="" selected>PROVEEDOR</option>
                        <?php if ($result_proveedor > 0) {
                            while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                        ?>
                                <option value="<?php echo $proveedor['idProveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
                        <?php
                            }
                        }
                        ?>

                    </select>
                </th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM productos WHERE status = 1");
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


            $query = mysqli_query($conection, "SELECT p.idProducto, p.referencia, p.nombre, p.precioCosto, p.precioVenta, precioVenta2, precioVenta3, precioVenta4, p.existencia,p.codigoDeBarra, pr.nombre as nombreProveedor, p.foto, m.nombre as nombreMarca, c.nombre as nombreCategoria FROM productos p LEFT JOIN proveedores pr on(p.idProveedor = pr.idProveedor) 
                             LEFT JOIN marcas m ON (p.idMarca = m.idMarca)
                             LEFT JOIN categorias c ON (p.idCategoria = c.idCategoria)
                             WHERE p.status = 1 ORDER BY p.idProducto DESC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {

                while ($data = mysqli_fetch_array($query)) {
                    if ($data['foto'] != 'img_producto.jpg') {
                        $foto = 'img/uploads/' . $data['foto'];
                    } else {
                        $foto = 'img/' . $data['foto'];
                    }
            ?>
                    <tr class="row<?php echo $data['idProducto']; ?>">
                        <td><?php echo $data['idProducto']; ?></td>
                        <td><?php echo $data['referencia']; ?></td>
                        <td><?php echo $data['codigoDeBarra']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['nombreMarca']; ?></td>
                        <td><?php echo $data['nombreCategoria']; ?></td>
                        <?php
                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                ?>
                        <td class="celPrecio"><?php echo number_format($data['precioCosto'], 0, ",", "."); ?></td>
                        <?php
                }
                ?>
                        <td><?php echo number_format($data['precioVenta'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['precioVenta2'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['precioVenta3'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['precioVenta4'], 0, ",", "."); ?></td>
                        <td class="celExistencia"><?php echo $data['existencia']; ?></td>
                        <td><?php echo $data['nombreProveedor']; ?></td>
                        <td class="imgProducto"> <img src="<?php echo $foto; ?>" alt=""> </td>

                        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                            <td>
                                <!-- <a href="#" product="<?php echo $data['idProducto']; ?>" class="link_add add_product"><i class="fas fa-plus"></i> Agregar</a>

                                | -->
                                <a href="editar_producto.php?id=<?php echo $data['idProducto']; ?>" class="link_edit"><i class="far fa-edit"></i> Editar</a>

                                |
                                <a href="eliminar_confirmar_producto.php?id=<?php echo $data['idProducto']; ?>" class="link_delete"><i class="far fa-trash-alt"></i> Eliminar</a>
                            </td>
                        <?php } ?>
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