<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("Location: ./");
}
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Usuarios</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="fas fa-users"></i> Lista de Usuarios</h1>
        <a href="registro_usuario.php" class="btn_new"><i class="fas fa-user-plus"></i> Crear Usuario</a>

        <form action="buscar_usuario.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <!-- <input type="submit" value="Buscar" class="btn_search"> -->
            <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM usuarios WHERE status = 1");
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


            $query = mysqli_query($conection, "SELECT u.idUsuario, u.nombre, u.correo, u.usuario, r.rol FROM usuarios u INNER JOIN rol r ON u.idRol = r.idRol WHERE status = 1 ORDER BY idUsuario ASC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['idUsuario']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['correo']; ?></td>
                        <td><?php echo $data['usuario']; ?></td>
                        <td><?php echo $data['rol']; ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $data['idUsuario']; ?>" class="link_edit"><i class="far fa-edit"></i> Editar</a>
                            <?php if ($data['idUsuario'] != 1) { ?>
                                |
                                <a href="eliminar_confirmar_usuario.php?id=<?php echo $data['idUsuario']; ?>" class="link_delete"><i class="far fa-trash-alt"></i>Eliminar</a>
                            <?php } ?>
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
                    <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fas fa-caret-left"></i> </a>
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
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-caret-right"></i></a></li>
                    <li><a href="?pagina=<?php echo $total_paginas ?>"><i class="fas fa-step-forward"></i></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>