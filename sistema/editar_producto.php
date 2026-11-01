<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
header('content-type: text/html; charset=utf-8');

include "../conexion.php";
if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $idProducto     = $_POST['id'];
        $proveedor      = $_POST['proveedor'];
        $nombreProducto = $_POST['producto'];
        $referencia     = $_POST['referencia'];
        $codigoDeBarra  = $_POST['codigoDeBarra'];
        $codigoDeBarra_sql = $codigoDeBarra === ''
            ? 'NULL'
            : "'" . mysqli_real_escape_string($conection, $codigoDeBarra) . "'";
        $marca          = $_POST['marca'];
        $categoria      = $_POST['categoria'];
        $precioVenta    = intval(str_replace(".", "", $_POST['precioVenta']));
        $precioVenta2   = intval(str_replace(".", "", $_POST['precioVenta2']));
        $precioVenta3   = intval(str_replace(".", "", $_POST['precioVenta3']));
        $precioVenta4   = intval(str_replace(".", "", $_POST['precioVenta4']));
        $precioCompra   = intval(str_replace(".", "", $_POST['precioCosto']));
        $imgProducto    = $_POST['foto_actual'];
        $imgRemove      = $_POST['foto_remove'];
        $idUsuario      = $_SESSION['idUser'];
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

        $foto        = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type        = $foto['type'];
        $url_temp    = $foto['tmp_name'];

        $upd = '';

        if ($nombre_foto != '') {
            $destino     = 'img/uploads/';
            $img_nombre  = 'img_' . md5(date('d-m-Y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src         = $destino . $imgProducto;
        } else {
            if ($_POST['foto_actual'] != $_POST['foto_remove']) {
                $imgProducto = 'img_producto.jpg';
            }
        }

        $query_update = mysqli_query($conection, "UPDATE productos SET 
                                                    nombre = '$nombreProducto',
                                                    idProveedor = '$proveedor',
                                                    precioCosto = '$precioCompra',
                                                    precioVenta = '$precioVenta',
                                                    precioVenta2 = '$precioVenta2',
                                                    precioVenta3 = '$precioVenta3',
                                                    precioVenta4 = '$precioVenta4',
                                                    foto = '$imgProducto',
                                                    referencia = '$referencia',
                                                    idMarca = '$marca',
                                                    idCategoria = '$categoria',
                                                    existencia = '$existencia',
                                                    porc_max_desc = $porc_max_desc_sql,
                                                    codigoDeBarra = $codigoDeBarra_sql,
                                                    cantMin = $cantMin_sql
                                                    WHERE idProducto = $idProducto
                                                    ");

        if ($query_update) {
            if ($nombre_foto != '' && $_POST['foto_actual'] != 'img_producto.jpg' || $_POST['foto_actual'] != $_POST['foto_remove']) {
                unlink('img/uploads/' . $_POST['foto_actual']);
            }

            if ($nombre_foto != '') {
                move_uploaded_file($url_temp, $src);
            }
            $alert = '<p class="mgs_save">Producto actualizado correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al actualizar el producto</p>';
        }
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_producto.php");
} else {
    $idProducto = $_REQUEST['id'];
    if (!is_numeric($idProducto)) {
        header("Location: lista_producto.php");
    }

    $query_producto = mysqli_query($conection, "SELECT p.idProducto, p.idProveedor, p.idMarca, p.idCategoria, p.referencia, p.nombre, p.precioCosto, p.precioVenta, p.precioVenta2, p.precioVenta3, p.precioVenta4, p.existencia,p.codigoDeBarra, pr.nombre as nombreProveedor, p.foto, m.nombre as nombreMarca, c.nombre as nombreCategoria, p.porc_max_desc, p.cantMin FROM productos p LEFT JOIN proveedores pr on(p.idProveedor = pr.idProveedor) 
    LEFT JOIN marcas m ON (p.idMarca = m.idMarca)
    LEFT JOIN categorias c ON (p.idCategoria = c.idCategoria) WHERE p.idProducto = $idProducto AND p.status = 1");
    $result_producto = mysqli_num_rows($query_producto);
    $nom = utf8_encode($result_producto);

    $foto = '';
    $classRemove = 'notBlock';

    if ($result_producto > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);
        if ($data_producto['foto'] != 'img_producto.jpg') {
            $classRemove = '';
            $foto = '<img id="img" src="img/uploads/' . $data_producto['foto'] . '" alt="producto">';
        }

        //print_r($data_producto);
    } else {
        header("Location: lista_producto.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Actualizar Producto</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Actualizar de Producto</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data_producto['idProducto']; ?>">
                <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">

                <label for="proveedor">Proveedor</label>

                <?php
                $query_proveedor = mysqli_query($conection, "SELECT idProveedor, nombre FROM proveedores WHERE status = 1 ORDER BY nombre ASC");
                $result_proveedor = mysqli_num_rows($query_proveedor);
                //mysqli_close($conection);
                ?>

                <select name="proveedor" id="proveedor" class="notItemOne">
                    <option value="<?php echo $data_producto['idProveedor']; ?>" selected><?php echo $data_producto['nombreProveedor']; ?></option>

                    <?php if ($result_proveedor > 0) {
                        while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                    ?>
                            <option value="<?php echo $proveedor['idProveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>

                </select>

                <label for="marca">Marca</label>
                <?php
                $query_marca = mysqli_query($conection, "SELECT idMarca, nombre FROM marcas WHERE status = 1 ORDER BY nombre ASC");
                $result_marca = mysqli_num_rows($query_marca);
                //mysqli_close($conection);
                ?>

                <select name="marca" id="marca" class="notItemOne">
                    <option value="<?php echo $data_producto['idMarca']; ?>" selected><?php echo $data_producto['nombreMarca']; ?></option>

                    <?php if ($result_marca > 0) {
                        while ($marca = mysqli_fetch_array($query_marca)) {
                    ?>
                            <option value="<?php echo $marca['idMarca']; ?>"><?php echo $marca['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>

                </select>

                <label for="categoria">Categoria</label>
                <?php
                $query_categoria = mysqli_query($conection, "SELECT idCategoria, nombre FROM categorias WHERE status = 1 ORDER BY nombre ASC");
                $result_categoria = mysqli_num_rows($query_categoria);
                //mysqli_close($conection);
                ?>

                <select name="categoria" id="categoria" class="notItemOne">
                    <option value="<?php echo $data_producto['idCategoria']; ?>" selected><?php echo $data_producto['nombreCategoria']; ?></option>

                    <?php if ($result_categoria > 0) {
                        while ($categoria = mysqli_fetch_array($query_categoria)) {
                    ?>
                            <option value="<?php echo $categoria['idCategoria']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php
                        }
                    }
                    ?>

                </select>

                <label for="referencia">Referencia</label>
                <input type="text" name="referencia" id="referencia" placeholder="Referencia del producto" value="<?php echo $data_producto['referencia']; ?>">

                <label for="codigoDeBarra">Codigo de Barra</label>
                <input type="text" name="codigoDeBarra" id="codigoDeBarra" placeholder="Codigo de Barra" value="<?php echo $data_producto['codigoDeBarra']; ?>">

                <label for="producto">Nombre del producto</label>
                <input type="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo utf8_encode($data_producto['nombre']); ?>">

                <label for="precioCosto">Precio Costo</label>
                <input type="text" name="precioCosto" id="precioCosto" placeholder="Precio Costo" value="<?php echo number_format($data_producto['precioCosto'], 0, ",", "."); ?>">

                <label for="precioVenta">Precio Venta</label>
                <input type="text" name="precioVenta" id="precioVenta" placeholder="Precio Venta" value="<?php echo number_format($data_producto['precioVenta'], 0, ",", "."); ?>">

                <label for="precioVenta2">Precio Venta 2</label>
                <input type="text" name="precioVenta2" id="precioVenta2" placeholder="Precio Venta 2" value="<?php echo number_format($data_producto['precioVenta2'], 0, ",", "."); ?>">

                <label for="precioVenta3">Precio Venta 3</label>
                <input type="text" name="precioVenta3" id="precioVenta3" placeholder="Precio Venta 3" value="<?php echo number_format($data_producto['precioVenta3'], 0, ",", "."); ?>">

                <label for="precioVenta4">Precio Venta 4</label>
                <input type="text" name="precioVenta4" id="precioVenta4" placeholder="Precio Venta 4" value="<?php echo number_format($data_producto['precioVenta4'], 0, ",", "."); ?>">

                <label for="porc_max_desc">Descuento Máximo en %</label>
                <input type="text" name="porc_max_desc" id="porc_max_desc" placeholder="Descuentos máximo en %" value="<?php echo $data_producto['porc_max_desc']; ?>">

                <label for="cantMin">Cantidad Minima</label>
                <input type="text" name="cantMin" id="cantMin" placeholder="Cantidad Minima" value="<?php echo $data_producto['cantMin']; ?>">

                <div class="photo">
                    <label for="foto">Foto</label>
                    <div class="prevPhoto">
                        <span class="delPhoto <?php echo $classRemove; ?>">X</span>
                        <label for="foto"></label>
                        <?php echo $foto; ?>
                    </div>
                    <div class="upimg">
                        <input type="file" name="foto" id="foto">
                    </div>
                    <div id="form_alert"></div>
                </div>

                <label for="existencia">Existencia</label>
                <input type="number" name="existencia" id="existencia" placeholder="Existencia" value="<?php echo $data_producto['existencia']; ?>">

                <!-- <input type="submit" value="Guardar Clientes" class="btn_save"> -->
                <button type="submit" class="btn_save"><i class="far fa-save"></i> Actualizar Producto</button>
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