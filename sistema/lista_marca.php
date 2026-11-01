<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Marcas</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="fas fa-cube"></i> Lista de Marcas</h1>
        <a href="registro_marca.php" class="btn_new"><i class="fas fa-plus"></i> Crear Marca</a>

        <form action="buscar_marca.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM marcas WHERE status = 1");
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


            $query = mysqli_query($conection, "SELECT * FROM marcas WHERE status = 1 ORDER BY nombre ASC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['idMarca']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td>
                            <a href="editar_marca.php?id=<?php echo $data['idMarca']; ?>" class="link_edit">Editar</a>
                            |
                            <a href="eliminar_confirmar_marca.php?id=<?php echo $data['idMarca']; ?>" class="link_delete">Eliminar</a>
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