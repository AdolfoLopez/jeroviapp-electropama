<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Obra</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <?php
        $busqueda = strtolower($_REQUEST['busqueda']);
        if (empty($busqueda)) {
            header("Location: lista_obra.php");
            mysqli_close($conection);
        }

        ?>
        <h1><i class="far fa-building"></i> Lista de Obra</h1>
        <a href="registro_obra.php" class="btn_new"><i class="far fa-plus"></i> Crear Obra</a>

        <form action="buscar_obra.php" method="GET" class="form_search">
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

            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM OBRA WHERE (ID_OBRA LIKE '%$busqueda%' OR NOMBRE LIKE '%$busqueda%') AND STATUS = 1");

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


            $query = mysqli_query($conection, "SELECT * FROM OBRA WHERE 
                                            (ID_OBRA LIKE '%$busqueda%' OR
                                            NOMBRE LIKE '%$busqueda%')
                                            AND
                                            STATUS = 1 ORDER BY ID_OBRA ASC LIMIT $desde,$por_pagina");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['ID_OBRA']; ?></td>
                        <td><?php echo $data['NOMBRE']; ?></td>
                        <td>
                            <a href="editar_obra.php?id=<?php echo $data['ID_OBRA']; ?>" class="link_edit">Editar</a>
                            |
                            <a href="eliminar_confirmar_obra.php?id=<?php echo $data['ID_OBRA']; ?>" class="link_delete">Eliminar</a>
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