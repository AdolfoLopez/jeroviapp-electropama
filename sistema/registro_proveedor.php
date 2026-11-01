<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['contacto']) || empty($_POST['ruc']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $nombre    = $_POST['nombre'];
        $ruc    = $_POST['ruc'];
        $contacto  = $_POST['contacto'];
        $telefono  = $_POST['telefono'];
        $telefono2  = $_POST['telefono2'];
        $celular  = $_POST['celular'];
        $celular2  = $_POST['celular2'];
        $direccion = $_POST['direccion'];
        $idUsuario = $_SESSION['idUser'];

        $queryVerificacion = mysqli_query($conection, "SELECT * FROM proveedores WHERE nombre = '$nombre'");
        $resultQueryVerificacion = mysqli_num_rows($queryVerificacion);
        if ($resultQueryVerificacion > 0) {
            $alert = '<p class="mgs_error">EL PROVEEDOR YA EXISTE</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO proveedores(nombre,ruc,telefono,direccion,contacto,idUsuario,telefono2,celular1,celular2) VALUES('$nombre','$ruc','$telefono','$direccion','$contacto','$idUsuario','$telefono2','$celular','$celular2')");

            if ($query_insert) {
                $alert = '<p class="mgs_save">Proveedor creado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al crear el proveedor</p>';
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
    <title>Registro Proveedor</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="far fa-building"></i> Registro de Proveedor</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <label for="nombre">Nombre Proveedor*</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del Proveedor">

                <label for="ruc">RUC*</label>
                <input type="text" name="ruc" id="ruc" placeholder="RUC">

                <label for="contacto">Nombre Contacto*</label>
                <input type="text" name="contacto" id="contacto" placeholder="Contacto del proveedor">

                <label for="direccion">Dirección*</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección">

                <label for="telefono">Telefono*</label>
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono">

                <label for="telefono2">Telefono 2</label>
                <input type="text" name="telefono2" id="telefono2" placeholder="telefono 2">

                <label for="celular">Celular</label>
                <input type="text" name="celular" id="celular" placeholder="Celular 1">

                <label for="celular2">Celular 2</label>
                <input type="text" name="celular2" id="celular2" placeholder="celular 2">

                <!-- <input type="submit" value="Guardar Clientes" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Proveedor</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>