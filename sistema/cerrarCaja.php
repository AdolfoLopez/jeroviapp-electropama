<?php
session_start();
if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("Location: ./");
}
include "../conexion.php";

//Formato fecha con hora
date_default_timezone_set('America/Sao_Paulo');
$fecha_actual = date('Y-m-d H:i:s');

    $idAperturaCaja = $_REQUEST['id'];
    if (!is_numeric($idAperturaCaja)) {
        header("Location: listaAperturaCierreCaja.php");
    }
    $query_cierre = mysqli_query($conection, "SELECT * FROM controlCaja WHERE id = $idAperturaCaja AND status = 'A'");
    $result_cierre = mysqli_num_rows($query_cierre);

    if ($result_cierre > 0) {
        $data_cierre = mysqli_fetch_assoc($query_cierre);
    }
    $fechaAperturaCaja = $data_cierre['fechaApertura'];

$queryTotalVenta = mysqli_query($conection, "SELECT SUM(monto) as totalVenta FROM cuotas WHERE status = '2' AND fechaPago BETWEEN '$fechaAperturaCaja' AND '$fecha_actual';");
$resultQueryTotalVenta = mysqli_num_rows($queryTotalVenta);
if ($resultQueryTotalVenta > 0) {
    $dataQueryTotalVenta = mysqli_fetch_assoc($queryTotalVenta);
}

$queryTotalGasto        = mysqli_query($conection, "SELECT sum(totalGasto) as totalGastos FROM gastos WHERE fecha BETWEEN '$fechaAperturaCaja' AND '$fecha_actual';");
$resultQueryTotalGasto  = mysqli_num_rows($queryTotalGasto);
if ($resultQueryTotalGasto > 0) {
    $dataQueryResultGasto = mysqli_fetch_assoc($queryTotalGasto);
}


$montoCaja = ($data_cierre['monto_inicial'] + $dataQueryTotalVenta['totalVenta']) - $dataQueryResultGasto['totalGastos'];


if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['id']) || empty($_POST['montoaRetirar']) || empty($_POST['fechaCierre'])) {
        $alert = '<p class="mgs_error">Todos los campos son obligatorios</p>';
    } else {
        $idAperturaCaja = $_POST['id'];
        $montoaRetirar  = intval(str_replace(".", "", $_POST['montoaRetirar']));
        $saldo          = intval(str_replace(".", "", $_POST['saldo']));
        $fechaCierre    = $_POST['fechaCierre'];
        $montoCierre    = intval(str_replace(".", "", $_POST['montoCaja']));

        $query_update = mysqli_query($conection, "UPDATE controlCaja SET 
                                                    monto_a_retirar = '$montoaRetirar',
                                                    saldo = '$saldo',
                                                    fechaCierre = '$fechaCierre',
                                                    status = 'C',
                                                    montoCierre = '$montoCierre'
                                                    WHERE id = $idAperturaCaja
                                                    ");

        if ($query_update) {

            $alert = '<p class="mgs_save">Caja cerrado correctamente</p>';
        } else {
            $alert = '<p class="mgs_error">Error al cerrar caja</p>';
        }
    }
}

if (empty($_REQUEST['id'])) {
    header("Location: lista_producto.php");
} else {
    $idAperturaCaja = $_REQUEST['id'];
    if (!is_numeric($idAperturaCaja)) {
        header("Location: lista_producto.php");
    }

    $query_cierre = mysqli_query($conection, "SELECT * FROM controlCaja WHERE id = $idAperturaCaja AND status = 'A'");
    $result_cierre = mysqli_num_rows($query_cierre);

    if ($result_cierre > 0) {
        $data_cierre = mysqli_fetch_assoc($query_cierre);
    } else {
        header("Location: listaAperturaCierreCaja.php");
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Cerrar Caja</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <div class="form_register">
            <h1><i class="fas fa-cubes"></i> Cerrar Caja</h1>
            <hr>
            <div class="alert"><?php echo isset($alert) ? $alert : ''; ?> </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data_cierre['id']; ?>">

                <label for="fechaApertura">Fecha Apertura</label>
                <input type="datetime" name="fechaApertura" id="fechaApertura" placeholder="fechaApertura" value="<?php echo $data_cierre['fechaApertura']; ?>">

                <label for="montoApertura">Monto Inicial</label>
                <input type="text" name="montoApertura" id="montoApertura" placeholder="montoApertura" value="<?php echo number_format($data_cierre['monto_inicial'], 0, ",", "."); ?>">

                <label for="montoCaja">Monto Caja</label>
                <input type="text" name="montoCaja" id="montoCaja" placeholder="Monto total en caja" value="<?= number_format($montoCaja, 0, ",", "."); ?>">

                <label for="montoaRetirar">Monto a retirar</label>
                <input type="text" name="montoaRetirar" id="montoaRetirar" placeholder="montoaRetirar" value="">

                <label for="saldo">Saldo</label>
                <input type="text" name="saldo" id="saldo" placeholder="Saldo" value="">

                <label for="fechaCierre">Fecha Cierre</label>
                <input type="datetime" name="fechaCierre" id="fechaCierre" placeholder="fechaCierre" value="<?= $fecha_actual; ?>">

                <button type="submit" class="btn_save"><i class="far fa-save"></i> CERRAR CAJA</button>
            </form>
        </div>
    </section>
    <script type="text/javascript">
        //SEPARADOR DE MILES.
        $("#montoaRetirar").on({
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
        
        //INICIO: calculo para saldo
        $('#montoaRetirar').keyup(function(e) {
            e.preventDefault();
            var montoCaja = numberSinComas($('#montoCaja').val())
            var montoaRetirar = numberSinComas($(this).val());
            var saldo = (montoCaja - montoaRetirar);
            if (saldo < 0) {
                $('#saldo').val('0');
            } else {
                $('#saldo').val(numberWithCommas(saldo));
                $('#vuelto').val('0');
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