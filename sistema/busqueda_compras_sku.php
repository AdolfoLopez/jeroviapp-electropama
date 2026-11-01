<?php
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Ventas</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Busqueda cant. comprada por Referencia Producto</h1>

        <form action="busqueda_producto_sku_result.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Referencia">
            <input type="submit" value="Buscar" class="btn_search">
        </form>
        <table>
            <tr>
                <th>No. Compra</th>
                <th>Fecha / Hora</th>
                <th>Referencia</th>
                <th>Nombre Producto</th>
                <th>Proveedor</th>
                <th>Cant. Comprada</th>
            </tr>
        </table>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>