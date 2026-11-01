<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("Location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    if ($_POST['idUsuario'] == 1) {
        header("Location: lista_usuario.php");
        mysqli_close($conection);
        exit;
    }

    $idUsuario = $_POST['idUsuario'];

    //$query_delete = mysqli_query($conection,"DELETE FROM usuarios WHERE idUsuario = $idUsuario ");
    $query_delete = mysqli_query($conection, "UPDATE usuarios SET status = 0 WHERE idUsuario = $idUsuario");
    mysqli_close($conection);
    if ($query_delete) {
        header("Location: lista_usuario.php");
        mysqli_close($conection);
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
    header("Location: lista_usuario.php");
} else {

    $idUsuario = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT u.nombre,u.usuario,r.rol FROM usuarios u
                                             INNER JOIN rol r
                                             ON u.idRol = r.idRol
                                             WHERE u.idUsuario = $idUsuario ");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre  = $data['nombre'];
            $usuario = $data['usuario'];
            $rol     = $data['rol'];
        }
    } else {
        header("Location: lista_usuario.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Usuario</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="data_delete">
            <i class="fas fa-user-times fa-7x" style="color:red"></i>
            <br />
            <br>
            <h2>¿Estás seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Usuario: <span><?php echo $usuario; ?></span></p>
            <p>Tipo de Usuario: <span><?php echo $rol; ?></span></p>

            <form action="" method="POST">
                <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?> ">
                <a href="lista_usuario.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
                <!-- <input type="submit" value="Aceptar" class="btn_ok"> -->
                <button type="submit" class="btn_ok"><i class="far fa-trash-alt"></i> Eliminar</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>