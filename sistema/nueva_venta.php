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
    <title>Nueva Venta</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="title_page">
            <h1><i class="fas fa-cube"></i> Nueva Venta</h1>
        </div>
        <div class="datos_cliente">

            <div class="action_cliente">
                <h4>Datos del Cliente</h4>
                <a href="#" class="btn_new btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>
            </div>
            <h4>Busqueda de Clientes</h4>
            <input type="text" id="txt_nombre_cliente" placeholder="Filtrar por nombre cliente..." />
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>R.U.C</th>
                        <th>CLIENTE</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>
                <tbody id="detalle_busqueda_cliente">
                </tbody>
            </table>

            <form action="" name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">
                <input type="hidden" name="action" value="addCliente">
                <input type="hidden" id="idCliente" name="idCliente" value="" required>

                <div class="wd20">
                    <label for="">C.I o RUC*</label>
                    <input type="text" name="ci_cliente" id="ci_cliente">
                </div>

                <div class="wd40">
                    <label for="">Nombre*</label>
                    <input type="text" name="nom_cliente" id="nom_cliente" disabled required>
                </div>

                <div class="wd40">
                    <label for="">Dirección*</label>
                    <input type="text" name="dir_cliente" id="dir_cliente" disabled required>
                </div></br>

                <div class="wd25">
                    <label for="">Celular 1</label>
                    <input type="text" name="cel1_cliente" id="cel1_cliente" disabled>
                </div>

                <div class="wd25">
                    <label for="">Celular 2</label>
                    <input type="text" name="cel2_cliente" id="cel2_cliente" disabled>
                </div>

                <div class="wd25">
                    <label for="">Telefono 1</label>
                    <input type="text" name="tel1_cliente" id="tel1_cliente" disabled>
                </div>

                <div class="wd25">
                    <label for="">Telefono 2</label>
                    <input type="text" name="tel2_cliente" id="tel2_cliente" disabled>
                </div>

                <!-- <div class="wd30">
                    <label for="">referencia 1</label>
                    <input type="text" name="ref1_cliente" id="ref1_cliente" disabled>
                </div>

                <div class="wd30">
                    <label for="">referencia 2</label>
                    <input type="text" name="ref2_cliente" id="ref2_cliente" disabled>
                </div>

                <div class="wd30">
                    <label for="">referencia 3</label>
                    <input type="text" name="ref3_cliente" id="ref3_cliente" disabled>
                </div> -->

                <div id="div_registro_cliente" class="wd100">
                    <button type="submit" class="btn_save"><i class="far fa-save fa-lg"></i> Guardar</button>
                </div>
            </form>

        </div>
        <div class="datos_venta">
            <h4>Datos de ventas</h4>
            <div class="datos">
                <div class="wd20">
                    <label for="">Vendedor</label>
                    <p><?php echo $_SESSION['nombre']; ?></p>
                    <label for="listaPrecio">Lista de precios</label>
                    <select name="listaPrecio" id="listaPrecio">
                        <option value="1">
                            Precio Venta</option>
                        <option value="2">
                            Precio 2</option>
                        <option value="3">
                            Precio 3</option>
                        <option value="4">
                            Precio 4</option>
                    </select>

                    <label for="obra">Seleccionar Obra:</label>
                    <?php
                    $query_obra = mysqli_query($conection, "SELECT ID_OBRA, NOMBRE FROM OBRA WHERE STATUS = 1 ORDER BY NOMBRE ASC");
                    $result_obra = mysqli_num_rows($query_obra);
                    ?>
                    <select name="obra" id="obra">
                        <option value="0"></option>
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
                <div>
                    <label for="noFacturaLegal" hidden>No. Factura Legal</label>
                    <input type="text" name="noFacturaLegal" id="noFacturaLegal" value="0" hidden>
                    <!-- <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo">
                        <option value="1">
                            Contado</option>
                        <option value="2">
                            Credito</option>
                    </select> -->
                </div>
                <div class="wd25">

                    <label for="tipo">Cond. de Venta.</label>
                    <select name="tipo" id="tipo">
                        <option value="1">
                            Contado</option>
                        <option value="2">
                            Credito</option>
                    </select>

                    <label for="tiempo">Tiempo</label>
                    <select name="tiempo" id="tiempo" disabled>
                        <option value="1">
                            Mensual</option>
                        <option value="2">
                            Semanal</option>
                    </select>

                    <label for="cantCuotas">Cant. Cuotas</label>
                    <input type="text" name="cantCuotas" id="cantCuotas" placeholder="Cantidad de Cuotas" value="1" disabled>

                </div>
                <div class="wd25">
                    <label for="entrega">Entrega</label>
                    <select name="entrega" id="entrega" disabled>
                        <option value="0">CON ENTREGA
                        </option>
                        <option value="1">
                            EN 30 DÍAS</option>
                    </select>

                    <label for="montoEntrega">Monto Entrega</label>
                    <input type="text" name="montoEntrega" id="montoEntrega" placeholder="Monto de la entrega" value="0" disabled>
                </div>

                <div class="wd20">
                    <label for="">Acciones</label>
                    <div id="acciones_venta">
                        <a href="#" class="btn_ok textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" id="btn_facturar_venta" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tbl_venta">
            <!-- <a href="#" class="buscar_producto">Buscar Produtos</a> -->
            <h4>Busqueda Productos</h4>
            <input type="text" id="txt_nombre_productoVenta" placeholder="Filtrar por nombre producto..." />
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
            <h4>Detalles de venta</h4>
            <table>
                <thead>
                    <tr>
                        <th width="55px">ID</th>
                        <th width="150px">Referencia</th>
                        <th width="150px">Cod. Barra</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th width="100px">Cantidad</th>
                        <th class="textright">Precio Unit.</th>
                        <?php
                        if ($_SESSION['rol'] == 1) {
                        ?>
                            <th width="55px">Desc. %</th>
                        <?php
                        }
                        ?>
                        <th class="textright">Precio Total</th>
                        <th>Acción</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="txt_cod_productoVenta" id="txt_cod_productoVenta" disabled></td>
                        <td><input type="text" name="txt_ref_productoVenta" id="txt_ref_productoVenta"></td>
                        <td><input type="text" name="txt_codigoBarra_productoVenta" id="txt_codigoBarra_productoVenta"></td>
                        <td id="txt_desctipcion">-</td>
                        <td id="txt_existencia">-</td>
                        <td><input type="text" name="txt_cant_productoVenta" id="txt_cant_productoVenta" value="0" min="1" disabled></td>
                        <td id="txt_precio" class="textright">0</td>
                        <?php
                        if ($_SESSION['rol'] == 1) {
                        ?>
                            <td><input type="text" name="txt_porc_desc" id="txt_porc_desc" value="0" min="0" disabled></td>
                        <?php
                        }
                        ?>
                        <td id="txt_precio_total" class="textright">0</td>
                        <td><a href="#" id="add_product_venta" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                        <td hidden><input type="text" name="txt_porc_max_desc" id="txt_porc_max_desc" value=""></td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Referencia</th>
                        <th>Cod. Barra</th>
                        <th colspan="2">Descripción</th>
                        <th>Cantidad</th>
                        <th class="textright">Precio Unit.</th>
                        <th class="textright"> Desc. %</th>
                        <th class="textright">Precio total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta">
                </tbody>
                <tfoot id="detalle_totales">
                </tfoot>
            </table>
        </div>

    </section>
    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function() {
            var usuarioid = <?php echo $_SESSION['idUser']; ?>;
            searchForDetailVenta(usuarioid);
        });

        //separador de miles
        $("#montoEntrega").on({
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

</body>

</html>