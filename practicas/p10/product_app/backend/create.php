<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $response = array(
        'success' => false,
        'message' => ''
    );

    // SE VERIFICA QUE HAYA LLEGADO EL JSON
    $json = file_get_contents('php://input');
    
    if(!empty($json)) {
        // SE DECODIFICA EL JSON A UN OBJETO PHP
        $data = json_decode($json, true);
        
        // SE VALIDA QUE EL JSON SEA VÁLIDO
        if(json_last_error() === JSON_ERROR_NONE) {
            
            // SE VALIDAN LOS CAMPOS OBLIGATORIOS
            if(isset($data['nombre']) && isset($data['marca']) && isset($data['modelo']) && 
               isset($data['precio']) && isset($data['unidades'])) {
                
                // SE SANITIZAN LOS DATOS
                $nombre = $conexion->real_escape_string(trim($data['nombre']));
                $marca = $conexion->real_escape_string(trim($data['marca']));
                $modelo = $conexion->real_escape_string(trim($data['modelo']));
                $precio = floatval($data['precio']);
                $unidades = intval($data['unidades']);
                $detalles = isset($data['detalles']) ? $conexion->real_escape_string(trim($data['detalles'])) : '';
                $imagen = isset($data['imagen']) ? $conexion->real_escape_string(trim($data['imagen'])) : 'img/default.png';
                
                // VALIDAR QUE NO EXISTA UN PRODUCTO IDÉNTICO NO ELIMINADO
                $sql_verificar = "SELECT id FROM productos 
                                 WHERE ((nombre = '$nombre' AND marca = '$marca') 
                                        OR (marca = '$marca' AND modelo = '$modelo')) 
                                   AND eliminado = 0";
                
                if($result_verificar = $conexion->query($sql_verificar)) {
                    if($result_verificar->num_rows > 0) {
                        $response['message'] = 'Ya existe un producto idéntico en la base de datos (mismo nombre y marca, o misma marca y modelo)';
                    } else {
                        // INSERTAR EL NUEVO PRODUCTO
                        $sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado) 
                                      VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen', 0)";
                        
                        if($conexion->query($sql_insert)) {
                            $response['success'] = true;
                            $response['message'] = 'Producto agregado correctamente con ID: ' . $conexion->insert_id;
                        } else {
                            $response['message'] = 'Error al insertar producto: ' . $conexion->error;
                        }
                    }
                    $result_verificar->free();
                } else {
                    $response['message'] = 'Error al verificar producto existente: ' . $conexion->error;
                }
                
            } else {
                $response['message'] = 'Faltan campos obligatorios en el JSON';
            }
        } else {
            $response['message'] = 'JSON inválido: ' . json_last_error_msg();
        }
    } else {
        $response['message'] = 'No se recibió ningún dato JSON';
    }

    // CERRAR CONEXIÓN
    $conexion->close();

    // DEVOLVER RESPUESTA
    echo json_encode($response, JSON_PRETTY_PRINT);
?>