<?php
session_start();
include "../conexion.php";

$busqueda = '';
if (isset($_REQUEST['busqueda']) && $_REQUEST['busqueda'] == '') {
    header("location: cobranzas.php");
}

if (!empty($_REQUEST['busqueda'])) {
    // if (!is_numeric($_REQUEST['busqueda'])) {
    //     header("location: cobranzas.php");
    // }
    $busqueda = $_REQUEST['busqueda'];
    // $where    = "cl.ci_ruc LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%'";
    $where    = "(cl.ci_ruc LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%')";
    $buscar   = "busqueda = $busqueda";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Cobranzas</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="far fa-newspaper"></i> Lista de Cuotas</h1>

        <form action="buscar_cobranzas.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="No. C.I / RUC" value="<?php echo $busqueda; ?>">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <!-- <div>
            <h5>Buscar por fecha</h5>
            <form action="buscar_venta.php" method="get" class="form_search_date">
                <label for="">De: </label>
                <input type="date" name="fecha_de" id="fecha_de" required>
                <label for=""> A </label>
                <input type="date" name="fecha_a" id="fecha_a" required>
                <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
            </form>
        </div> -->

        <table>
            <tr>
                <th>No. Venta</th>
                <th>C.I - RUC</th>
                <th>Cliente</th>
                <th>Obra</th>
                <th>Cuotas</th>
                <th>Fecha Vencimiento</th>
                <th>Días atrasados</th>
                <th>Interés - Mora</th>
                <th>Fecha Pago</th>
                <th>Estado</th>
                <th>Monto Cuota</th>
                <th>Pago</th>
                <th>Saldo</th>
                <th class="textright">Acciones</th>
                <th></th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM cuotas c
            inner join ventas v ON c.idVenta = v.idVenta
            INNER JOIN clientes cl ON v.idCliente = cl.idCliente
            WHERE (cl.ci_ruc LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%')  AND c.status = 1;");
            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];
            $por_pagina = 36;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT
            c.id,
            c.idVenta,
            c.cantCuota,
            c.noCuota,
            c.monto,
            c.fechaVencimiento,
            c.fechaPago,
            c.montoRecibido,
            c.idUsuario,
            c.saldo,
            c.status,
            v.idUsuario,
            v.idCliente,
            u.nombre as usuario,
            cl.nombre as cliente,
            cl.ci_ruc,
            ob.NOMBRE AS nombreObra,
            TIMESTAMPDIFF(DAY, c.fechaVencimiento, curdate()) AS diasAtrasados
            
            FROM cuotas c
            INNER JOIN ventas v ON c.idVenta = v.idVenta
            INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
            INNER JOIN clientes cl ON v.idCliente = cl.idCliente 
             LEFT JOIN OBRA ob ON(ob.ID_OBRA = v.ID_OBRA) 
            WHERE (cl.ci_ruc LIKE '%$busqueda%' OR cl.nombre LIKE '%$busqueda%')
            AND c.status = 1
            LIMIT $desde,$por_pagina");

            mysqli_close($conection);
            //$mora = $data['diasAtrasados'] * 500;
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['status'] == 1) {
                        $status = '<span class="anulada">Sin Pago</span>';
                        $interes = $data['monto'] * 0.0011;
                        $mora = round($data['diasAtrasados'] * $interes);
                        if ($mora > 0) {
                            $mora = $mora;
                        } else {
                            $mora = '0';
                        }
                    } else {
                        $status = '<span class="pagada">Pagado</span>';
                        $mora = '';
                    }

            ?><tr id="row_<?php echo $data['id']; ?>">
                        <td><?php echo $data['idVenta']; ?></td>
                        <td><?php echo $data['ci_ruc']; ?></td>
                        <td><?php echo $data['cliente']; ?></td>
                        <td><?php echo $data['nombreObra']; ?></td>
                        <td><?php echo $data['noCuota']; ?> / <?php echo $data['cantCuota']; ?></td>
                        <td><?php echo $data['fechaVencimiento']; ?></td>
                        <td><?php echo $data['diasAtrasados']; ?></td>

                        <td>Gs. <?php echo number_format($mora, 0, ",", "."); ?></td>
                        <td><?php echo $data['fechaPago']; ?></td>
                        <td class="celstatus"><?php echo $status; ?></td>
                        <td class="textright totalfactura">Gs. <?php echo number_format($data['monto'], 0, ",", "."); ?></td>
                        <!-- <td class="textright totalfactura">Gs. <?php echo number_format(($data['monto'] + ($mora)), 0, ",", "."); ?></td> -->
                        <td>Gs. <?php echo number_format($data['montoRecibido'], 0, ",", "."); ?></td>
                        <td>Gs. <?php echo number_format($data['saldo'], 0, ",", "."); ?></td>
                        <td>
                            <!-- <a href="#" cuota="<?php echo $data['id']; ?>" class="link_add add_cobrar"><i class="fas fa-plus"></i> Cobrar</a> -->
                            <a href="#" no_cuota="<?php echo $data['noCuota']; ?>" cuota="<?php echo $data['id']; ?>" id_cliente="<?php echo $data['idCliente'];  ?>" id_Venta="<?php echo $data['idVenta'];  ?>" class="link_add add_cobrar"><i class="fas fa-plus"></i> Cobrar</a>
                        </td>
                        <td>
                            <a href="#" id_venta="<?php echo $data['idVenta']; ?>" class="mostrar_lista_producto"><span style="font-size:18px; color: #000;"><i class="far fa-list-alt"></i></span></a>

                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
        <div class="paginador">
            <ul>
                <?php
                if ($pagina != 1) {
                ?>
                    <li>
                        <a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|< </a>
                    </li>
                    <li>
                        <a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>">
                            << </a>
                    </li>
                <?php
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '&busqueda=' . $busqueda . '">' . $i . '</a></li>';
                    }
                }
                if ($pagina != $total_paginas) {
                ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>> </a></li>
                    <li><a href="?pagina=<?php echo $total_paginas ?>&busqueda=<?php echo $busqueda; ?>">>| </a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>