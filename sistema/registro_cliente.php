<?php
session_start();
// if ($_SESSION['rol'] != 1) {
//     header("Location: ./");
// }

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['ruc_ci']) || empty($_POST['nombre']) || empty($_POST['direccion'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $ruc_ci = $_POST['ruc_ci'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $celular1 = $_POST['celular1'];
        $celular2 = $_POST['celular2'];
        $telefono1 = $_POST['telefono1'];
        $telefono2 = $_POST['telefono2'];
        $referencia1 = $_POST['referencia1'];
        $referencia2 = $_POST['referencia2'];
        $referencia3 = $_POST['referencia3'];
        $idUsuario = $_SESSION['idUser'];

        $result = 0;
        $query_verificacion = mysqli_query($conection, "SELECT * FROM clientes WHERE ci_ruc = '$ruc_ci' ");
        $result = mysqli_fetch_array($query_verificacion);
        if ($result > 0) {
            $alert = '<p class="msg_error">El número de RUC o CI ya existe</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO clientes(ci_ruc,nombre,direccion,celular1,celular2,telefono1,telefono2,referencia1,referencia2,referencia3,idUsuario) VALUES('$ruc_ci','$nombre','$direccion','$celular1','$celular2','$telefono1','$telefono2','$referencia1','$referencia2','$referencia3','$idUsuario')");

            if ($query_insert) {
                $alert = '<p class="mgs_save">Cliente creado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al crear el cliente</p>';
            }
        }
    }
    mysqli_close($conection);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Registro Clientes</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Registro de clientes</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <label for="ruc_ci">RUC - C.I *</label>
                <input type="text" name="ruc_ci" id="ruc_ci" placeholder="RUC y/o C. de Identidad">

                <label for="nombre">Razón Social *</label>
                <input type="text" name="nombre" id="nombre" placesholder="Razón Social o Nombre completo">

                <label for="direccion">Dirección *</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección">

                <label for="celular1">Celular 1</label>
                <input type="text" name="celular1" id="celular1" placeholder="Celular 1">

                <label for="celular2">Celular 2</label>
                <input type="text" name="celular2" id="celular2" placeholder="Celular 2">

                <label for="telefono1">Teléfono 1</label>
                <input type="text" id="telefono1" name="telefono1" placeholder="Teléfono 1">

                <label for="telefono2">Teléfono 2</label>
                <input type="text" id="telefono2" name="telefono2" placeholder="Teléfono 2">

                <!-- <label for="referencia1">Referencia 1</label>
                <input type="text" id="referencia1" name="referencia1" placeholder="Referencia 1">

                <label for="referencia2">Referencia 2</label>
                <input type="text" id="referencia2" name="referencia2" placeholder="Referencia 2">

                <label for="referencia3">Referencia 3</label>
                <input type="text" id="referencia3" name="referencia3" placeholder="Referencia 3"> -->

                <input type="submit" value="Guardar Clientes" class="btn_save">
            </form>
        </div>



    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>