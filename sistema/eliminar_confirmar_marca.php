<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    if (empty($_POST['idMarca'])) {
        header("Location: lista_marca.php");
        mysqli_close($conection);
    }
    $idMarca = $_POST['idMarca'];
    $query_delete = mysqli_query($conection, "UPDATE marcas SET status = 0 WHERE idMarca = $idMarca");
    mysqli_close($conection);
    if ($query_delete) {
        header("Location: lista_marca.php");
        mysqli_close($conection);
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_marca.php");
} else {

    $idMarca = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM marcas WHERE idMarca = $idMarca ");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre  = $data['nombre'];
        }
    } else {
        header("Location: lista_marca.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Marca</title>
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
                <input type="hidden" name="idMarca" value="<?php echo $idMarca; ?> ">
                <a href="lista_marca.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn_ok">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>