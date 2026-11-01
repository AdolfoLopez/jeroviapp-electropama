<?php

$db_host = 'localhost'; //Host del Servidor MySQL
$db_name = 'dbSystem'; //Nombre de la Base de datos
$db_user = 'root'; //Usuario de MySQL
$db_pass = ''; //Password de Usuario MySQL
// echo $db_host;

$fecha = date("Ymd-His"); //Obtenemos la fecha y hora para identificar el respaldo
// echo $fecha;

// Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
$salida_sql = $db_name . '_' . $fecha . '.sql';
// echo $salida_sql;

//Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
$dump = "mysqldump -h $db_host -u$db_user -p$db_pass --opt $db_name  > $salida_sql";
// $output = "/var/www/html/system/";
system($dump, $output); //Ejecutamos el comando para respaldo

// $zip = new ZipArchive(); //Objeto de Libreria ZipArchive

// Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
// $salida_zip = $db_name . '_' . $fecha . '.zip';

// if ($zip->open($salida_zip, ZIPARCHIVE::CREATE) === true) { //Creamos y abrimos el archivo ZIP
//     $zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
//     $zip->close(); //Cerramos el ZIP
//     unlink($salida_sql); //Eliminamos el archivo temporal SQL
//     // header("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP

//     // header('Content-Type: application/sql');
//     // header('Content-disposition: attachment; filename='.$salida_sql);
//     // header('Content-Length: ' . filesize($salida_sql));

//     // readfile($salida_sql);

//     // $srcfile='C:\File\Whatever\Path\Joe.txt';
//     // $dstfile='G:\Shared\Reports\Joe.txt';
//     //$srcfile = '/var/www/html/system/respaldo/' . $salida_zip; //C:\xampp\htdocs\system\respaldo\  C:\xampp\htdocs\sistema\respaldo\
//     //$dstfile = '/home/adolfolopez/Descargas/' . $salida_zip; //C:\Users\Admin\Desktop\BACKUP-BD\   C:\Users\Adoflo López\Desktop\BACKUP-BD\
    
//     $srcfile = "C:\xampp\htdocs\sistema\respaldo" . $salida_zip;
//     $dstfile = 'C:\Users\Adoflo López\Desktop\BACKUP-BD' . $salida_zip;
    
//     // mkdir(dirname($dstfile), 0777, true);
//     copy($srcfile, $dstfile);
// } else {
//     echo 'Error'; //Enviamos el mensaje de error;
// }
