<?php
session_start();
if ($_SESSION['rol'] != 1) {
    header("Location: ./");
}
include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $idUsuario = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuarios 
                                              WHERE (usuario = '$user' AND idUsuario != $idUsuario) 
                                              OR (correo = '$email' AND idUsuario != $idUsuario )");
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $alert = '<p class="msg_error">El usuario o la contraseña ya existe</p>';
        } else {

            if (empty($_POST['clave'])) {
                $sql_update = mysqli_query($conection, "UPDATE usuarios SET
                                                            nombre = '$nombre', correo = '$email', usuario = '$user', idRol = '$rol'
                                                            WHERE idUsuario = $idUsuario");
            } else {
                $sql_update = mysqli_query($conection, "UPDATE usuarios SET
                                                            nombre = '$nombre', correo = '$email', usuario = '$user', clave = '$clave', idRol = '$rol'
                                                            WHERE idUsuario = $idUsuario");
            }

            if ($sql_update) {
                $alert = '<p class="mgs_save">Usuario actualizado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al actualizar el usuario</p>';
            }
        }
    }
}

//mostrar datos
if (empty($_REQUEST['id'])) {
    header('Location: lista_usuario.php');
    mysqli_close($conection);
}

$iduser = $_REQUEST['id'];
$sql = mysqli_query($conection, "SELECT u.idUsuario, u.nombre, u.correo, u.usuario, (u.idRol) as idrol, (r.rol) as rol FROM usuarios u INNER JOIN rol r ON u.idRol = r.idRol WHERE idUsuario = $iduser and status = 1");
//mysqli_close($conection);
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header('Location: lista_usuario.php');
} else {
    $option = '';
    while ($data = mysqli_fetch_array($sql)) {
        $iduser  = $data['idUsuario'];
        $nombre  = $data['nombre'];
        $correo  = $data['correo'];
        $usuario = $data['usuario'];
        $idrol   = $data['idrol'];
        $rol     = $data['rol'];

        if ($idrol == 1) {
            $option = '<option value="' . $idrol . '" select>' . $rol . '</option>';
        } else if ($idrol == 2) {
            $option = '<option value="' . $idrol . '" select>' . $rol . '</option>';
        } else if ($idrol == 3) {
            $option = '<option value="' . $idrol . '" select>' . $rol . '</option>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar Usuario</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="far fa-edit"></i> Actualizar usuarios</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $iduser; ?>">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placesholder="Nombre completo" value="<?php echo $nombre; ?>">

                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico" value="<?php echo $correo; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">

                <label for="clave">Clave</label>
                <input type="password" id="clave" name="clave" placeholder="Clave de acceso">
                <label for="rol">Tipo de Usuario</label>

                <?php
                include "../conexion.php";
                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                //mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);
                ?>


                <select name="rol" id="rol" class="notItemOne">
                    <?php
                    echo $option;
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {
                    ?>
                            <option value="<?php echo $rol['idRol'] ?>"><?php echo $rol['rol'] ?></option>
                    <?php
                        }
                    }

                    ?>
                </select>

                <!-- <input type="submit" value="Actualizar usuario" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Actualizar usuario</button>
            </form>
        </div>

    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>
