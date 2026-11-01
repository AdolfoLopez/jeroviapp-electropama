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
    <title>Lista de Categorías</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("Location: lista_categoria.php");
            mysqli_close($conection);
        }

        ?>
        <h1><i class="far fa-building"></i> Lista de Categorias</h1>
        <a href="registro_categoria.php" class="btn_new"><i class="far fa-plus"></i> Crear Categoria</a>

        <form action="buscar_categoria.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            <?php

            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM categorias WHERE (idCategoria LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%') AND status = 1");

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


            $query = mysqli_query($conection, "SELECT * FROM categorias WHERE 
                                            (idCategoria LIKE '%$busqueda%' OR
                                            nombre LIKE '%$busqueda%')
                                            AND
                                            status = 1 ORDER BY idCategoria ASC LIMIT $desde,$por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['idCategoria']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td>
                            <a href="editar_categoria.php?id=<?php echo $data['idCategoria']; ?>" class="link_edit">Editar</a>
                            |
                            <a href="eliminar_confirmar_categoria.php?id=<?php echo $data['idCategoria']; ?>" class="link_delete">Eliminar</a>
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