<?php

// Configuración de la conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$password = 'daniela20';  
$base_datos = 'marketzone';  


$conexion = mysqli_connect($host, $usuario, $password, $base_datos);


if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}


mysqli_set_charset($conexion, "utf8");


$exito = false;
$mensaje_error = "";
$datos_insertados = array();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $marca = mysqli_real_escape_string($conexion, trim($_POST['marca']));
    $modelo = mysqli_real_escape_string($conexion, trim($_POST['modelo']));
    $precio = floatval($_POST['precio']);
    $detalles = mysqli_real_escape_string($conexion, trim($_POST['detalles']));
    $unidades = intval($_POST['unidades']);
    
    
    $nombre_imagen = "";
    $error_imagen = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_unico = time() . '_' . uniqid() . '.' . $extension;
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        $ruta_destino = "img/" . $nombre_unico;
        
        if (!file_exists('img')) {
            if (!mkdir('img', 0777, true)) {
                $error_imagen = "No se pudo crear la carpeta img/";
            }
        }
        
        if (empty($error_imagen) && move_uploaded_file($ruta_temporal, $ruta_destino)) {
            $nombre_imagen = $nombre_unico;
        } else {
            $error_imagen = "Error al subir la imagen";
        }
    } else {
        $error_imagen = "No se recibió ninguna imagen";
    }
    

    if (empty($nombre) || empty($marca) || empty($modelo)) {
        $mensaje_error = "Error: Todos los campos son obligatorios.";
    } else {
        
        
        $query_verificar = "SELECT id FROM productos 
                           WHERE nombre = '$nombre' 
                           AND marca = '$marca' 
                           AND modelo = '$modelo'";
        
        $resultado_verificar = mysqli_query($conexion, $query_verificar);
        
        if (mysqli_num_rows($resultado_verificar) > 0) {
     
            $mensaje_error = "Error: Ya existe un producto con el mismo nombre, marca y modelo en la base de datos.";
        } else {
 
            // $query_insertar = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                             // VALUES ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$nombre_imagen', 0)";

            $query_insertar = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                  VALUES ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$nombre_imagen')";
            
            if (mysqli_query($conexion, $query_insertar)) {
                $exito = true;
                $datos_insertados = array(
                    'id' => mysqli_insert_id($conexion),
                    'nombre' => $nombre,
                    'marca' => $marca,
                    'modelo' => $modelo,
                    'precio' => $precio,
                    'detalles' => $detalles,
                    'unidades' => $unidades,
                    'imagen' => $nombre_imagen
                );
            } else {
                $mensaje_error = "Error al insertar el producto: " . mysqli_error($conexion);
            }
        }
    }
} else {
    $mensaje_error = "Error: No se recibieron datos del formulario.";
}


mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Registro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        .success {
            background-color: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .error {
            background-color: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        h2 {
            color: #555;
            margin: 20px 0 10px 0;
            font-size: 20px;
        }
        
        .dato {
            background: #f8f9fa;
            padding: 10px 15px;
            margin: 8px 0;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        
        .dato strong {
            color: #667eea;
            display: inline-block;
            width: 120px;
        }
        
        .imagen-preview {
            margin-top: 20px;
            text-align: center;
        }
        
        .imagen-preview img {
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($exito): ?>
            <div class="success">
                <div class="icon">✅</div>
                <h1>¡Producto Registrado Exitosamente!</h1>
            </div>
            
            <h2> Resumen de Datos Insertados:</h2>
            
            <div class="dato">
                <strong>ID:</strong> <?php echo $datos_insertados['id']; ?>
            </div>
            
            <div class="dato">
                <strong>Nombre:</strong> <?php echo htmlspecialchars($datos_insertados['nombre']); ?>
            </div>
            
            <div class="dato">
                <strong>Marca:</strong> <?php echo htmlspecialchars($datos_insertados['marca']); ?>
            </div>
            
            <div class="dato">
                <strong>Modelo:</strong> <?php echo htmlspecialchars($datos_insertados['modelo']); ?>
            </div>
            
            <div class="dato">
                <strong>Precio:</strong> $<?php echo number_format($datos_insertados['precio'], 2); ?>
            </div>
            
            <div class="dato">
                <strong>Detalles:</strong> <?php echo htmlspecialchars($datos_insertados['detalles']); ?>
            </div>
            
            <div class="dato">
                <strong>Unidades:</strong> <?php echo $datos_insertados['unidades']; ?>
            </div>
            
            <?php if (!empty($datos_insertados['imagen'])): ?>
                <div class="imagen-preview">
                    <h2> Imagen del Producto:</h2>
                    <img src="img/<?php echo htmlspecialchars($datos_insertados['imagen']); ?>" 
                         alt="<?php echo htmlspecialchars($datos_insertados['nombre']); ?>">
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="error">
                <div class="icon">❌</div>
                <h1>Error en el Registro</h1>
                <p style="margin-top: 15px; font-size: 16px;">
                    <?php echo htmlspecialchars($mensaje_error); ?>
                </p>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center;">
            <a href="formulario_productos.html" class="btn">
                <?php echo $exito ? ' Agregar Otro Producto' : ' Intentar Nuevamente'; ?>
            </a>
        </div>
    </div>
</body>
</html>