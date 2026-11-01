<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    if (empty($_POST['idObra'])) {
        header("Location: lista_obra.php");
        mysqli_close($conection);
    }
    $idObra = $_POST['idObra'];
    $query_delete = mysqli_query($conection, "UPDATE OBRA SET STATUS = 0 WHERE ID_OBRA = $idObra");
    mysqli_close($conection);
    if ($query_delete) {
        header("Location: lista_obra.php");
        mysqli_close($conection);
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_obra.php");
} else {

    $idObra = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM OBRA WHERE ID_OBRA = $idObra ");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre  = $data['NOMBRE'];
        }
    } else {
        header("Location: lista_obra.php");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Obra</title>
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
                <input type="hidden" name="idObra" value="<?php echo $idObra; ?> ">
                <a href="lista_obra.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn_ok">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>