<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

header('content-type: text/html; charset=utf-8');

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['producto']) || empty($_POST['precioVenta']) || empty($_POST['referencia']) || empty($_POST['marca']) || empty($_POST['categoria'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $proveedor      = $_POST['proveedor'];
        $nombreProducto = $_POST['producto'];
        $precioVenta    = intval(str_replace(".", "", $_POST['precioVenta']));
        $precioVenta2    = intval(str_replace(".", "", $_POST['precioVenta2']));
        $precioVenta3    = intval(str_replace(".", "", $_POST['precioVenta3']));
        $precioVenta4    = intval(str_replace(".", "", $_POST['precioVenta4']));

        $precioCompra   = intval(str_replace(".", "", $_POST['precioCosto']));
        if ($_POST['existencia'] == '') {
            $existencia     = 0;
        } else {
            $existencia     = $_POST['existencia'];
        }
        $porc_max_desc     = $_POST['porc_max_desc'] ?? null;
        // $porc_max_desc_sql = $porc_max_desc === null ? 'NULL' : $porc_max_desc;
        // $porc_max_desc_sql = $porc_max_desc === null ? 'NULL' : $porc_max_desc;
        $porc_max_desc_sql = empty($porc_max_desc)
            ? 'NULL'
            : $porc_max_desc;
        // $cantMin     = $_POST['cantMin'] ?? null;
        // $cantMin_sql = $cantMin === null ? 'NULL' : $cantMin;

        $cantMin = isset($_POST['cantMin']) && trim($_POST['cantMin']) !== ''
            ? (int) $_POST['cantMin']
            : null;

        $cantMin_sql = ($cantMin === null)
            ? 'NULL'
            : $cantMin;

        $referencia     = $_POST['referencia'];
        $codigoDeBarra  = $_POST['codigoDeBarra'];
        $codigoDeBarra_sql = $codigoDeBarra === ''
            ? 'NULL'
            : "'" . mysqli_real_escape_string($conection, $codigoDeBarra) . "'";
        $marca          = $_POST['marca'];
        $categoria      = $_POST['categoria'];
        $idUsuario      = $_SESSION['idUser'];

        $foto        = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type        = $foto['type'];
        $url_temp    = $foto['tmp_name'];

        $imgProducto = 'img_producto.jpg';

        if ($nombre_foto != '') {
            $destino     = 'img/uploads/';
            $img_nombre  = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src         = $destino . $imgProducto;
        }

        $queryVerificacion = mysqli_query($conection, "SELECT * FROM productos WHERE referencia = '$referencia'");
        $resultQueryVerificacion = mysqli_num_rows($queryVerificacion);
        if ($resultQueryVerificacion > 0) {
            $alert = '<p class="mgs_error">YA EXISTE UN PRODUCTO CON ESTA REFERENCIA</p>';
        } else {
            // $query_insert = mysqli_query($conection, "INSERT INTO productos(nombre,idProveedor,precioCosto,precioVenta,existencia,foto,idUsuario,referencia,idMarca,idCategoria,precioVenta2,precioVenta3,precioVenta4,porc_max_desc,codigoDeBarra,cantMin) VALUES('$nombreProducto','$proveedor','$precioCompra','$precioVenta','$existencia','$imgProducto','$idUsuario','$referencia','$marca','$categoria','$precioVenta2','$precioVenta3','$precioVenta4',$porc_max_desc,'$codigoDeBarra','$cantMin')");


            $sql = "INSERT INTO productos(
    nombre,
    idProveedor,
    precioCosto,
    precioVenta,
    existencia,
    foto,
    idUsuario,
    referencia,
    idMarca,
    idCategoria,
    precioVenta2,
    precioVenta3,
    precioVenta4,
    porc_max_desc,
    codigoDeBarra,
    cantMin
) VALUES(
    '$nombreProducto',
    '$proveedor',
    '$precioCompra',
    '$precioVenta',
    '$existencia',
    '$imgProducto',
    '$idUsuario',
    '$referencia',
    '$marca',
    '$categoria',
    '$precioVenta2',
    '$precioVenta3',
    '$precioVenta4',
    $porc_max_desc_sql,
    $codigoDeBarra_sql,
    $cantMin_sql
)";

// echo "<pre>";
// echo $sql;
// echo "</pre>";
// exit;

$query_insert = mysqli_query($conection, $sql);




            if ($query_insert) {
                $alert = '<p class="mgs_save">PRODUCTO creado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al crear PRODUCTO</p>';
            }
            if ($query_insert) {
                if ($nombre_foto != '') {
                    move_uploaded_file($url_temp, $src);
                }
                $alert = '<p class="mgs_save">Producto creado correctamente</p>';
            } else {
                $alert = '<p class="mgs_error">Error al crear el producto</p>';
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
    <title>Registro Producto</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Registro de Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="proveedor">Proveedor*</label>
                <?php
                $query_proveedor = mysqli_query($conection, "SELECT idProveedor, nombre FROM proveedores WHERE status = 1 ORDER BY nombre ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                ?>

                <select name="proveedor" id="proveedor">
                    <?php if ($result_proveedor > 0) {
                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                    ?>
                            <option value="<?php echo $proveedor['idProveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label for="marca">Marca*</label>
                <?php
                $query_marca = mysqli_query($conection, "SELECT idMarca, nombre FROM marcas WHERE status = 1 ORDER BY nombre ASC");
                $result_marca = mysqli_num_rows($query_marca);
                ?>
                <select name="marca" id="marca">
                    <?php if ($result_marca > 0) {
                        while ($marca = mysqli_fetch_array($query_marca)) {
                    ?>
                            <option value="<?php echo $marca['idMarca']; ?>"><?php echo $marca['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label for="categoria">Categoria*</label>
                <?php
                $query_categoria = mysqli_query($conection, "SELECT idCategoria, nombre FROM categorias WHERE status = 1 ORDER BY nombre ASC");
                $result_categoria = mysqli_num_rows($query_categoria);
                ?>
                <select name="categoria" id="categoria">
                    <?php if ($result_categoria > 0) {
                        while ($categoria = mysqli_fetch_array($query_categoria)) {
                    ?>
                            <option value="<?php echo $categoria['idCategoria']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label for="referencia">Referencia*</label>
                <input type="text" name="referencia" id="referencia" placeholder="Referencia del producto">

                <label for="codigoDeBarra">Codigo de Barra</label>
                <input type="text" name="codigoDeBarra" id="codigoDeBarra" placeholder="Codigo de Barra" value="0">

                <label for="producto">Nombre del producto*</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto">

                <label for="precioCosto">Precio Costo</label>
                <input type="text" name="precioCosto" id="precioCosto" placeholder="Precio Costo">

                <label for="precioVenta">Precio Venta*</label>
                <input type="text" name="precioVenta" id="precioVenta" placeholder="Precio Venta">

                <label for="precioVenta2">Precio Venta 2</label>
                <input type="text" name="precioVenta2" id="precioVenta2" placeholder="Precio Venta 2">

                <label for="precioVenta3">Precio Venta 3</label>
                <input type="text" name="precioVenta3" id="precioVenta3" placeholder="Precio Venta 3">

                <label for="precioVenta4">Precio Venta 4</label>
                <input type="text" name="precioVenta4" id="precioVenta4" placeholder="Precio Venta 4">

                <label for="porc_max_desc">Descuento Máximo en %</label>
                <input type="text" name="porc_max_desc" id="porc_max_desc" placeholder="Descuentos máximo en %">

                <label for="cantMin">Cantidad Minima</label>
                <input type="text" name="cantMin" id="cantMin" placeholder="Cantidad Minima">

                <label for="existencia">Existencia</label>
                <input type="number" name="existencia" id="existencia" placeholder="Existencia">

                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <!-- <input type="submit" value="Guardar Clientes" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Producto</button>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        //SEPARADOR DE MILES.
        $("#precioCosto").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        $("#precioVenta").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        $("#precioVenta2").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        $("#precioVenta3").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
        $("#precioVenta4").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });

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