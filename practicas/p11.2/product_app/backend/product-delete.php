<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(
        'status'  => 'error',
        'message' => 'No se recibió el ID del producto'
    );

    if(isset($_POST['id'])) {
        $id = intval($_POST['id']);
        
        // VALIDAR QUE EL ID SEA VÁLIDO
        if ($id <= 0) {
            $data['message'] = "ID de producto inválido";
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }

        // VERIFICAR QUE EL PRODUCTO EXISTA
        $sql = "SELECT id FROM productos WHERE id = ? AND eliminado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $data['message'] = "El producto no existe o ya fue eliminado";
            $stmt->close();
            $conexion->close();
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }
        $stmt->close();

        // REALIZAR EL BORRADO LÓGICO
        $sql = "UPDATE productos SET eliminado=1 WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            $data['status'] = "success";
            $data['message'] = "Producto eliminado exitosamente";
        } else {
            $data['message'] = "ERROR: No se ejecutó la consulta. " . $stmt->error;
        }
        
        $stmt->close();
        $conexion->close();
    }
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>