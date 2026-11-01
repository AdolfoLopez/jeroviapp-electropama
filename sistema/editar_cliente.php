<?php
session_start();
include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['ruc_ci']) || empty($_POST['nombre']) || empty($_POST['direccion'])) {
        $alert = '<p class="mgs_error">Favo completar los campos obligatorios</p>';
    } else {
        $idCliente = $_POST['id'];
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

        $query = mysqli_query($conection, "SELECT * FROM clientes WHERE (ci_ruc = $ruc_ci and idCliente != $idCliente) ");
        $result = mysqli_fetch_array($query);
        $result = count($result);

        if ($result > 0) {
            $alert = '<p class="msg_error">El número de ruc o C. I. ya existe</p>';
        } else {
            $sql_update = mysqli_query($conection, "UPDATE clientes
                                                        SET ci_ruc = '$ruc_ci', nombre = '$nombre', direccion = '$direccion', celular1 = '$celular1', celular2 = '$celular2', telefono1 = '$telefono1', telefono2 = '$telefono2', referencia1 = '$referencia1', referencia2 = '$referencia2', referencia3 = '$referencia3'  
                                                            WHERE idCliente = $idCliente");

            if ($sql_update) {
                $alert = '<p class="mgs_save">Cliente actualizado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al actualizar el cliente</p>';
            }
        }
    }
}


//mostrar datos
if (empty($_REQUEST['id'])) {
    header('Location: lista_clientes.php');
    mysqli_close($conection);
}

$idCliente = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT * FROM clientes WHERE idCliente = $idCliente and status = 1");
//mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header('Location: lista_cliente.php');
} else {
    while ($data = mysqli_fetch_array($sql)) {
        $idCliente   = $data['idCliente'];
        $ci_ruc      = $data['ci_ruc'];
        $nombre      = $data['nombre'];
        $direccion   = $data['direccion'];
        $celular1    = $data['celular1'];
        $celular2    = $data['celular2'];
        $telefono1   = $data['telefono1'];
        $telefono2   = $data['telefono2'];
        $referencia1 = $data['referencia1'];
        $referencia2 = $data['referencia2'];
        $referencia3 = $data['referencia3'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar Clientes</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1>Actualizar Clientes</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">

                <input type="hidden" name="id" value="<?php echo $idCliente; ?>">
                <label for="ruc_ci">RUC - C.I *</label>
                <input type="text" name="ruc_ci" id="ruc_ci" placeholder="RUC y/o C. de Identidad" value="<?php echo $ci_ruc ?>">

                <label for="nombre">Razón Social *</label>
                <input type="text" name="nombre" id="nombre" placesholder="Razón Social o Nombre completo" value="<?php echo $nombre ?>">

                <label for="direccion">Dirección *</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo $direccion ?>">

                <label for="celular1">Celular 1</label>
                <input type="text" name="celular1" id="celular1" placeholder="Celular 1" value="<?php echo $celular1 ?>">

                <label for="celular2">Celular 2</label>
                <input type="text" name="celular2" id="celular2" placeholder="Celular 2" value="<?php echo $celular2 ?>">

                <label for="telefono1">Teléfono 1</label>
                <input type="text" id="telefono1" name="telefono1" placeholder="Teléfono 1" value="<?php echo $telefono1 ?>">

                <label for="telefono2">Teléfono 2</label>
                <input type="text" id="telefono2" name="telefono2" placeholder="Teléfono 2" value="<?php echo $telefono2 ?>">

                <!-- <label for="referencia1">Referencia 1</label>
                <input type="text" id="referencia1" name="referencia1" placeholder="Referencia 1" value="<?php echo $referencia1 ?>">

                <label for="referencia2">Referencia 2</label>
                <input type="text" id="referencia2" name="referencia2" placeholder="Referencia 2" value="<?php echo $referencia2 ?>">

                <label for="referencia3">Referencia 3</label>
                <input type="text" id="referencia3" name="referencia3" placeholder="Referencia 3" value="<?php echo $referencia3 ?>"> -->

                <input type="submit" value="Actualizar Clientes" class="btn_save">
            </form>
        </div>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>