<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'jeroviapp_electropama';

$conection = @mysqli_connect($host, $user, $password, $db);
if (!$conection) {
  echo "Error en la conexion";
}
$conection->set_charset("utf8mb4");