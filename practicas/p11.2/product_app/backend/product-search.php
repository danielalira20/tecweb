<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();

    if(isset($_GET['search'])) {
        $search = trim($_GET['search']);
        
        // VALIDAR QUE NO ESTÉ VACÍO
        if (empty($search)) {
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        }

        // SANITIZAR LA BÚSQUEDA
        $searchParam = "%{$search}%";
        
        // SE REALIZA LA QUERY DE BÚSQUEDA CON PREPARED STATEMENT
        $sql = "SELECT * FROM productos WHERE (id = ? OR nombre LIKE ? OR marca LIKE ? OR detalles LIKE ?) AND eliminado = 0";
        $stmt = $conexion->prepare($sql);
        
        // Intentar convertir a número para búsqueda por ID
        $searchId = is_numeric($search) ? intval($search) : 0;
        
        $stmt->bind_param("isss", $searchId, $searchParam, $searchParam, $searchParam);
        
        if($stmt->execute()) {
            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if(!is_null($rows)) {
                // SE MAPEAN AL ARREGLO DE RESPUESTA
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $data[$num][$key] = $value;
                    }
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