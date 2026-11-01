<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("Location: ./");
}

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['rol'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {

        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuarios WHERE usuario = '$user' OR correo = '$email'");
        //mysqli_close($conection);
        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El usuario o la contraseña ya existe</p>';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO usuarios(nombre,correo,usuario,clave,idRol) VALUES('$nombre','$email','$user','$clave','$rol')");

            if ($query_insert) {
                $alert = '<p class="mgs_save">Usuario creado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al crear el usuario</p>';
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
    <title>Registro Usuario</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-user-plus"></i> Registro de usuarios</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placesholder="Nombre completo">

                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario">

                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" placeholder="Clave de acceso">

                <?php
                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                //mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);
                ?>

                <label for="rol">Tipo de Usuario</label>
                <select name="rol" id="rol">
                    <?php
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                    ?>
                            <option value="<?php echo $rol['idRol'] ?>"><?php echo $rol['rol'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <!--<input type="submit" value="Crear usuario" class="btn_save">-->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Crear usuario</button>
            </form>
        </div>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>