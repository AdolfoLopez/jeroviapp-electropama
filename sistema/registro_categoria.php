<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre'])) {
        $alert = '<p class="mgs_error">El campo de nombre es obligatorio</p>';
    } else {
        $nombre      = $_POST['nombre'];
        $idUsuario = $_SESSION['idUser'];

        $queryVerificacion = mysqli_query($conection, "SELECT * FROM categorias WHERE nombre = '$nombre'");
        $resultQueryVerificacion = mysqli_num_rows($queryVerificacion);
        if ($resultQueryVerificacion > 0) {
            $alert = '<p class="mgs_error">LA CATEGORIA YA EXISTE</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO categorias(nombre,idUsuario) VALUES('$nombre','$idUsuario')");

        if ($query_insert) {
            $alert = '<p class="mgs_save">Categoría creado correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al crear la categoría</p>';
        }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Registro de Categorías</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Registro de Categorías</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">

                <label for="nombre">Categoría *</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre de la categoría">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Categoría</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>