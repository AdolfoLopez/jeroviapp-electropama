<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

header('content-type: text/html; charset=utf-8');

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['cantInventario']) || $_POST['cantInventario'] == 0 ) {
        $alert = '<p class="mgs_error">El campo de cantidad actual no debe estar vacio o con valor 0</p>';
    } else {
        $cantInventario = $_POST['cantInventario'];
        $idProducto     = $_POST['idProducto'];
        $cantActual     = $_POST['cantActual'];
        $cantInventario = $_POST['cantInventario'];
        $descripcion    = $_POST['descripcion'];
        $idUsuario      = $_SESSION['idUser'];

            $query_update = mysqli_query($conection, "UPDATE productos SET existencia = $cantInventario WHERE idProducto = $idProducto" );

            $query_insert = mysqli_query($conection, "INSERT INTO inventarios(idProducto,cantActual,cantInventario,IdUsuario,descripcion) VALUES('$idProducto', '$cantActual', '$cantInventario', '$idUsuario', '$descripcion')");
            if ($query_insert) {
                $alert = '<p class="mgs_save">INVENTARIO guardado</p>';
            } else {
                $alert = '<p class="mgs_error">Error al GUARDAR</p>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Registro Producto</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Registro de Inventarios</h1>
            
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>
            <h4>Busqueda Productos</h4>
            <input type="text" id="txt_nombre_productoInventario" placeholder="Filtrar por nombre, codigo barra, referencia..." />
            <table>
                <thead>
                    <tr>
                        <th>REFERENCIA</th>
                        <th>COD. BARRA</th>
                        <th>NOMBRE PRODUCTO</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_busqueda_nombre">
                </tbody>
            </table>
            <br />

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idProducto" id="idProducto" value="">
                <label for="referencia">Referencia</label>
                <input type="text" name="referencia" id="referencia" placeholder="Referencia del producto" readonly>

                <label for="codigoDeBarra">Codigo de Barra</label>
                <input type="text" name="codigoDeBarra" id="codigoDeBarra" placeholder="Codigo de Barra" readonly>

                <label for="producto">Nombre del producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto" readonly>

                <label for="cantActual">Cantidad Actual</label>
                <input type="text" name="cantActual" id="cantActual" value="" readonly>

                <label for="cantInventario">Cantidad Inventario</label>
                <input type="text" name="cantInventario" id="cantInventario" placeholder="Cantidad Inventario" autofocus>

                <label for="descripcion">Motivo / Descripción</label>
                <input type="text" name="descripcion" id="descripcion" placeholder="Descripción">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Inventario</button>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type=text]').forEach(node => node.addEventListener('keypress', e => {
                if (e.keyCode == 13) {
                    e.preventDefault();
                }
            }))
        });
    </script>
    <?php include "includes/footer.php"; ?>
</body>

</html>