<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(
        'status'  => 'error',
        'message' => 'Datos incompletos o inválidos'
    );

    if(isset($_POST['nombre'])) {
        // SE OBTIENEN Y SANITIZAN LOS DATOS
        $nombre = trim($_POST['nombre']);
        $marca = isset($_POST['marca']) ? trim($_POST['marca']) : '';
        $modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
        $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
        $unidades = isset($_POST['unidades']) ? intval($_POST['unidades']) : 0;
        $detalles = isset($_POST['detalles']) ? trim($_POST['detalles']) : 'NA';
        $imagen = isset($_POST['imagen']) ? trim($_POST['imagen']) : 'img/default.png';

        // VALIDACIONES DEL LADO DEL SERVIDOR
        $errores = array();

        // Validar nombre
        if (empty($nombre)) {
            $errores[] = "El nombre del producto es obligatorio";
        } elseif (strlen($nombre) > 100) {
            $errores[] = "El nombre no puede exceder 100 caracteres";
        }

        // Validar marca
        if (empty($marca)) {
            $errores[] = "La marca es obligatoria";
        } elseif (strlen($marca) > 50) {
            $errores[] = "La marca no puede exceder 50 caracteres";
        }

        // Validar modelo
        if (empty($modelo)) {
            $errores[] = "El modelo es obligatorio";
        } elseif (strlen($modelo) > 25) {
            $errores[] = "El modelo no puede exceder 25 caracteres";
        } elseif (!preg_match('/^[a-zA-Z0-9\-]+$/', $modelo)) {
            $errores[] = "El modelo solo puede contener letras, números y guiones";
        }

        // Validar precio
        if ($precio <= 0) {
            $errores[] = "El precio debe ser mayor a 0";
        } elseif ($precio > 99999999.99) {
            $errores[] = "El precio excede el límite permitido";
        }

        // Validar unidades
        if ($unidades < 0) {
            $errores[] = "Las unidades no pueden ser negativas";
        }

        // Validar detalles
        if (strlen($detalles) > 250) {
            $errores[] = "Los detalles no pueden exceder 250 caracteres";
        }

        // Si hay errores, retornarlos
        if (!empty($errores)) {
            $data['message'] = implode('. ', $errores);
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }

        // VERIFICAR SI EL NOMBRE YA EXISTE
        $sql = "SELECT * FROM productos WHERE nombre = ? AND eliminado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data['message'] = "Ya existe un producto con ese nombre";
            $stmt->close();
            $conexion->close();
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }
        $stmt->close();

        // INSERTAR EL PRODUCTO (usando prepared statements)
        $conexion->set_charset("utf8");
        $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssdis", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);
        
        if($stmt->execute()){
            $data['status'] = "success";
            $data['message'] = "Producto agregado exitosamente";
        } else {
            $data['message'] = "ERROR: No se pudo agregar el producto. " . $stmt->error;
        }
        
        $stmt->close();
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>