<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();

    if(isset($_POST['id'])) {
        $id = intval($_POST['id']);
        
        // VALIDAR QUE EL ID SEA VÁLIDO
        if ($id <= 0) {
            $data['error'] = "ID de producto inválido";
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }

        // SE REALIZA LA QUERY DE BÚSQUEDA CON PREPARED STATEMENT
        $sql = "SELECT * FROM productos WHERE id = ? AND eliminado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if(!is_null($row)) {
                // SE MAPEAN AL ARREGLO DE RESPUESTA
                foreach($row as $key => $value) {
                    $data[$key] = $value;
                }
            }
            $result->free();
        } else {
            die('Query Error: '.$stmt->error);
        }
        
        $stmt->close();
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>