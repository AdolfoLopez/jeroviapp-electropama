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
        $idCategoria   = $_POST['id'];
        $nombre    = $_POST['nombre'];
        $idUsuario = $_SESSION['idUser'];

        $query_update = mysqli_query($conection, "UPDATE categorias SET 
                                                    nombre = '$nombre',
                                                    idUsuario = '$idUsuario'
                                                    WHERE idCategoria = $idCategoria
                                                    ");

        if ($query_update) {
            $alert = '<p class="mgs_save">La categoria fue modificada correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al modificar la categoria</p>';
        }
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_categoria.php");
} else {
    $idCategoria = $_REQUEST['id'];
    if (!is_numeric($idCategoria)) {
        header("Location: lista_categoria.php");
    }

    $query = mysqli_query($conection, "SELECT * FROM categorias WHERE idCategoria = $idCategoria AND status = 1");
    $result_producto = mysqli_num_rows($query);

    if ($result_producto > 0) {
        $data = mysqli_fetch_assoc($query);
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
    <title>Modificar categoria</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Modificar categoría</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['idCategoria']; ?>">
                <label for="nombre">Categoria</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre de la categoria" value="<?php echo $data['nombre']; ?>">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Modificación</button>
            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>