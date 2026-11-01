<?php
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>Nueva Compra</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="title_page">
            <h1><i class="fas fa-cube"></i> Nueva Compra</h1>
        </div>
        <div class="datos_cliente">
            <div class="action_cliente">
                <h4>Datos del Proveedor</h4>
                <a href="#" class="btn_new btn_new_proveedor"><i class="fas fa-plus"></i> Nuevo Proveedor</a>
            </div>
            <h4>Busqueda de Proveedor</h4>
            <input type="text" id="txt_nombre_proveedor" placeholder="Filtrar por nombre y/o Id proveedor..." />
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>R.U.C</th>
                        <th>PROVEEDOR</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>
                <tbody id="detalle_busqueda_proveedor">
                </tbody>
            </table>
            <form action="" name="form_new_proveedor_compra" id="form_new_proveedor_compra" class="datos">
                <input type="hidden" name="action" value="addProveedor">
                <input type="hidden" id="idProveedor" name="idProveedor" value="" required>

                <div class="wd30">
                    <label for="bnombre">Buscar</label>
                    <input type="text" name="bnom_proveedor" id="bnom_proveedor">
                </div>

                <div class=" wd30">
                    <label for="nombre">Proveedor*</label>
                    <input type="text" name="nom_proveedor" id="nom_proveedor" disabled required>
                </div>

                <div class=" wd30">
                    <label for="contacto">Contacto*</label>
                    <input type="text" name="con_proveedor" id="con_proveedor" disabled required>
                </div>
                <div class="wd30">
                    <label for="telefono">Telefono*</label>
                    <input type="text" name="tel_proveedor" id="tel_proveedor" disabled required>
                </div>
                <div class="wd100">
                    <label for="direccion">Dirección*</label>
                    <input type="text" name="dir_proveedor" id="dir_proveedor" disabled required>
                </div>

                <div id="div_registro_proveedor" class="wd100">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
        <div class="datos_venta">
            <h4>Datos de compra</h4>
            <div class="datos">
                <div class="wd50">
                    <label for="">Comprador</label>
                    <p><?php echo $_SESSION['nombre']; ?></p>
                </div>
                <!-- <div class="wd50">
                    <label for="">Fecha Emisión</label>
                    <input type="date" name="fechaEmision" id="fechaEmision">
                </div> -->
                <div class="wd50">
                    <label for="">Acciones</label>
                    <div id="acciones_venta">
                        <a href="#" class="btn_ok textcenter" id="btn_anular_compra"><i class="fas fa-ban"></i> ANULAR</a>
                        <a href="#" class="btn_new textcenter" id="btn_facturar_compra" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tbl_venta">
        <h4>Busqueda Productos</h4>
            <input type="text" id="txt_nombre_productoCompra" placeholder="Filtrar por nombre, referencia, codigo de barra de producto..." />
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>REFERENCIA</th>
                        <th>COD. BARRA</th>
                        <th>NOMBRE PRODUCTO</th>
                        <th>STOCK</th>
                        <th>Precio Unit.</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_busqueda_nombre">
                </tbody>
            </table>
            <br />
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th width="100px">Referencia</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th width="100px">Cantidad</th>
                        <th class="textright">Precio</th>
                        <th class="textright">Precio Total</th>
                        <th>Acción</th>
                    </tr>
                    <tr>
                        <td style="width:55px"><input type="text" name="txt_cod_producto2" id="txt_cod_producto2" disabled></td>
                        <td><input type="text" name="txt_ref_producto" id="txt_ref_producto"></td>
                        <td id="txt_desctipcion">-</td>
                        <td id="txt_existencia">-</td>
                        <td><input type="text" name="txt_cant_producto2" id="txt_cant_producto2" value="0" min="1" disabled></td>
                        <td id="txt_precio" class="textright">0</td>
                        <td id="txt_precio_total" class="textright">0</td>
                        <td><a href="#" id="add_product_compra" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Referencia</th>
                        <th colspan="2">Descripción</th>
                        <th>Cantidad</th>
                        <th class="textright">Precio</th>
                        <th class="textright">Precio total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_ventaCompra">
                    <!-- contnido ajax -->
                </tbody>
                <tfoot id="detalle_totalesCompra">
                    <!-- contentido ajax -->
                </tfoot>
            </table>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function() {
            var usuarioid = <?php echo $_SESSION['idUser']; ?>;
            searchForDetailCompra(usuarioid);
        });
    </script>
</body>

</html>