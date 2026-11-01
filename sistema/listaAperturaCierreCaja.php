<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Apertura/Cierre Caja</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1><i class="fas fa-cube"></i> Lista de Apertura/Cierre Caja</h1>
        <a href="aperturaCaja.php" class="btn_new"><i class="fas fa-plus"></i> Crear Apertura Caja</a>
        <!-- <form action="buscar_producto.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form> -->

        <table>
            <tr>
                <th>ID</th>
                <th>Fecha Apertura</th>
                <th>Fecha Cierre</th>
                <th>Monto Inicial</th>
                <th>Monto Total al cerrar</th>
                <th>Saldo/Monto Caja</th>
                <th>Monto a Retirar</th>
                <th>Estado</th>
                <!-- <th>
                    <?php
                    $query_proveedor = mysqli_query($conection, "SELECT idProveedor, nombre FROM proveedores WHERE status = 1 ORDER BY nombre ASC");
                    $result_proveedor = mysqli_num_rows($query_proveedor);
                    ?>
                    <select name="proveedor" id="search_proveedor">
                        <option value="" selected>PROVEEDOR</option>
                        <?php if ($result_proveedor > 0) {
                            while ($proveedor = mysqli_fetch_array($query_proveedor)) {
                        ?>
                                <option value="<?php echo $proveedor['idProveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
                        <?php
                            }
                        }
                        ?>

                    </select>
                </th> -->
                <!-- <th>Foto</th> -->
                <th>Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM controlCaja WHERE status = 1");
            $result_register = mysqli_fetch_array($query_register);
            $total_registro = $result_register['total_registro'];
            $por_pagina = 20;
            if (empty($_GET['pagina'])) {
                $pagina = 1;
            } else {
                $pagina = $_GET['pagina'];
            }
            $desde = ($pagina - 1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT * FROM controlCaja ORDER BY id DESC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
                    // if ($data['foto'] != 'img_producto.jpg') {
                    //     $foto = 'img/uploads/' . $data['foto'];
                    // } else {
                    //     $foto = 'img/' . $data['foto'];
                    // }
            ?>
                        <tr class="row<?php echo $data['id']; ?>">
                        <td><?php echo $data['id']; ?></td>
                        <td><?php echo $data['fechaApertura']; ?></td>
                        <td><?php echo $data['fechaCierre']; ?></td>
                        <td><?php echo number_format($data['monto_inicial'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['montoCierre'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['saldo'], 0, ",", "."); ?></td>
                        <td><?php echo number_format($data['monto_a_retirar'], 0, ",", "."); ?></td>
                        <td><?php echo $data['status']; ?></td>

                        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                            <td>
                                <a href="cerrarCaja.php?id=<?php echo $data['id']; ?>" class="link_edit"><i class="far fa-edit"></i> Cerrar</a>
                            </td>
                        <?php } ?>
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
                    <li><a href="?pagina=<?php echo 1; ?>">|< </a>
                    </li>
                    <li><a href="?pagina=<?php echo $pagina - 1; ?>">
                            << </a>
                    </li>
                <?php
                }
                for ($i = 1; $i <= $total_paginas; $i++) {
                    if ($i == $pagina) {
                        echo '<li class="pageSelected">' . $i . '</li>';
                    } else {
                        echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($pagina != $total_paginas) {
                ?>
                    <li><a href="?pagina=<?php echo $pagina + 1; ?>">>> </a></li>
                    <li><a href="?pagina=<?php echo $total_paginas ?>">>| </a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>

    <?php include "includes/footer.php"; ?>
</body>

</html>