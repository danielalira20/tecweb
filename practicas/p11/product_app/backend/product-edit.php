<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(
        'status'  => 'error',
        'message' => 'La actualización falló'
    );

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JSON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        // SE VERIFICA QUE EXISTA EL ID
        if(isset($jsonOBJ->id)) {
            $conexion->set_charset("utf8");
            $sql = "UPDATE productos SET 
                    nombre = '{$jsonOBJ->nombre}',
                    marca = '{$jsonOBJ->marca}',
                    modelo = '{$jsonOBJ->modelo}',
                    precio = {$jsonOBJ->precio},
                    detalles = '{$jsonOBJ->detalles}',
                    unidades = {$jsonOBJ->unidades},
                    imagen = '{$jsonOBJ->imagen}'
                    WHERE id = {$jsonOBJ->id}";
            
            if($conexion->query($sql)){
                $data['status'] = "success";
                $data['message'] = "Producto actualizado";
            } else {
                $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
            }
        } else {
            $data['message'] = "ERROR: ID de producto no especificado";
        }
        
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>