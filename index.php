<?php
$alert = "";
// Limita los intentos fallidos para reducir ataques de fuerza bruta.
$maxLoginAttempts = 3;
$lockoutSeconds = 3 * 60;
session_start();

// Restablece el bloqueo y el contador cuando finaliza el periodo de espera.
if (!empty($_SESSION['login_locked_until']) && $_SESSION['login_locked_until'] <= time()) {
    unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
}
if (!empty($_SESSION['active'])) {
    header('Location: sistema/');
    // Detiene la respuesta para que no se renderice el formulario tras redirigir.
    exit;
} else {



    if (!empty($_POST)) {
        // Impide nuevas validaciones mientras la cuenta temporalmente bloqueada sigue vigente.
        if (!empty($_SESSION['login_locked_until']) && $_SESSION['login_locked_until'] > time()) {
            $remainingMinutes = (int) ceil(($_SESSION['login_locked_until'] - time()) / 60);
            $alert = "Demasiados intentos fallidos. Intente nuevamente en $remainingMinutes minuto(s).";
        } elseif (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = "Ingrese su usuario y clave";
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conection, $_POST['usuario']);
            $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));

            $query = mysqli_query($conection, "SELECT * FROM usuarios WHERE usuario = '$user' AND clave = '$pass' ");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $data = mysqli_fetch_array($query);

                // Limpia el límite de intentos y renueva la sesión tras autenticar al usuario.
                unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
                session_regenerate_id(true);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idUsuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email']  = $data['correo'];
                $_SESSION['user']   = $data['usuario'];
                $_SESSION['rol']    = $data['idRol'];

                header('Location: sistema/');
                // Finaliza la respuesta después de una redirección exitosa.
                exit;
            } else {
                // Registra el fallo y activa el bloqueo al alcanzar el máximo permitido.
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

                if ($_SESSION['login_attempts'] >= $maxLoginAttempts) {
                    $_SESSION['login_locked_until'] = time() + $lockoutSeconds;
                    unset($_SESSION['login_attempts']);
                    $alert = "Demasiados intentos fallidos. Intente nuevamente en 3 minutos.";
                } else {
                    $alert = "El usuario o la contrasena es incorrecta";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SystemCCV</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section id="container">
        <form action="" method="post">
            <h3>Iniciar Sesión</h3>
            <img src="img/login.png" alt="login">

            <input type="text" name="usuario" placeholder="Usuario" autofocus>
            <input type="password" name="clave" placeholder="Contraseña">
            <div class="alert"><?php echo isset($alert) ? $alert : '';  ?></div>
            <input type="submit" value="INGRESAR">
        </form>

    </section>

</body>

</html>
