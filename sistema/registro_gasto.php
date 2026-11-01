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
    <title>Registro de Gastos</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="title_page">
            <h1><i class="fas fa-cube"></i> Registro de Gastos</h1>
        </div>

        <div class="datos_venta">
            <h4>Datos de Registro Gastos</h4>
            <div class="datos">
                <div class="wd50">
                    <label for="">Usuario</label>
                    <p><?php echo $_SESSION['nombre']; ?></p>
                </div>

                <div class="wd50">
                    <label for="tipoDocumento">Tipo Documento</label>
                    <select name="tipoDocumento" id="tipoDocumento">
                        <option value="Factura">
                            Factura</option>
                        <option value="Recibo">
                            Recibo</option>
                    </select>

                    <br />
                </div>
                <div class="wd50">
                    <label for="noDocumento">No. Documento</label>
                    <input type="text" name="noDocumento" id="noDocumento" maxlength="9">
                </div>

                <div class="wd50">
                    <?php
                    date_default_timezone_set('America/Asuncion');
                    $fecha_actual = date('Y-m-d H:i:s');


                    // $month = date('m');
                    // $day = date('d');
                    // $year = date('Y');

                    // $hora    = time('H');
                    // $minuto  = time('m');
                    // $segundo = time('s');  

                    // $today = $year . '-' . $month . '-' . $day . ' '. $hora . ':'. $minuto . ':' . $segundo;
                    ?>
                    <label for="fecha">Fecha</label>
                    <input type="datetime" name="fecha" id="fecha" value="<?php echo $fecha_actual; ?>">

                </div>

                <div class="wd50">
                    <label for="">Acciones</label>
                    <div id="acciones_venta">
                        <a href="#" class="btn_ok textcenter" id="btn_anular_gasto"><i class="fas fa-ban"></i> Anular</a>
                        <a href="#" class="btn_new textcenter" id="btn_procesar_gasto" style="display: none;"><i class="far fa-edit"></i> Procesar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tbl_venta">
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="textright">Monto</th>
                        <th>Acción</th>
                    </tr>

                    <tr>
                        <td width="555px"><input type="text" name="txt_descripcion" id="txt_descripcion"></td>
                        <td><input type="text" name="txt_monto" id="txt_monto" value="0"></td>
                        <td><a href="#" id="add_gasto" class="link_add"><i class="fas fa-plus"></i> Agregar</a></td>
                    </tr>

                    <tr>
                        <th>Descripción</th>
                        <th class="textright">Monto</th>
                        <th>Acción</th>
                    </tr>

                </thead>
                <tbody id="detalle_gasto">
                </tbody>
                <tfoot id="detalle_totales_gasto">
                </tfoot>
            </table>
        </div>

    </section>
    <?php include "includes/footer.php"; ?>

    <script type="text/javascript">
        $(document).ready(function() {
            var usuarioid = <?php echo $_SESSION['idUser']; ?>;
            searchForDetailGasto(usuarioid);
        });

        //SEPARADOR DE MILES.
        $("#txt_monto").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        // .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
                });
            }
        });
    </script>


</body>

</html>