<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['ruc']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $alert = '<p class="mgs_error">Favor completar los campos obligatorios</p>';
    } else {
        $idProveedor = $_POST['id'];
        $nombre      = $_POST['nombre'];
        $ruc      = $_POST['ruc'];
        $direccion   = $_POST['direccion'];
        $telefono    = $_POST['telefono'];
        $telefono2    = $_POST['telefono2'];
        $celular    = $_POST['celular'];
        $celular2    = $_POST['celular2'];
        $contacto    = $_POST['contacto'];

        $sql_update = mysqli_query($conection, "UPDATE proveedores
                                                SET nombre = '$nombre', ruc = '$ruc', direccion = '$direccion', contacto = '$contacto', telefono = '$telefono', telefono2 = '$telefono2', celular1 = '$celular', celular2 = '$celular2'
                                                WHERE idProveedor = $idProveedor");

        if ($sql_update) {
            $alert = '<p class="mgs_save">Proveedor actualizado correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al actualizar el proveedor</p>';
        }
    }
}


//mostrar datos
if (empty($_REQUEST['id'])) {
    header('Location: lista_proveedor.php');
    mysqli_close($conection);
}
$idProveedor = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT * FROM proveedores WHERE idProveedor = $idProveedor and status = 1");
//mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header('Location: lista_proveedor.php');
} else {
    while ($data = mysqli_fetch_array($sql)) {
        $idProveedor = $data['idProveedor'];
        $nombre    = $data['nombre'];
        $ruc    = $data['ruc'];
        $contacto  = $data['contacto'];
        $telefono  = $data['telefono'];
        $telefono2 = $data['telefono2'];
        $celular1 = $data['celular1'];
        $celular2 = $data['celular2'];
        $direccion = $data['direccion'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar Proveedor</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="far fa-edit"></i> Actualizar Proveedor</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $idProveedor; ?>">

                <label for="nombre">Proveedor</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre del Proveedor" value="<?php echo $nombre; ?>">

                <label for="ruc">RUC</label>
                <input type="text" name="ruc" id="ruc" placeholder="RUC" value="<?php echo $ruc; ?>">

                <label for="contacto">Nombre Contacto</label>
                <input type="text" name="contacto" id="contacto" placeholder="Contacto del proveedor" value="<?php echo $contacto; ?>">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>">

                <label for="telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo $telefono; ?>">

                <label for="telefono2">Telefono 2</label>
                <input type="text" name="telefono2" id="telefono2" placeholder="telefono 2" value="<?php echo $telefono2; ?>" > 

                <label for="celular">Celular</label>
                <input type="text" name="celular" id="celular" placeholder="Celular 1" value="<?php echo $celular1; ?>">

                <label for="celular2">Celular</label>
                <input type="text" name="celular2" id="celular2" placeholder="celular 2" value="<?php echo $celular2; ?>">
                

                <!-- <input type="submit" value="Guardar Clientes" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Actualizar Proveedor</button>
            </form>
        </div>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>