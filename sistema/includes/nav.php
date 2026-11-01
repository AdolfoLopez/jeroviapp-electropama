<nav>
	<ul>
		<?php
		if ($_SESSION['rol'] == 1) {
		?>
			<li class="principal">
				<a href="#"> Usuarios</a>
				<ul>
					<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
					<li><a href="lista_usuario.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
				</ul>
			</li>

			<li class="principal">
				<a href="#">Informes</a>
				<ul>
					<li><a href="informes/generaListadoMasVendidos.php" target="_blank">Listado mas vendidos - TOP 50</a></li>
					<li><a href="busqueda_compras_sku.php">Busqueda Compras por Referencia</a></li>
					<li><a href="informes/generalistadoProductosCantMin.php" target="_blank">Listado Productos con existencia Minima</a></li>
				</ul>
			</li>
		<?php
		}
		?>

		<li class="principal">
			<a href="#">Clientes</a>
			<ul>
				<li><a href="registro_cliente.php">Nuevo Cliente</a></li>
				<li><a href="lista_cliente.php">Lista de Clientes</a></li>
			</ul>
		</li>

		<li class="principal">
			<a href="#">Obras</a>
			<ul>
				<li><a href="registro_obra.php">Nueva Obra</a></li>
				<li><a href="lista_obra.php">Lista de Obras</a></li>
				<li><a href="informeVentasObra.php">Informe de Obras/Ventas</a></li>
			</ul>
		</li>

		<?php
		if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
		?>
			<li class="principal">
				<a href="#">Proveedores</a>
				<ul>
					<li><a href="registro_proveedor.php"><i class="fas fa-user-plus"></i> Nuevo Proveedor</a></li>
					<li><a href="lista_proveedor.php"><i class="far fa-list-alt"></i> Lista de Proveedores</a></li>
				</ul>
			</li>
		<?php
		}
		?>

		<?php
		if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
		?>
			<li class="principal">
				<a href="#">Marcas</a>
				<ul>
					<li><a href="registro_marca.php"><i class="fas fa-user-plus"></i> Nueva Marca</a></li>
					<li><a href="lista_marca.php"><i class="far fa-list-alt"></i> Lista de Marcas</a></li>
				</ul>
			</li>
		<?php
		}
		?>

		<?php
		if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
		?>
			<li class="principal">
				<a href="#">Categorías</a>
				<ul>
					<li><a href="registro_categoria.php"><i class="fas fa-user-plus"></i> Nueva Categoría</a></li>
					<li><a href="lista_categoria.php"><i class="far fa-list-alt"></i> Lista de Categoría</a></li>
				</ul>
			</li>
		<?php
		}
		?>

		<li class="principal">
			<a href="#">Productos</a>
			<ul>
				<?php
				if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
				?>
					<li><a href="registro_producto.php">Nuevo Producto</a></li>
				<?php
				}
				?>
				<li><a href="lista_producto.php">Lista de Productos</a></li>
				<li><a href="registro_inventario.php">Inventarios</a></li>
				<li><a href="lista_inventario.php">Lista de Inventarios</a></li>
			</ul>
		</li>

		<?php
		if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
		?>
			<li class="principal">
				<a href="#">Compras</a>
				<ul>
					<li><a href="nueva_compra.php">Nueva Compra</a></li>
					<li><a href="compras.php">Compras</a></li>
				</ul>
			</li>
		<?php
		}
		?>

		<li class="principal">
			<a href="#">Ventas</a>
			<ul>
				<li><a href="nueva_venta.php">Nueva Ventas</a></li>
				<li><a href="ventas.php">Ventas</a></li>
				<li><a href="ventas_por_mes.php">Ventas por mes</a></li>
			</ul>
		</li>

		<li class="principal">
			<a href="#">Caja</a>
			<ul>
				<?php
				if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
				?>
					<!-- <li><a href="aperturaCaja.php">Apertura Caja</a></li> -->
					<li><a href="listaAperturaCierreCaja.php">Apertura/Cierre Caja</a></li>
					<li><a href="cobranzas.php">Cobranzas</a></li>
					<li><a href="registro_gasto.php">Gastos</a></li>
					<li><a href="resumen_caja.php">Resumen Caja</a></li>
					<li><a href="informeGanancia.php">Informe de Ganancias</a></li>
				<?php
				}
				?>
				<li><a href="presupuesto.php">Presupuesto</a></li>
				<li><a href="lista_presupuesto.php">Lista de Presupuesto</a></li>
			</ul>
		</li>
	</ul>
</nav>