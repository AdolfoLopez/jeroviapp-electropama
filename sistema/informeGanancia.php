<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html> 
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Informe de Ganancias</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Informe de Ganancias</h1>
        <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_informeGanancia.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>Total Ventas</th>
                <th>Total Gastos</th>
                <th>Total Compras</th>
                <th>Ganancias</th>
            </tr>
        </table>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>