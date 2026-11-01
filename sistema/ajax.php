<?php
include "../conexion.php";
session_start();

if (!empty($_POST)) {
    //estraer datos del producto
    if ($_POST['action'] == 'infoProducto') {
        $idProducto = $_POST['producto'];
        $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE idProducto = $idProducto AND status = 1");
        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }

    //estraer datos de cuotas
    if ($_POST['action'] == 'infoCuotas') {
        $idCuotas = $_POST['id_Cuota'];
        $idVenta  = $_POST['id_venta'];
        $no_cuota = $_POST['no_cuota'];
        $query_min_cuota = (mysqli_query($conection, "SELECT MIN(noCuota) AS minCuota FROM cuotas WHERE idVenta = $idVenta AND status = 1"));
        $result_query_min_cuota = mysqli_num_rows($query_min_cuota);
        if ($result_query_min_cuota > 0) {
            $min_cuota = mysqli_fetch_assoc($query_min_cuota);
        } else {
            echo 'error';
        }

        if ($min_cuota['minCuota'] == $no_cuota) {
            $query  = mysqli_query($conection,  "SELECT  *,
            TIMESTAMPDIFF(DAY, fechaVencimiento, curdate()) AS diasAtrasados,
            (TIMESTAMPDIFF(DAY, fechaVencimiento, curdate()) * 500) + monto AS montoMora,
            TIMESTAMPDIFF(DAY, fechaVencimiento, curdate()) * (monto * 0.0011) + monto AS valorMora,
           
            montoRecibido + saldo as montoTotal
            FROM cuotas
            WHERE id = $idCuotas
            AND status = 1
    ");
            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error en infoCuotas';
            exit;
        } else {
            echo 'error';
        }
    }

    /*_______LISTA DE PRODUCTOS PARA BUSCADOR*/
    if ($_POST['action'] == 'lista_productos') {
        $query = mysqli_query($conection, "SELECT * FROM productos");
        mysqli_close($conexion);
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error en listar productos';
        exit;
    }
    /*_____FIN lista de productos para buscador_____*/

    if ($_POST['action'] == 'cobrar_cuota') {
        if (!empty($_POST['id_cuota'])) {
            $id_Cuota = $_POST['id_cuota'];
            $usuario_id = $_SESSION['idUser'];
            $monto_recibido = intval(str_replace(".", "", $_POST['monto_recibido']));
            $ultimoPago = intval(str_replace(".", "", $_POST['monto_recibido']));
            $mora = intval(str_replace(".", "", $_POST['mora']));
            $saldo = intval(str_replace(".", "", $_POST['saldo']));
            $pago = intval(str_replace(".", "", $_POST['pago']));
            $monto = intval(str_replace(".", "", $_POST['monto'])); //monto_a_cobrar, puede ser con o sin interes.
            $montoCuota = intval(str_replace(".", "", $_POST['montoCuota'])); //es monto de la cuota sin intereses.
            $montoSaldo = intval(str_replace(".", "", $_POST['montoSaldo']));

            if ($monto_recibido < $monto && $monto_recibido < $montoCuota) {
                $monto_recibido = $monto_recibido + $pago;
                $update = mysqli_query($conection, "UPDATE cuotas SET fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = $saldo, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto_recibido == $monto) {
                $monto_recibido = $monto_recibido + $pago;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto_recibido > $monto && $pago > 0) {
                $monto_recibido = $pago + $montoSaldo;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto_recibido > $monto && $monto == $montoCuota) {
                $monto_recibido = $montoCuota;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto_recibido > $monto && $monto <> $montoCuota) {
                $monto_recibido = $montoCuota + $mora;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto == $montoCuota && $mora > 0) {
                $monto_recibido = $monto_recibido;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto > $montoCuota) {
                $monto_recibido = $monto_recibido + $mora;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            } else if ($monto < $montoCuota && $saldo > 0) {
                $monto_recibido = $monto_recibido;
                $update = mysqli_query($conection, "UPDATE cuotas SET status = 2, fechaPago = now(), idUsuario = $usuario_id, montoRecibido = $monto_recibido, mora = $mora, saldo = 0, ultimoPago = $ultimoPago WHERE id = $id_Cuota");
                $selectUpdate = mysqli_query($conection, "SELECT * from cuotas WHERE id = $id_Cuota");
            }

            //FIN:  descontado el vuelto de la siguiente cuota
            $result_pro = mysqli_num_rows($selectUpdate);
            if ($result_pro > 0) {
                $data = mysqli_fetch_assoc($selectUpdate);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                echo 'error';
            }

            mysqli_close($conection);
        } else {
            // echo 'error';
        }
        exit;
    }

    //*******INICIO******* BUSCAR PRODUCTO PARA VENTA al clicar en el resultado del filtro*/
    if ($_POST['action'] == 'infoProductoRef2') {
        $referencia = $_POST['id_detalle'];
        $listaPrecio = $_POST['listaPrecio'];
        if ($listaPrecio == 1) {
            $query2  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE idProducto = '$referencia' AND status = 1 AND precioVenta != 0 OR precioVenta != null");
        }
        if ($listaPrecio == 2) {
            $query2  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta2 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE idProducto = '$referencia' AND status = 1 AND precioVenta2 != 0 OR precioVenta2 != null");
        }
        if ($listaPrecio == 3) {
            $query2  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta3 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE idProducto = '$referencia' AND status = 1 AND precioVenta3 != 0 OR precioVenta3 != null");
        }
        if ($listaPrecio == 4) {
            $query2  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta4 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE idProducto = '$referencia' AND status = 1 AND precioVenta4 != 0 OR precioVenta4 != null");
        }
        // $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");


        mysqli_close($conection);

        $result2 = mysqli_num_rows($query2);
        if ($result2 > 0) {
            $data2 = mysqli_fetch_assoc($query2);
            echo json_encode($data2, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA VENTA*/

    //*******INICIO******* BUSCAR PRODUCTO PARA COMPRA al clicar en el resultado del filtro*/
    if ($_POST['action'] == 'infoProductoRefCom') {
        $referencia = $_POST['id_detalleCom'];
        $query2  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioCosto,porc_max_desc,codigoDeBarra FROM productos WHERE idProducto = '$referencia' AND status = 1");
        mysqli_close($conection);

        $result2 = mysqli_num_rows($query2);
        if ($result2 > 0) {
            $data2 = mysqli_fetch_assoc($query2);
            echo json_encode($data2, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA COMPRA*/

    //*******INICIO******* BUSCAR PRODUCTO PARA INVENTARIO al clicar en el resultado del filtro*/
    if ($_POST['action'] == 'infoProductoRefInv') {
        $referencia = $_POST['id_detalle'];
        $query3  = mysqli_query($conection, "SELECT idProducto, referencia, nombre, existencia, codigoDeBarra FROM productos WHERE idProducto LIKE '%$referencia%' AND status = 1");
        mysqli_close($conection);
        $result3 = mysqli_num_rows($query3);
        if ($result3 > 0) {
            $data3 = mysqli_fetch_assoc($query3);
            echo json_encode($data3, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA INVENTARIO*/

    //*******INICIO******* BUSCAR CLIENTE PARA VENTA al clicar en el resultado del filtro*/
    if ($_POST['action'] == 'infoCliente2') {
        $idCliente = $_POST['id_cliente'];
        $queryCliente2  = mysqli_query($conection, "SELECT * FROM clientes WHERE idCliente = '$idCliente' AND status = 1");
        mysqli_close($conection);

        $resultCliente2 = mysqli_num_rows($queryCliente2);
        if ($resultCliente2 > 0) {
            $data2 = mysqli_fetch_assoc($queryCliente2);
            echo json_encode($data2, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR CLIENTE PARA VENTA*/

    //*******INICIO******* BUSCAR PROVEEDOR PARA COMPRA al clicar en el resultado del filtro*/
    if ($_POST['action'] == 'infoProveedor2') {
        $idProveedor = $_POST['idProveedor'];
        $queryProveedor2  = mysqli_query($conection, "SELECT * FROM proveedores WHERE idProveedor = '$idProveedor' AND status = 1");
        mysqli_close($conection);

        $resultProveedor2 = mysqli_num_rows($queryProveedor2);
        if ($resultProveedor2 > 0) {
            $data2 = mysqli_fetch_assoc($queryProveedor2);
            echo json_encode($data2, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PROVEEDOR PARA COMPRA*/


    //*******INICIO******* BUSCAR PRODUCTO PARA VENTA*/
    if ($_POST['action'] == 'infoProductoRef') {
        $referencia = $_POST['producto'];
        $listaPrecio = $_POST['listaPrecio'];
        if ($listaPrecio == 1) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE referencia = '$referencia' AND status = 1 AND precioVenta != 0 OR precioVenta != null");
        }
        if ($listaPrecio == 2) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta2 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE referencia = '$referencia' AND status = 1 AND precioVenta2 != 0 OR precioVenta2 != null");
        }
        if ($listaPrecio == 3) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta3 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE referencia = '$referencia' AND status = 1 AND precioVenta3 != 0 OR precioVenta3 != null");
        }
        if ($listaPrecio == 4) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta4 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE referencia = '$referencia' AND status = 1 AND precioVenta4 != 0 OR precioVenta4 != null");
        }
        // $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");


        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA VENTA*/

    //*******INICIO******* BUSCAR PRODUCTO PARA PRESUPUESTO*/
    if ($_POST['action'] == 'infoProductoRefPresupuesto') {
        $referencia = $_POST['producto'];
        $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA PRESUPUESTO*/

    //*******INICIO******* BUSCAR PRODUCTO por codigo de barra PARA VENTA*/
    if ($_POST['action'] == 'infoProductoCodigoBarra') {
        $codigoDeBarra = $_POST['producto'];
        $listaPrecio = $_POST['listaPrecio'];
        if ($listaPrecio == 1) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE codigoDeBarra = '$codigoDeBarra' AND status = 1 AND precioVenta != 0 OR precioVenta != null");
        }
        if ($listaPrecio == 2) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta2 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE codigoDeBarra = '$codigoDeBarra' AND status = 1 AND precioVenta2 != 0 OR precioVenta2 != null");
        }
        if ($listaPrecio == 3) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta3 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE codigoDeBarra = '$codigoDeBarra' AND status = 1 AND precioVenta3 != 0 OR precioVenta3 != null");
        }
        if ($listaPrecio == 4) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta4 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE codigoDeBarra = '$codigoDeBarra' AND status = 1 AND precioVenta4 != 0 OR precioVenta4 != null");
        }
        // $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");


        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO PARA VENTA*/

    //*******INICIO******* BUSCAR PRODUCTO nombre PARA VENTA*/
    if ($_POST['action'] == 'infoProductoNombre') {
        $nombre = $_POST['producto'];
        $listaPrecio = $_POST['listaPrecio'];
        if ($listaPrecio == 1) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE nombre like '%$nombre%' AND status = 1 AND precioVenta != 0 OR precioVenta != null LIMIT 15");
        }
        if ($listaPrecio == 2) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta2 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE nombre like '%$nombre%' AND status = 1 AND precioVenta2 != 0 OR precioVenta2 != null LIMIT 15");
        }
        if ($listaPrecio == 3) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta3 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE nombre like '%$nombre%' AND status = 1 AND precioVenta3 != 0 OR precioVenta3 != null LIMIT 15");
        }
        if ($listaPrecio == 4) {
            $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta4 as precioVenta,porc_max_desc,codigoDeBarra FROM productos WHERE nombre like '%$nombre%' AND status = 1 AND precioVenta4 != 0 OR precioVenta4 != null LIMIT 15");
        }
        // $query  = mysqli_query($conection, "SELECT idProducto,referencia,nombre,existencia,precioVenta FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            // $data = mysqli_fetch_assoc($query);
            // echo json_encode($data, JSON_UNESCAPED_UNICODE);


            while ($data = mysqli_fetch_assoc($query)) {

                $detalleTabla .= '
                    <tr>
                    <td>' . $data['idProducto'] . '</td>
                    <td>' . $data['referencia'] . '</td>
                    <td>' . $data['codigoDeBarra'] . '</td>
                    <td>' . $data['nombre'] . '</td>
                    <td>' . $data['existencia'] . '</td>
                    <td>' . number_format($data['precioVenta'], 0, ",", ".") . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); buscar_producto_resultado(' . $data['idProducto'] . ');"><i class="fas fa-plus"></i> </a>
                    </td>
                    <td hidden>' . $data['porc_max_desc'] . '</td>
                </tr>   
                    ';
            }
            $arrayData['detalle'] = $detalleTabla;
            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO nombre PARA VENTA*/

    //*******INICIO******* BUSCAR PRODUCTO nombre PARA COMPRA*/
    if ($_POST['action'] == 'infoProductoNombre2') {
        $nombre = $_POST['producto'];
        $query  = mysqli_query(
            $conection,
            "SELECT 
            idProducto,
            referencia,
            nombre,
            existencia,
            precioCosto,
            porc_max_desc,
            codigoDeBarra 
            FROM productos 
            WHERE (nombre like '%$nombre%' OR referencia LIKE '%$nombre%' OR codigoDeBarra LIKE '%$nombre%') AND status = 1 LIMIT 15"
        );

        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                $detalleTabla .= '
                <tr>
                <td>' . $data['idProducto'] . '</td>
                <td>' . $data['referencia'] . '</td>
                <td>' . $data['codigoDeBarra'] . '</td>
                <td>' . $data['nombre'] . '</td>
                <td>' . $data['existencia'] . '</td>
                <td>' . number_format($data['precioCosto'], 0, ",", ".") . '</td>
                <td class="">
                    <a href="#" class="link_delete" onclick="event.preventDefault(); buscar_producto_resultado_compra(' . $data['idProducto'] . ');"><i class="fas fa-plus"></i> </a>
                </td>
                <td hidden>' . $data['porc_max_desc'] . '</td>
            </tr>
                
                ';
            }

            $arrayData['detalle'] = $detalleTabla;

            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);

            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO nombre PARA COMPRA*/

    //*******INICIO******* BUSCAR PRODUCTO nombre PARA INVENTARIO*/
    if ($_POST['action'] == 'infoProductoNombreInventario') {
        $busqueda = $_POST['producto'];
        $query  = mysqli_query($conection, "SELECT idProducto, referencia, codigoDeBarra, nombre FROM productos WHERE referencia LIKE '%$busqueda%' OR codigoDeBarra LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' AND status = 1");
        mysqli_close($conection);
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                $detalleTabla .= '
                <tr>
                <td>' . $data['referencia'] . '</td>
                <td>' . $data['codigoDeBarra'] . '</td>
                <td>' . $data['nombre'] . '</td>
                <td class="">
                    <a href="#" class="link_delete" onclick="event.preventDefault(); buscar_producto_resultadoInv(' . $data['idProducto'] . ');"><i class="fas fa-plus"></i> </a>
                </td>
            </tr>
                ';
            }
            $arrayData['detalle'] = $detalleTabla;
            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR PRODUCTO nombre PARA INVENTARIO*/


    //*******INICIO******* BUSCAR cliente por nombre PARA VENTA*/
    if ($_POST['action'] == 'infoCliente') {
        $nombre = $_POST['cliente'];
        $queryCliente  = mysqli_query($conection, "SELECT * FROM clientes WHERE status = 1 and nombre LIKE '%$nombre%' LIMIT 15");
        mysqli_close($conection);

        $resultCliente = mysqli_num_rows($queryCliente);
        if ($resultCliente > 0) {
            while ($data = mysqli_fetch_assoc($queryCliente)) {

                $detalleTabla .= '
                    <tr>
                    <td>' . $data['idCliente'] . '</td>
                    <td>' . $data['ci_ruc'] . '</td>
                    <td>' . $data['nombre'] . '</td>
                    <td>
                        <a href="#" class="link_delete" onclick="event.preventDefault(); buscar_cliente_resultado(' . $data['idCliente'] . ');"><i class="fas fa-plus"></i> </a>
                    </td>
                </tr>
                    ';
            }
            $arrayData['detalle'] = $detalleTabla;
            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR cliente por nombre PARA VENTA*/

    //*******INICIO******* BUSCAR proveedor por nombre PARA COMPRA*/
    if ($_POST['action'] == 'infoProveedor') {
        $nombre = $_POST['proveedor'];
        $queryProveedor  = mysqli_query($conection, "SELECT * FROM proveedores WHERE status = 1 and nombre LIKE '%$nombre%' OR idProveedor = '$nombre' LIMIT 15");
        mysqli_close($conection);

        $resultProveedor = mysqli_num_rows($queryProveedor);
        if ($resultProveedor > 0) {
            while ($data = mysqli_fetch_assoc($queryProveedor)) {
                $detalleTabla .= '
                    <tr>
                    <td>' . $data['idProveedor'] . '</td>
                    <td>' . $data['ruc'] . '</td>
                    <td>' . $data['nombre'] . '</td>
                    <td>
                        <a href="#" class="link_delete" onclick="event.preventDefault(); buscar_proveedor_resultado(' . $data['idProveedor'] . ');"><i class="fas fa-plus"></i> </a>
                    </td>
                </tr>
                    ';
            }
            $arrayData['detalle'] = $detalleTabla;
            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    //*******FIN******* BUSCAR proveedor por nombre PARA COMPRA*/


    /*_______INICIO_______
        Estrae datos del producto por REFERECIA
        COMPRA
    */
    if ($_POST['action'] == 'infoProducto_ref') {
        $referencia = $_POST['producto'];
        // $query  = mysqli_query($conection, "SELECT idProducto, referencia, nombre, existencia, precioCosto FROM productos WHERE referencia LIKE '%$referencia%' AND status = 1");
        $query  = mysqli_query($conection, "SELECT idProducto, referencia, nombre, existencia, precioCosto FROM productos WHERE referencia = '$referencia' AND status = 1");

        mysqli_close($conection);

        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
    }
    /*_______FIN_______*/


    /*_______INICIO_______
        Buscar cliente    
        Ventas
    */
    if ($_POST['action'] == 'searchCliente') {
        if (!empty($_POST['cliente'])) {
            $ci = $_POST['cliente'];
            $query = mysqli_query($conection, "SELECT * FROM clientes WHERE ci_ruc LIKE '$ci' AND status = 1");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    /*_______FIN_______*/

    // ******* BUSCAR PRODUCTO *******
    if ($_POST['action'] == 'searchProduct') {
        if (!empty($_POST['buscar'])) {
            $buscar = $_POST['buscar'];
            $query = mysqli_query($conection, "SELECT 
            p.nombre as nombreProducto, 
            p.precioCosto, 
            p.existencia, 
            p.referencia, 
            pro.nombre as nombreProveedor, 
            m.nombre as nombreMarca,
            c.nombre as nombreCategoria,
            round((p.precioCosto * 20 / 100 + p.precioCosto) / 3) as precio20,
            round((p.precioCosto * 45 / 100 + p.precioCosto) / 6) as precio45,
            round((p.precioCosto * 70 / 100 + p.precioCosto) / 9) as precio70,
            round((p.precioCosto * 90 / 100 + p.precioCosto) / 12) as precio90
            FROM productos p
            INNER JOIN proveedores pro on (p.idProveedor = pro.idProveedor)
            INNER JOIN marcas m on (p.idMarca = m.idMarca)
            INNER JOIN categorias c on (p.idCategoria = c.idCategoria)  WHERE p.nombre LIKE '%$buscar%' AND p.status = 1");

            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    // ******* FIN BUSCAR PRODUCTO *******

    //*******INICIO******* buscar proveedor - venta
    if ($_POST['action'] == 'searchProveedor') {
        if (!empty($_POST['proveedor'])) {
            $nom = $_POST['proveedor'];
            $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE nombre LIKE '%$nom%' AND status = 1");
            mysqli_close($conection);
            $result = mysqli_num_rows($query);

            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    //*******FIN******* buscar proveedor - venta





    //Registrar CLIENTE - VENTAS
    if ($_POST['action'] == 'addCliente') {
        //print_r($_POST);
        $ci = $_POST['ci_cliente'];
        $nombre = $_POST['nom_cliente'];
        $telefono1 = $_POST['tel1_cliente'];
        $direccion = $_POST['dir_cliente'];
        $telefono2 = $_POST['tel2_cliente'];
        $celular1 = $_POST['cel1_cliente'];
        $celular2 = $_POST['cel2_cliente'];
        $referencia1 = $_POST['ref1_cliente'];
        $referencia2 = $_POST['ref2_cliente'];
        $referencia3 = $_POST['ref3_cliente'];
        $idUsuario = $_SESSION['idUser'];

        $query_insert = mysqli_query($conection, "INSERT INTO clientes(ci_ruc,nombre,direccion,celular1,celular2,telefono1,telefono2,referencia1,referencia2,referencia3,idUsuario) VALUES('$ci','$nombre','$direccion','$celular1','$celular2','$telefono1','$telefono2','$referencia1','$referencia2','$referencia3','$idUsuario')");

        if ($query_insert) {
            $idCliente = mysqli_insert_id($conection);
            $msg = $idCliente;
        } else {
            $msg = 'error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
    }

    //********INICIO*******Registrar Proveedor Compra
    if ($_POST['action'] == 'addProveedor') {
        //print_r($_POST);

        $nombre = $_POST['nom_proveedor'];
        $telefono = $_POST['tel_proveedor'];
        $direccion = $_POST['dir_proveedor'];
        $contacto = $_POST['con_proveedor'];
        $idUsuario = $_SESSION['idUser'];

        $query_insert = mysqli_query($conection, "INSERT INTO proveedores(nombre,direccion,telefono,contacto,idUsuario) VALUES('$nombre','$direccion','$telefono','$contacto','$idUsuario')");

        if ($query_insert) {
            $idProveedor = mysqli_insert_id($conection);
            $msg = $idProveedor;
        } else {
            $msg = 'error';
        }
        mysqli_close($conection);
        echo $msg;
        exit;
    }
    //********FIN*******Registrar Proveedor Compra

    // agregar producto al detalle temporal
    if ($_POST['action'] == 'addProductoDetalle') {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
            echo 'error';
        } else {
            $idProducto     = $_POST['producto'];
            $cantidad       = $_POST['cantidad'];
            // $porc_desc      = $_POST['porc_desc'];
            $porc_desc = (int) ($_POST['porc_desc'] ?? 0);
            $total_linea    = $_POST['total_linea'];
            $token          = md5($_SESSION['idUser']);
            $listaPrecio    = $_POST['listaPrecio'];

            // VERIFICAR CANTIDAD DE ITEMS EN TABLA TEMP
            $query_cant         = mysqli_query($conection, "SELECT * FROM detallesVentas_temp WHERE token_user = '$token'");
            $result_query_cant  = mysqli_num_rows($query_cant);
            if ($result_query_cant < 13) {
                $query_iva  = mysqli_query($conection, "SELECT iva FROM configuracion");
                $result_iva = mysqli_num_rows($query_iva);

                $query_detalle_temp = mysqli_query($conection, "CALL add_detalle_temp($idProducto,$cantidad,'$token',$porc_desc,'$total_linea','$listaPrecio')");
                $result = mysqli_num_rows($query_detalle_temp);

                $detalleTabla = '';
                $sub_total    = 0;
                $iva          = 0;
                $total        = 0;
                $arrayData    = array();

                if ($result > 0) {
                    if ($result_iva > 0) {
                        $info_iva = mysqli_fetch_assoc($query_iva);
                        $iva = $info_iva['iva'];
                    }
                    while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                        $precioTotal = $data['cant'] * $data['precioVenta'];
                        $total_linea = $precioTotal - (($precioTotal * $data['porc_desc']) / 100);
                        // $sub_total = $sub_total + $precioTotal;
                        $sub_total = $sub_total + $total_linea;

                        $total = $total + $precioTotal;

                        $detalleTabla .= '
                    <tr>
                    <td>' . $data['id'] . '</td>
                    <td>' . $data['referencia'] . '</td>
                    <td>' . $data['codigoDeBarra'] . '</td>
                    <td colspan="2">' . $data['nombre'] . '</td>
                    <td class="textcenter">' . $data['cant'] . '</td>
                    <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".") . '</td>
                    <td class="textright">' . $data['porc_desc'] . "%" . '</td>
                    <td class="textright">' . number_format($total_linea, 0, ",", ".") . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                    </td>
                </tr>
                    
                    ';
                    }
                    $impuesto = round(($sub_total * ($iva / 100)));
                    $tl_sniva = round($sub_total - $impuesto);
                    $total    = round($impuesto + $tl_sniva);

                    $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalleTotales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                } else {
                    echo 'error';
                }
                //mysqli_close($conection);
            } else {
                $respuesta = "negativo";
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            }
        }
        exit;
    }

    // agregar producto al detalle temporal PRESUPUESTO
    if ($_POST['action'] == 'addProductoDetallePresupuesto') {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
            echo 'error';
        } else {
            $idProducto = $_POST['producto'];
            $cantidad   = $_POST['cantidad'];
            $token      = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL add_detalle_temp_presupuesto($idProducto,$cantidad,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioVenta'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTabla .= '
                        <tr>
                        <td>' . $data['id'] . '</td>
                        <td>' . $data['referencia'] . '</td>
                        <td colspan="2">' . $data['nombre'] . '</td>
                        <td class="textcenter">' . $data['cant'] . '</td>
                        <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".") . '</td>
                        <td class="textright">' . number_format($precioTotal, 0, ",", ".") . '</td>
                        <td class="">
                            <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_presupuesto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                        </td>
                    </tr>
                        
                        ';
                }
                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotales = '
                        <tr>
                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                            <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                            <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">TOTAL Q.</td>
                            <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                        </tr>
                    ';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
        exit;
    }
    /*FIN AGREGAR PRODUCTO DETALLE*/

    // agregar gasto al detalle temporal - GASTO
    if ($_POST['action'] == 'addGastoDetalles') {
        if (empty($_POST['descripcion']) || empty($_POST['monto'])) {
            echo 'error';
        } else {
            $descripcion = $_POST['descripcion'];
            $monto   = $_POST['monto'];
            $token      = md5($_SESSION['idUser']);

            $query_detalle_temp = mysqli_query($conection, "CALL add_detalle_gasto_temp('$descripcion',$monto,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            //$sub_total    = 0;
            // $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                // if ($result_iva > 0) {
                //     $info_iva = mysqli_fetch_assoc($query_iva);
                //     $iva = $info_iva['iva'];
                // }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $total = $total + $data['monto'];

                    $detalleTabla .= '
                    <tr>
                    <td>' . $data['descripcion'] . '</td>
                    <td>' . number_format($data['monto'], 0, ",", ".") . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_gasto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                    </td>
                </tr>
                    
                    ';
                }

                $detalleTotales = '
                    <tr>
                        <td class="textright">TOTAL Gs.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';

                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
        exit;
    }

    //*******INICIO****** agregar producto al detalle temporal COMPRA
    if ($_POST['action'] == 'addProductoDetalleCompra') {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
            echo 'error';
        } else {
            $idProducto = $_POST['producto'];
            $cantidad   = $_POST['cantidad'];
            $token      = md5($_SESSION['idUser']);
            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_tempCompra = mysqli_query($conection, "CALL add_detalle_temp_Compra($idProducto,$cantidad,'$token')");
            $result = mysqli_num_rows($query_detalle_tempCompra);

            $detalleTablaCompra = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_tempCompra)) {

                    $precioTotal = $data['cant'] * $data['precioCompra'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTablaCompra .= '
                    <tr>
                    <td>' . $data['id'] . '</td>
                    <td>' . $data['referencia'] . '</td>
                    <td colspan="2">' . $data['nombre'] . '</td>
                    <td class="textcenter">' . $data['cant'] . '</td>
                    <td class="textright">' . number_format($data['precioCompra'], 0, ",", ".")  . '</td>
                    <td class="textright">' . number_format($precioTotal, 0, ",", ".")  . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalleCompra(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                    </td>
                </tr>
                    
                    ';
                }
                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotalesCompra = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';
                $arrayData['detalle'] = $detalleTablaCompra;
                $arrayData['totales'] = $detalleTotalesCompra;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            // mysqli_close($conection);
        }
        exit;
    }
    //*******FIN****** agregar producto al detalle temporal COMPRA

    //extrae datos del detalle temp
    if ($_POST['action'] == 'searchForDetalle') {
        if (empty($_POST['user'])) {
            echo 'error';
        } else {
            $token      = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT tmp.id,tmp.token_user,tmp.cant,tmp.precioVenta,p.idProducto,p.nombre,tmp.porc_desc,p.referencia,p.codigoDeBarra FROM detallesVentas_temp tmp INNER JOIN productos p ON tmp.idProducto = p.idProducto WHERE token_user = '$token' ");
            $result = mysqli_num_rows($query);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);


            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioVenta'];
                    $total_linea = $precioTotal - (($precioTotal * $data['porc_desc']) / 100);
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTabla .= '
                        <tr>
                            <td>' . $data['id'] . '</td>
                            <td>' . $data['referencia'] . '</td>
                            <td>' . $data['codigoDeBarra'] . '</td>
                            <td colspan="2">' . $data['nombre'] . '</td>
                            <td class="textcenter">' . $data['cant'] . '</td>
                            <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".")  . '</td>
                            <td class="textright">' . $data['porc_desc'] . "%"  . '</td>
                            <td class="textright">' . number_format($total_linea, 0, ",", ".")  . '</td>
                            <td class="">
                                <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                            </td>
                        </tr>
                    ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".")  . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".")  . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".")  . '</td>
                    </tr>
                ';
                $arrayData['detalleVenta'] = $detalleTabla;
                $arrayData['totalesVenta'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    //extrae datos del detalle temp PRESUPUESTO
    if ($_POST['action'] == 'searchForDetallePresupuesto') {
        if (empty($_POST['user'])) {
            echo 'error';
        } else {
            $token      = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT tmp.id,tmp.token_user,tmp.cant,tmp.precioVenta,p.idProducto,p.nombre FROM detallespresupuesto_temp tmp INNER JOIN productos p ON tmp.idProducto = p.idProducto WHERE token_user = '$token' ");
            $result = mysqli_num_rows($query);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);


            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioVenta'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTabla .= '
                            <tr>
                                <td>' . $data['id'] . '</td>
                                <td colspan="2">' . $data['nombre'] . '</td>
                                <td class="textcenter">' . $data['cant'] . '</td>
                                <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".")  . '</td>
                                <td class="textright">' . number_format($precioTotal, 0, ",", ".")  . '</td>
                                <td class="">
                                    <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_presupuesto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                                </td>
                            </tr>
                        ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotales = '
                        <tr>
                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                            <td class="textright">' . number_format($tl_sniva, 0, ",", ".")  . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                            <td class="textright">' . number_format($impuesto, 0, ",", ".")  . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">TOTAL Q.</td>
                            <td class="textright">' . number_format($total, 0, ",", ".")  . '</td>
                        </tr>
                    ';
                $arrayData['detalleVenta'] = $detalleTabla;
                $arrayData['totalesVenta'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }
    /*FIN EXTRAER DATOS DE PRESUPUESTO TEMP*/

    //extrae datos del detalle GASTO temp
    if ($_POST['action'] == 'searchForDetalleGasto') {
        if (empty($_POST['user'])) {
            echo 'error';
        } else {
            $token      = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT * FROM detallesGastos_temp  WHERE token_user = '$token' ");
            $result = mysqli_num_rows($query);

            $detalleTabla = '';
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    $total = $total + $data['monto'];

                    $detalleTabla .= '
                    <tr>
                    <td>' . $data['descripcion'] . '</td>
                    <td>' . number_format($data['monto'], 0, ",", ".") . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_gasto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                    </td>
                </tr>
                        ';
                }

                $detalleTotales = '
                <tr>
                <td class="textright">TOTAL Gs.</td>
                <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
            </tr>
                    ';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }

    //*******INICIO******* extrae datos del detalle temp COMPRA
    if ($_POST['action'] == 'searchForDetalleCompra') {
        if (empty($_POST['user'])) {
            echo 'error';
        } else {
            $token      = md5($_SESSION['idUser']);

            $query = mysqli_query($conection, "SELECT tmp.id,tmp.token_user,tmp.cant,tmp.precioCompra,p.idProducto,p.nombre, p.referencia FROM detallesCompras_temp tmp INNER JOIN productos p ON tmp.idProducto = p.idProducto WHERE token_user = '$token' ");
            $result = mysqli_num_rows($query);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);


            $detalleTablaCompra = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioCompra'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTablaCompra .= '
                        <tr>
                            <td>' . $data['id'] . '</td>
                            <td>' . $data['referencia'] . '</td>
                            <td colspan="2">' . $data['nombre'] . '</td>
                            <td class="textcenter">' . $data['cant'] . '</td>
                            <td class="textright">' . number_format($data['precioCompra'], 0, ",", ".") . '</td>
                            <td class="textright">' . number_format($precioTotal, 0, ",", ".") . '</td>
                            <td class="">
                                <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalleCompra(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                            </td>
                        </tr>
                    ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotalesCompra = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';
                $arrayData['detalle'] = $detalleTablaCompra;
                $arrayData['totales'] = $detalleTotalesCompra;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conection);
        }
        exit;
    }
    //*******FIN******* extrae datos del detalle temp COMPRA

    //eliminar datos del detalles ventas temp
    if ($_POST['action'] == 'del_producto_detalle') {
        if (empty($_POST['id_detalle'])) {
            echo 'error';
        } else {
            $id_detalle = $_POST['id_detalle'];
            $token      = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL del_detalle_temp($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);


            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioVenta'];
                    $total_linea = $precioTotal - (($precioTotal * $data['porc_desc']) / 100);
                    $sub_total = $sub_total + $total_linea;
                    $total = $total + $precioTotal;

                    $detalleTabla .= '
                        <tr>
                            <td>' . $data['id'] . '</td>
                            <td>' . $data['referencia'] . '</td>
                            <td>' . $data['codigoDeBarra'] . '</td>
                            <td colspan="2">' . $data['nombre'] . '</td>
                            <td class="textcenter">' . $data['cant'] . '</td>
                            <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".") . '</td>
                            <td class="textright">' . $data['porc_desc'] . "%" . '</td>
                            <td class="textright">' . number_format($total_linea, 0, ",", ".") . '</td>
                            <td class="">
                                <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                            </td>
                        </tr>
                    ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotales = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
    }

    //eliminar datos del detalles PRESUPUESTO temp
    if ($_POST['action'] == 'del_producto_detalle_presupuesto') {
        if (empty($_POST['id_detalle'])) {
            echo 'error';
        } else {
            $id_detalle = $_POST['id_detalle'];
            $token      = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL del_detalle_temp_presupuesto($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);


            $detalleTabla = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioVenta'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTabla .= '
                            <tr>
                                <td>' . $data['id'] . '</td>
                                <td colspan="2">' . $data['nombre'] . '</td>
                                <td class="textcenter">' . $data['cant'] . '</td>
                                <td class="textright">' . number_format($data['precioVenta'], 0, ",", ".") . '</td>
                                <td class="textright">' . number_format($precioTotal, 0, ",", ".") . '</td>
                                <td class="">
                                    <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_presupuesto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                                </td>
                            </tr>
                        ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotales = '
                        <tr>
                            <td colspan="5" class="textright">SUBTOTAL Q.</td>
                            <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                            <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="textright">TOTAL Q.</td>
                            <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                        </tr>
                    ';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
    }
    /*FIN ELIMINAR LINEA DETALLE TEMP PRESUPUESTO*/

    //eliminar datos del detalles GASTO temp
    if ($_POST['action'] == 'del_producto_detalle_gasto') {
        if (empty($_POST['id_detalle'])) {
            echo 'error';
        } else {
            $id_detalle = $_POST['id_detalle'];
            $token      = md5($_SESSION['idUser']);

            $query_detalle_temp = mysqli_query($conection, "CALL del_producto_detalle_gasto($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);


            $detalleTabla = '';
            //$sub_total    = 0;
            //$iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $total = $total + $data['monto'];

                    $detalleTabla .= '
                    <tr>
                    <td>' . $data['descripcion'] . '</td>
                    <td>' . number_format($data['monto'], 0, ",", ".") . '</td>
                    <td class="">
                        <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle_gasto(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                    </td>
                </tr>
                    ';
                }

                $detalleTotales = '
                <tr>
                    <td class="textright">TOTAL Gs.</td>
                    <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                </tr>
                ';
                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
    }

    //*******INICIO*******eliminar datos del detalles COMPRA temp
    if ($_POST['action'] == 'del_producto_detalleCompra') {
        if (empty($_POST['id_detalle'])) {
            echo 'error';
        } else {
            $id_detalle = $_POST['id_detalle'];
            $token      = md5($_SESSION['idUser']);

            $query_iva = mysqli_query($conection, "SELECT iva FROM configuracion");
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conection, "CALL del_detalle_tempCompra($id_detalle,'$token')");
            $result = mysqli_num_rows($query_detalle_temp);


            $detalleTablaCompra = '';
            $sub_total    = 0;
            $iva          = 0;
            $total        = 0;
            $arrayData    = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['iva'];
                }
                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    // $precioTotal = round($data['cantidad'] * $data['precioVenta'], 2);
                    // $sub_total = round($sub_total + $precioTotal, 2);
                    // $total = round($total + $precioTotal, 2);

                    $precioTotal = $data['cant'] * $data['precioCompra'];
                    $sub_total = $sub_total + $precioTotal;
                    $total = $total + $precioTotal;

                    $detalleTablaCompra .= '
                        <tr>
                            <td>' . $data['id'] . '</td>
                            <td colspan="2">' . $data['nombre'] . '</td>
                            <td class="textcenter">' . $data['cant'] . '</td>
                            <td class="textright">' . number_format($data['precioCompra'], 0, ",", ".") . '</td>
                            <td class="textright">' . number_format($precioTotal, 0, ",", ".") . '</td>
                            <td class="">
                                <a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalleCompra(' . $data['id'] . ');"><i class="far fa-trash-alt"></i> </a>
                            </td>
                        </tr>
                    ';
                }

                $impuesto = round(($sub_total * ($iva / 100)));
                $tl_sniva = round($sub_total - $impuesto);
                $total    = round($impuesto + $tl_sniva);

                $detalleTotalesCompra = '
                    <tr>
                        <td colspan="5" class="textright">SUBTOTAL Q.</td>
                        <td class="textright">' . number_format($tl_sniva, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">IVA (' . $iva . '%)</td>
                        <td class="textright">' . number_format($impuesto, 0, ",", ".") . '</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="textright">TOTAL Q.</td>
                        <td class="textright">' . number_format($total, 0, ",", ".") . '</td>
                    </tr>
                ';
                $arrayData['detalle'] = $detalleTablaCompra;
                $arrayData['totales'] = $detalleTotalesCompra;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            //mysqli_close($conection);
        }
    }
    //*******FIN*******eliminar datos del detalles COMPRA temp


    //anular venta
    if ($_POST['action'] == 'anularVenta') {
        $token = md5($_SESSION['idUser']);
        $query_delete = mysqli_query($conection, "DELETE FROM detallesVentas_temp WHERE token_user = '$token' ");
        mysqli_close($conection);
        if ($query_delete) {
            echo 'ok';
        } else {
            echo 'error';
        }
        exit;
    }

    //anular presupuesto
    if ($_POST['action'] == 'anularPresupuesto') {
        $token = md5($_SESSION['idUser']);
        $query_delete = mysqli_query($conection, "DELETE FROM detallesPresupuesto_temp WHERE token_user = '$token' ");
        mysqli_close($conection);
        if ($query_delete) {
            echo 'ok';
        } else {
            echo 'error';
        }
        exit;
    }
    /*FIN ANULAR PRESUPUESTO*/

    //anular GASTO
    if ($_POST['action'] == 'anularGasto') {
        $token = md5($_SESSION['idUser']);
        $query_delete = mysqli_query($conection, "DELETE FROM detallesGastos_temp WHERE token_user = '$token' ");
        mysqli_close($conection);
        if ($query_delete) {
            echo 'ok';
        } else {
            echo 'error';
        }
        exit;
    }

    //*******INICIO******* anular COMPRA
    if ($_POST['action'] == 'anularCompra') {
        $token = md5($_SESSION['idUser']);
        $query_delete = mysqli_query($conection, "DELETE FROM detallesCompras_temp WHERE token_user = '$token' ");
        mysqli_close($conection);
        if ($query_delete) {
            echo 'ok';
        } else {
            echo 'error';
        }
        exit;
    }
    //*******FIN******* anular COMPRA

    //procesar venta
    if ($_POST['action'] == 'procesarVenta') {
        if (empty($_POST['idCliente'])) {
            echo 'error';
        } else {
            $idCliente = $_POST['idCliente'];
            $tipo = $_POST['tipo'];
            $cantCuotas = $_POST['cantCuotas'];
            $noFacturaLegal = $_POST['noFacturaLegal'];
            $entrega = $_POST['entrega'];
            $montoEntrega = $_POST['montoEntrega'];
            $idObra = $_POST['idObra'];
        }

        if ($montoEntrega == '') {
            $montoEntrega = 0;
        }

        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];


        $query = mysqli_query($conection, "SELECT * FROM detallesVentas_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);


        if ($result > 0) {
            $query_procesar = mysqli_query($conection, "CALL procesar_venta($usuario,$idCliente,'$token',$tipo,$cantCuotas,$noFacturaLegal,$entrega,$montoEntrega,$idObra)");
            $result_detalle = mysqli_num_rows($query_procesar);


            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }

        mysqli_close($conection);
        exit;
    }
    //####### procesar venta FIN #######

    //procesar presupuesto
    if ($_POST['action'] == 'procesarPresupuesto') {
        if (empty($_POST['idCliente'])) {
            echo 'error';
        } else {
            $idCliente = $_POST['idCliente'];
            $tipo = $_POST['tipo'];
        }

        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];


        $query = mysqli_query($conection, "SELECT * FROM detallesPresupuesto_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);


        if ($result > 0) {
            $query_procesar = mysqli_query($conection, "CALL procesar_presupuesto($usuario,$idCliente,'$token',$tipo)");
            $result_detalle = mysqli_num_rows($query_procesar);


            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }

        mysqli_close($conection);
        exit;
    }
    //####### procesar presupuesto FIN #######

    //procesar Gasto
    if ($_POST['action'] == 'procesarGasto') {
        $tipoDocumento = $_POST['tipoDocumento'];
        $noDocumento = $_POST['noDocumento'];
        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];
        $fecha = $_POST['fecha'];

        $query = mysqli_query($conection, "SELECT * FROM detallesGastos_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $query_procesar = mysqli_query($conection, "CALL procesar_gasto($usuario,$noDocumento,'$token','$tipoDocumento','$fecha')");
            $result_detalle = mysqli_num_rows($query_procesar);

            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }

        mysqli_close($conection);
        exit;
    }

    //procesar venta Semanal
    if ($_POST['action'] == 'procesarVentaSemanal') {
        if (empty($_POST['idCliente'])) {
            //$idCliente = 1;
            echo 'error';
        } else {
            $idCliente = $_POST['idCliente'];
            $tipo = $_POST['tipo'];
            $cantCuotas = $_POST['cantCuotas'];
            $montoEntrega = $_POST['montoEntrega'];
            $noFacturaLegal = $_POST['noFacturaLegal'];
        }

        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];


        $query = mysqli_query($conection, "SELECT * FROM detallesVentas_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);


        if ($result > 0) {
            $query_procesar = mysqli_query($conection, "CALL procesar_venta_semanal($usuario,$idCliente,'$token',$tipo,$cantCuotas,$montoEntrega,$noFacturaLegal)");
            $result_detalle = mysqli_num_rows($query_procesar);


            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }

        mysqli_close($conection);
        exit;
    }

    //*******INICIO******* procesar COMPRA
    if ($_POST['action'] == 'procesarCompra') {
        if (empty($_POST['idProveedor'])) {
            //$idCliente = 1;
        } else {
            $idProveedor = $_POST['idProveedor'];
        }

        $token = md5($_SESSION['idUser']);
        $usuario = $_SESSION['idUser'];

        $query = mysqli_query($conection, "SELECT * FROM detallesCompras_temp WHERE token_user = '$token'");
        $result = mysqli_num_rows($query);


        if ($result > 0) {
            $query_procesar = mysqli_query($conection, "CALL procesar_Compra($usuario,$idProveedor,'$token')");
            $result_detalle = mysqli_num_rows($query_procesar);

            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
        mysqli_close($conection);
        exit;
    }

    //info factura compra
    if ($_POST['action'] == 'infoFactura') {
        if (!empty($_POST['nofactura'])) {
            $nofactura = $_POST['nofactura'];
            $query = mysqli_query($conection, "SELECT * FROM compras WHERE idCompra = $nofactura AND status = 1");
            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo "error";
        exit;
    }

    //info factura venta
    if ($_POST['action'] == 'infoFacturaVenta') {
        if (!empty($_POST['nofactura'])) {
            $nofactura = $_POST['nofactura'];
            $query = mysqli_query($conection, "SELECT * FROM ventas WHERE idVenta = $nofactura AND status = 1");
            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo "error";
        exit;
    }


    //anular compra
    if ($_POST['action'] == 'anularFactura') {
        if (!empty($_POST['noFactura'])) {
            $noFactura = $_POST['noFactura'];
            $query_anular = mysqli_query($conection, "CALL anular_compra($noFactura)");
            mysqli_close($conection);
            $result = mysqli_num_rows($query_anular);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo 'error';
        exit;
    }

    //anular venta
    if ($_POST['action'] == 'anularFacturaVenta') {
        if (!empty($_POST['noFactura'])) {
            $noFactura = $_POST['noFactura'];
            $query_anular = mysqli_query($conection, "CALL anular_venta($noFactura)");
            mysqli_close($conection);
            $result = mysqli_num_rows($query_anular);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        echo 'error';
        exit;
    }

    //PARA MOSTRAR LA LISTA DE PRODUCTOS COMPRADOS
    if ($_POST['action'] == 'lista_productos_comprados') {
        if (!empty($_POST['id_venta'])) {
            $no_venta = $_POST['id_venta'];
            $query_lista_productos = mysqli_query($conection, "SELECT p.referencia, p.nombre FROM ventas v INNER JOIN detallesVenta dv ON (v.idVenta = dv.noVenta) INNER JOIN productos p ON (dv.idProducto = p.idProducto) WHERE v.idVenta = $no_venta; ");
            mysqli_close($conection);
            $result = mysqli_num_rows($query_lista_productos);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query_lista_productos)) {
                    $detalleTabla .= '
                    <tbody>
                    <tr>
                        <td>' . $data['referencia'] . '</td>
                        <td>' . $data['nombre'] . '</td>
                    </tr>
                    </tbody>
                                    ';
                }
                $arrayData['detalle'] = $detalleTabla;
                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        }
        // echo 'error';
        exit;
    }
}
exit;
