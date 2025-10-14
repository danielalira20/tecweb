<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Verificar que se recibieron datos por POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die('Método no permitido. Use POST.');
    }

    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die('ERROR: ID de producto no proporcionado.');
    }

    // Obtener y validar datos del formulario
    $id = intval($_POST['id']);
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $marca = isset($_POST['marca']) ? trim($_POST['marca']) : '';
    $modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
    $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
    $detalles = isset($_POST['detalles']) ? trim($_POST['detalles']) : '';
    $unidades = isset($_POST['unidades']) ? intval($_POST['unidades']) : 0;
    $imagen_actual = isset($_POST['imagen_actual']) ? $_POST['imagen_actual'] : '';

    // Validaciones
    if (empty($nombre) || empty($marca) || empty($modelo)) {
        die('ERROR: Nombre, marca y modelo son requeridos.');
    }

    if ($precio <= 99.99) {
        die('ERROR: El precio debe ser mayor a 99.99');
    }

    if ($unidades < 0) {
        die('ERROR: Las unidades deben ser mayor o igual a 0.');
    }

    // Conectar a la base de datos
    $link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');

    
    if ($link->connect_errno) {
        die("ERROR: No pudo conectarse con la DB. " . $link->connect_error);
    }

    
    $link->set_charset("utf8");
    $imagen = $imagen_actual; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_ext = strtolower(pathinfo($imagen_nombre, PATHINFO_EXTENSION));
        
        
        $extensiones_permitidas = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($imagen_ext, $extensiones_permitidas)) {
            // Generar un nombre único para la imagen
            $imagen_nuevo_nombre = uniqid() . '.' . $imagen_ext;
            $imagen_destino = 'src/' . $imagen_nuevo_nombre;
            
            if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
                $imagen = $imagen_nuevo_nombre;
                
                if (!empty($imagen_actual) && $imagen_actual !== 'imagen.png' && file_exists('src/' . $imagen_actual)) {
                    unlink('src/' . $imagen_actual);
                }
            } else {
                die('ERROR: No se pudo subir la imagen.');
            }
        } else {
            die('ERROR: Formato de imagen no permitido. Use JPG, PNG o GIF.');
        }
    }

    if (empty($imagen)) {
        $imagen = 'imagen.png';
    }

    $sql = "UPDATE productos SET 
            nombre = '{$link->real_escape_string($nombre)}',
            marca = '{$link->real_escape_string($marca)}',
            modelo = '{$link->real_escape_string($modelo)}',
            precio = {$precio},
            detalles = '{$link->real_escape_string($detalles)}',
            unidades = {$unidades},
            imagen = '{$link->real_escape_string($imagen)}'
            WHERE id = {$id}";

    
    if (mysqli_query($link, $sql)) {
       
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Producto Actualizado</title>
            <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css'>
            <style>
                body {
                    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #feada6 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: Arial, sans-serif;
                }
                .mensaje-container {
                    background: white;
                    padding: 40px;
                    border-radius: 15px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    text-align: center;
                    max-width: 500px;
                }
                .icono-exito {
                    font-size: 64px;
                    color: #28a745;
                    margin-bottom: 20px;
                }
                h2 {
                    color: #d81b60;
                    margin-bottom: 20px;
                }
                .btn-regresar {
                    background: linear-gradient(135deg, #d81b60 0%, #c2185b 100%);
                    color: white;
                    padding: 12px 30px;
                    border: none;
                    border-radius: 8px;
                    text-decoration: none;
                    display: inline-block;
                    margin-top: 20px;
                    font-weight: 600;
                }
                .btn-regresar:hover {
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 20px rgba(216, 27, 96, 0.4);
                }
            </style>
        </head>
        <body>
            <div class='mensaje-container'>
                <div class='icono-exito'>✓</div>
                <h2>¡Producto Actualizado!</h2>
                <p>El producto <strong>{$nombre}</strong> ha sido actualizado correctamente.</p>
                <div>
                    <a href='get_productos_xhtml_v2.php?tope=1000' class='btn-regresar'>Ver Productos (HTML)</a>
                    <a href='get_productos_vigentes_v2.php' class='btn-regresar' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>Ver Productos (JSON)</a>
                </div>
            </div>
        </body>
        </html>";
    } else {
        echo "ERROR: No se ejecutó la actualización. " . mysqli_error($link);
    }

    // Cerrar la conexión
    mysqli_close($link);
?>