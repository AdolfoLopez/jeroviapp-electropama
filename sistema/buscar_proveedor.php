<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Clientes</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("Location: lista_proveedor.php");
            mysqli_close($conection);
        }

        ?>
        <h1><i class="far fa-building"></i> Lista de Clientes</h1>
        <a href="registro_proveedor.php" class="btn_new"><i class="far fa-plus"></i> Crear Proveedor</a>

        <form action="buscar_proveedor.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Teléfono 2</th>
                <th>Celular</th>
                <th>Celular 2</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php

            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM proveedores WHERE (idProveedor LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR contacto LIKE '%$busqueda%') AND status = 1");

            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];
            $por_pagina = 3;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE 
                                            (idProveedor LIKE '%$busqueda%' OR
                                            nombre LIKE '%$busqueda%' OR
                                            contacto LIKE '%$busqueda%' )
                                            AND
                                            status = 1 ORDER BY idProveedor ASC LIMIT $desde,$por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    $formato = 'Y-m-d H:i:s';
                    $fecha = DateTime::createFromFormat($formato, $data['date_add']);
            ?>
                    <tr>
                        <td><?php echo $data['idProveedor']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['contacto']; ?></td>
                        <td><?php echo $data['direccion']; ?></td>
                        <td><?php echo $data['telefono']; ?></td>
                        <td><?php echo $data['telefono2']; ?></td>
                        <td><?php echo $data['celular1']; ?></td>
                        <td><?php echo $data['celular2']; ?></td>
                        <td><?php echo $fecha->format('d-m-Y'); ?></td>
                        <td>
                            <a href="editar_proveedor.php?id=<?php echo $data['idProveedor']; ?>" class="link_edit">Editar</a>

                            |
                            <a href="eliminar_confirmar_proveedor.php?id=<?php echo $data['idProveedor']; ?>" class="link_delete">Eliminar</a>

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
                    <li>
                        <a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|< </a>
                    </li>
                    <li>
                        <a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>">
                            << </a>
                    </li>
                <?php
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
                    }
                }
                if ($pagina != $total_paginas) {
                ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>> </a></li>
                    <li><a href="?pagina=<?php echo $total_paginas ?>&busqueda=<?php echo $busqueda; ?>">>| </a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>