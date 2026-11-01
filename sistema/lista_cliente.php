<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de Clientes</title>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <section id="container">
        <h1>Lista de Clientes</h1>
        <a href="registro_cliente.php" class="btn_new">Crear Cliente</a>

        <form action="buscar_cliente.php" method="GET" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <input type="submit" value="Buscar" class="btn_search">
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>RUC-CI</th>
                <th>Dirección</th>
                <th>Celular 1</th>
                <th>Celular 2</th>
                <th>Teléfono 1</th>
                <th>Teléfono 2</th>
                <!-- <th>Referencia 1</th> -->
                <!-- <th>Referencia 2</th> -->
                <!-- <th>Referencia 3</th> -->
                <th>Acciones</th>
            </tr>
            <?php
            //paginacion
            $query_register = mysqli_query($conection, "SELECT COUNT(*) as total_registro FROM clientes WHERE status = 1");
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


            $query = mysqli_query($conection, "SELECT * FROM clientes WHERE status = 1 ORDER BY idCliente ASC LIMIT $desde,$por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td><?php echo $data['idCliente']; ?></td>
                        <td><?php echo $data['nombre']; ?></td>
                        <td><?php echo $data['ci_ruc']; ?></td>
                        <td><?php echo $data['direccion']; ?></td>
                        <td><?php echo $data['celular1']; ?></td>
                        <td><?php echo $data['celular2']; ?></td>
                        <td><?php echo $data['telefono1']; ?></td>
                        <td><?php echo $data['telefono2']; ?></td>
                        <!-- <td><?php echo $data['referencia1']; ?></td> -->
                        <!-- <td><?php echo $data['referencia2']; ?></td> -->
                        <!-- <td><?php echo $data['referencia3']; ?></td> -->
                        <td>
                            <a title="Editar" href="editar_cliente.php?id=<?php echo $data['idCliente']; ?>" class="link_edit"><i class="far fa-edit"></i></a>
                            <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                                
                            <a title="Eliminar" href="eliminar_confirmar_cliente.php?id=<?php echo $data['idCliente']; ?>" class="link_delete"><i class="far fa-trash-alt"></i></a>
                            <?php } ?>
                            
                             <!-- estado de cuenta - lista completa -->
                            <button title="Estado de Cuenta - Completo"  style="border: none; cursor:pointer;" class="btn_viewn view_factura" cl="<?php echo $data['idCliente']; ?>"><i class="far fa-list-alt"></i></button>
                                    <!-- estado de cuenta - cuotas sin pagar -->
                                    
                            <button title="Estado de Cuenta - Sin Pagos" style="border: none; cursor:pointer;" class="btn_viewn view_estado_cuenta" cl="<?php echo $data['idCliente']; ?>"><i class="fas fa-list"></i></button>
                            <!--  Muestra extrato de cliente sin cobrar los intereses  -->
                            <button title="Estado de Cuenta - Sin Pagos/Interes" style="border: none; cursor:pointer;" class="btn_viewn view_estado_cuentaSinInteres" cl="<?php echo $data['idCliente']; ?>"><i class="fas fa-list"></i></button>
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