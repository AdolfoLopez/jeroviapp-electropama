<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Informe Ventas Obra</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i>Informe Ventas Obra</h1>

        <div>
            <h5>Buscar por fecha</h5>
            <form action="informes/generaInformeVentasObra.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <div class="wd20">
                    <label for="Obra">Obra</label>
                    <?php
                    $query_obra = mysqli_query($conection, "SELECT ID_OBRA, NOMBRE FROM OBRA WHERE STATUS = 1 ORDER BY NOMBRE ASC");
                    $result_obra = mysqli_num_rows($query_obra);
                    ?>
                    <select name="obra" id="obra">
                        <option value="TODOS" selected>TODOS</option>
                        <?php if ($result_obra > 0) {
                            while ($obra = mysqli_fetch_array($query_obra)) {
                        ?>
                                <option value="<?php echo $obra['ID_OBRA']; ?>"><?php echo $obra['NOMBRE']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="wd20">
                    <label for="pagado">Pagado</label>
                    <select name="pagado" id="pagado">
                        <option value="TODOS">TODOS</option>
                        <option value="2">Pagado</option>
                        <option value="1">No Pagado</option>
                    </select>
                </div>
                <button type="submit" class="btn_view" target="_blank"><i class="fas fa-search"></i> GENERAR INFORME</button>

            </form>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>