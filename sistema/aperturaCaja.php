<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}

 //Formato fecha con hora
 date_default_timezone_set('America/Asuncion');
$fecha_actual = date('Y-m-d H:i:s');

include "../conexion.php";

$ultimoIdApertura = mysqli_query($conection, "SELECT MAX(id) AS ultimoId FROM controlCaja WHERE status = 'C'");
$resultUltimoIdApertura = mysqli_num_rows($ultimoIdApertura);
if($resultUltimoIdApertura > 0){
    $dataultimoIdApertura = mysqli_fetch_assoc($ultimoIdApertura);
}
$idUltimoApertura = $dataultimoIdApertura['ultimoId'];

$ultimoSaldoApertura = mysqli_query($conection,"SELECT saldo FROM controlCaja WHERE id = $idUltimoApertura; ");
$resultUltimoSaldoApertura = mysqli_num_rows($ultimoSaldoApertura);
if($resultUltimoSaldoApertura > 0){
    $dataUltimoSaldoApertura = mysqli_fetch_assoc($ultimoSaldoApertura);
}
$ultimoSaldo = $dataUltimoSaldoApertura['saldo'];



if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['fechaInicial']) || empty($_POST['montoFinal'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
       
        $fechaInicial = $_POST['fechaInicial'];
        $montoInicial = intval(str_replace(".", "", $_POST['montoFinal']));

        $query_insert = mysqli_query($conection, "INSERT INTO controlCaja(fechaApertura,monto_inicial) VALUES('$fechaInicial','$montoInicial')");

        if ($query_insert) {
            $alert = '<p class="mgs_save">Apertura procesado correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al procesar</p>';
        }
        header("Location: listaAperturaCierreCaja.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Apertura Caja</title>
</head>

<body>
    <?php include "includes/header.php";
     //Formato fecha con hora
    //  date_default_timezone_set('America/Asuncion');
    //  $fecha_actual("Y-m-d H:i:s");
    
    ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Apertura Caja</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST" enctype="multipart/form-data">

                <label for="fechaInicial">Fecha Apertura</label>
                <input type="datetime" name="fechaInicial" id="fechaInicial" placeholder="Fecha Inicial" value="<?= $fecha_actual ?>">

                <label for="montoCaja">Efectivo en Caja</label>
                <input type="text" name="montoCaja" id="montoCaja"  value="<?= number_format($ultimoSaldo, 0, ",", ".") ?>">

                <label for="montoInicial">Importe Apertura*</label>
                <input type="text" name="montoInicial" id="montoInicial" placeholder="Monto Inicial">

                <label for="montoFinal">Total Efectivo Caja</label>
                <input type="text" name="montoFinal" id="montoFinal" placeholder="Monto Final">

                <!-- <label for="montoaRetirar">Monto a Retirar</label>
                <input type="text" name="montoaRetirar" id="montoaRetirar" placeholder="Monto a Retirar">

                <label for="montoCaja">Monto Caja</label>
                <input type="text" name="montoCaja" id="montoCaja" placeholder="Monto Caja">


                <label for="fechaCierre">Fecha Cierre</label>
                <input type="date" name="fechaCierre" id="fechaCierre" placeholder="Fecha Cierre"> -->

                <button type="submit" class="btn_save"><i class="far fa-save"></i> Guardar Apertura</button>
            </form>
        </div>
    </section>

    <script type="text/javascript">
        //SEPARADOR DE MILES.
        $("#montoInicial").on({
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

        // $("#montoaRetirar").on({
        //     "focus": function(event) {
        //         $(event.target).select();
        //     },
        //     "keyup": function(event) {
        //         $(event.target).val(function(index, value) {
        //             return value.replace(/\D/g, "")
        //                 .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        //         });
        //     }
        // });

        //INICIO: calculo para saldo
        $('#montoInicial').keyup(function(e) {
            e.preventDefault();
            var montoCaja = numberSinComas($('#montoCaja').val())
            var montoInicial = numberSinComas($(this).val());
            var saldo = parseInt(montoCaja ) + parseInt( montoInicial);
            if (saldo < 0) {
                $('#montoFinal').val('0');
            } else {
                $('#montoFinal').val(numberWithCommas(saldo));
                // $('#vuelto').val('0');
            }
        })
        //FIN: calculo para saldo
        function numberWithCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function numberSinComas(number) {
            return number.toString().replace(/[.]/g, '');
        }



        
    </script>




    <?php include "includes/footer.php"; ?>
</body>

</html>