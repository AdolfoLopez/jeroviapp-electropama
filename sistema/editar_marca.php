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
        $idMarca   = $_POST['id'];
        $nombre    = $_POST['nombre'];
        $idUsuario = $_SESSION['idUser'];

        $query_update = mysqli_query($conection, "UPDATE marcas SET 
                                                    nombre = '$nombre',
                                                    idUsuario = '$idUsuario'
                                                    WHERE idMarca = $idMarca
                                                    ");

        if ($query_update) {
            $alert = '<p class="mgs_save">La marca fue modificada correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al modificar la marca</p>';
        }
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_marca.php");
} else {
    $idMarca = $_REQUEST['id'];
    if (!is_numeric($idMarca)) {
        header("Location: lista_marca.php");
    }

    $query = mysqli_query($conection, "SELECT * FROM marcas WHERE idMarca = $idMarca AND status = 1");
    $result_producto = mysqli_num_rows($query);

    if ($result_producto > 0) {
        $data = mysqli_fetch_assoc($query);
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
    <title>Modificar Marca</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Modificar Marca</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['idMarca']; ?>">
                <label for="nombre">Marca</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre de la marca" value="<?php echo $data['nombre']; ?>">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Modificación</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>