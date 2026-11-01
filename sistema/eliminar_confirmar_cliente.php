<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {
    if (empty($_POST['idCliente'])) {
        header("Location: lista_cliente.php");
        mysqli_close($conection);
    }
    $idCliente = $_POST['idCliente'];
    $query_delete = mysqli_query($conection, "UPDATE clientes SET status = 0 WHERE idCliente = $idCliente");
    mysqli_close($conection);
    if ($query_delete) {
        header("Location: lista_cliente.php");
        mysqli_close($conection);
    } else {
        echo "Error al eliminar";
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_cliente.php");
} else {

    $idCliente = $_REQUEST['id'];
    $query = mysqli_query($conection, "SELECT * FROM clientes
                                             WHERE idCliente = $idCliente ");
    mysqli_close($conection);
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $ci_ruc  = $data['ci_ruc'];
            $nombre  = $data['nombre'];
        }
    } else {
        header("Location: lista_cliente.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Eliminar Cliente</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="data_delete">
            <h2>¿Estás seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>RUC - C.I: <span><?php echo $ci_ruc; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="idCliente" value="<?php echo $idCliente; ?> ">
                <a href="lista_cliente.php" class="btn_cancel">Cancelar</a>
                <input type="submit" value="Aceptar" class="btn_ok">
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>