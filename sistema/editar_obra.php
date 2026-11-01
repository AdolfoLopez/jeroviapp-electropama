<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre'])) {
        $alert = '<p class="mgs_error">El campo del nombre es obligatorio</p>';
    } else {
        $idObra   = $_POST['id'];
        $nombre    = $_POST['nombre'];

        $query_update = mysqli_query($conection, "UPDATE OBRA SET NOMBRE = '$nombre' WHERE ID_OBRA = $idObra ");

        if ($query_update) {
            $alert = '<p class="mgs_save">La obra fue modificada correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al modificar la obra</p>';
        }
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_obra.php");
} else {
    $idObra = $_REQUEST['id'];
    if (!is_numeric($idObra)) {
        header("Location: lista_obra.php");
    }

    $query = mysqli_query($conection, "SELECT * FROM OBRA WHERE ID_OBRA = $idObra AND STATUS = 1");
    $result_producto = mysqli_num_rows($query);

    if ($result_producto > 0) {
        $data = mysqli_fetch_assoc($query);
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
    <title>Modificar Obra</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Modificar Obra</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['ID_OBRA']; ?>">
                <label for="nombre">Nombre de la Obra</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre de la obra" value="<?php echo $data['NOMBRE']; ?>">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Modificación</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>