<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();
    
    // SE VERIFICA HABER RECIBIDO EL TÉRMINO DE BÚSQUEDA
    if( isset($_POST['search']) ) {
        $search = $_POST['search'];
        
        // SE REALIZA LA QUERY DE BÚSQUEDA CON LIKE PARA BUSCAR EN NOMBRE, MARCA Y DETALLES
        $sql = "SELECT * FROM productos 
                WHERE nombre LIKE '%{$search}%' 
                   OR marca LIKE '%{$search}%' 
                   OR detalles LIKE '%{$search}%'";
        
        if ( $result = $conexion->query($sql) ) {
            // SE OBTIENEN TODOS LOS RESULTADOS
            while( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
                // SE CODIFICAN A UTF-8 LOS DATOS Y SE AGREGAN AL ARREGLO DE RESPUESTA
                $producto = array();
                foreach($row as $key => $value) {
                    $producto[$key] = $value; // utf8_encode($value);
                }
                $data[] = $producto;
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($conexion));
        }
        $conexion->close();
    } 
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>