<?php
	// Habilitar reporte de errores para debugging
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	if(isset($_GET['tope']))
	{
		$tope = $_GET['tope'];
	}
	else
	{
		die('Parámetro "tope" no detectado...');
	}

	if (!empty($tope))
	{
		/** SE CREA EL OBJETO DE CONEXION */
		@$link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');	

		/** comprobar la conexión */
		if ($link->connect_errno) 
		{
			die('Falló la conexión: '.$link->connect_error.'<br/>');
		}

		/** ESTABLECER CHARSET UTF-8 */
		$link->set_charset("utf8");

		/** Consultar productos con unidades menores o iguales al tope */
		if ( $result = $link->query("SELECT * FROM productos WHERE unidades <= {$tope}") ) 
		{
			$rows = $result->fetch_all(MYSQLI_ASSOC);
			/** útil para liberar memoria asociada a un resultado con demasiada información */
			$result->free();
		}

		$link->close();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Productos</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	</head>
	<body>
		<h3>PRODUCTOS CON UNIDADES MENORES O IGUALES A <?= $tope ?></h3>

		<br/>
		
		<?php if( isset($rows) && count($rows) > 0 ) : ?>

			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Nombre</th>
						<th scope="col">Marca</th>
						<th scope="col">Modelo</th>
						<th scope="col">Precio</th>
						<th scope="col">Unidades</th>
						<th scope="col">Detalles</th>
						<th scope="col">Imagen</th>
						<th scope="col">Modificar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $row) : ?>
					<tr>
						<th scope="row"><?= $row['id'] ?></th>
						<td><?= htmlspecialchars($row['nombre']) ?></td>
						<td><?= htmlspecialchars($row['marca']) ?></td>
						<td><?= htmlspecialchars($row['modelo']) ?></td>
						<td><?= $row['precio'] ?></td>
						<td><?= $row['unidades'] ?></td>
						<td><?= htmlspecialchars(substr($row['detalles'], 0, 50)) ?>...</td>
						<td><img src="img/<?= $row['imagen'] ?>" alt="Producto" style="max-width: 100px;"></td>
						<td>
							<button class="btn btn-primary btn-sm" onclick="modificarProducto(<?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['nombre'])) ?>', '<?= addslashes(htmlspecialchars($row['marca'])) ?>', '<?= addslashes(htmlspecialchars($row['modelo'])) ?>', <?= $row['precio'] ?>, <?= $row['unidades'] ?>, '<?= addslashes(htmlspecialchars($row['detalles'])) ?>', '<?= $row['imagen'] ?>')">
								Modificar
							</button>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		<?php else : ?>

			<div class="alert alert-warning" role="alert">
				No se encontraron productos con unidades menores o iguales a <?= $tope ?>
			</div>

		<?php endif; ?>

		<script>
			function modificarProducto(id, nombre, marca, modelo, precio, unidades, detalles, imagen) {
				// Crear un formulario para enviar los datos por POST
				var form = document.createElement('form');
				form.method = 'POST';
				form.action = 'formulario_productos_v2.php';

				var campos = {
					'id': id,
					'nombre': nombre,
					'marca': marca,
					'modelo': modelo,
					'precio': precio,
					'unidades': unidades,
					'detalles': detalles,
					'imagen': imagen
				};

				for (var key in campos) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = key;
					input.value = campos[key];
					form.appendChild(input);
				}

				document.body.appendChild(form);
				form.submit();
			}
		</script>
	</body>
</html>