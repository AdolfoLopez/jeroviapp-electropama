<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    if (empty($_POST['idCategoria'])) {
        header("Location: lista_categoria.php");
        mysqli_close($conection);
    }
    $idCategoria = $_POST['idCategoria'];
    $query_delete = mysqli_query($conection, "UPDATE categorias SET status = 0 WHERE idCategoria = $idCategoria");
    mysqli_close($conection);
    if ($query_delete) {
        header("Location: lista_categoria.php");
        mysqli_close($conection);
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_categoria.php");
} else {

    $idCategoria = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM categorias WHERE idCategoria = $idCategoria ");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre  = $data['nombre'];
        }
    } else {
        header("Location: lista_categoria.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Categoria</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="data_delete">
            <i class="far fa-building fa-7x" style="color: #e66262;"></i>
            <br><br>
            <h2>¿Estás seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="idCategoria" value="<?php echo $idCategoria; ?> ">
                <a href="lista_categoria.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn_ok">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>