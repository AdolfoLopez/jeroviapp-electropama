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
        $search_proveedor = '';
        $search_categoria = '';

        if (empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor']) && empty($_REQUEST['categorias'])) {
            header("Location: lista_producto.php");
        }

        if (!empty($_REQUEST['busqueda'])) {
            $busqueda = strtolower($_REQUEST['busqueda']);
            $where = "(p.idProducto LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR p.referencia LIKE '%$busqueda%') AND p.status = 1";
            $buscar = 'busqueda=' . $busqueda;
        }

        if (!empty($_REQUEST['proveedor'])) {
            $search_proveedor = $_REQUEST['proveedor'];
            $where = "p.idProveedor LIKE $search_proveedor AND p.status = 1";
            $buscar = 'proveedor=' . $search_proveedor;
        }

        if (!empty($_REQUEST['categorias'])) {
            $search_categoria = $_REQUEST['categorias'];
            $where = "p.idCategoria LIKE $search_categoria AND p.status = 1";
            $buscar = 'categorias=' . $search_categoria;
        }

        ?>

        <h1><i class="fas fa-cube"></i> Lista de Productos</h1>
        <a href="registro_producto.php" class="btn_new"><i class="fas fa-plus"></i> Crear Producto</a>

        <form action="buscar_producto.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php $busqueda ?>">
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
                    $pro = 0;
                    if (!empty($_REQUEST['proveedor'])) {
                        $pro = $_REQUEST['proveedor'];
                    }
                    $query_proveedor = mysqli_query($conection, "SELECT idProveedor, nombre FROM proveedores WHERE status = 1 ORDER BY nombre ASC");
                    $result_proveedor = mysqli_num_rows($query_proveedor);
                    ?>
                    <select name="proveedor" id="search_proveedor">
                        <option value="" selected>PROVEEDOR</option>
                        <?php if ($result_proveedor > 0) {
                            while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                                if ($pro == $proveedor['idProveedor']) {

                        ?>
                                    <option value="<?php echo $proveedor['idProveedor'];
                                                    ?>" selected><?php echo $proveedor['nombre']; ?></option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?php echo $proveedor['idProveedor'];
                                                    ?>"><?php echo $proveedor['nombre']; ?></option>
                        <?php
                                }
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


            // $query = mysqli_query($conection, "SELECT p.idProducto, p.nombre, p.precioCosto, p.precioVenta, p.existencia, pr.nombre as nombreProveedor, p.foto FROM productos p INNER JOIN proveedores pr on(p.idProveedor = pr.idProveedor) WHERE $where
            //             ORDER BY p.idProducto DESC LIMIT $desde,$por_pagina");

            $query = mysqli_query($conection, "SELECT p.idProducto, p.referencia, p.nombre, p.precioCosto, p.precioVenta, p.precioVenta2, p.precioVenta3, p.precioVenta4, p.existencia,p.codigoDeBarra, pr.nombre as nombreProveedor, p.foto, m.nombre as nombreMarca, c.nombre as nombreCategoria FROM productos p LEFT JOIN proveedores pr on(p.idProveedor = pr.idProveedor) 
            LEFT JOIN marcas m ON (p.idMarca = m.idMarca)
            LEFT JOIN categorias c ON (p.idCategoria = c.idCategoria)
            WHERE $where ORDER BY p.idProducto DESC LIMIT $desde,$por_pagina");

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
                    <tr>
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
                        <td><?php echo $data['existencia']; ?></td>
                        <td><?php echo $data['nombreProveedor']; ?></td>
                        <td class="imgProducto"> <img src="<?php echo $foto; ?>" alt=""> </td>

                        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                            <td>
                                <!-- <a href="agregar_producto.php?id=<?php echo $data['idProducto']; ?>" class="link_add"><i class="fas fa-plus"></i> Agregar</a> -->
                                |
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