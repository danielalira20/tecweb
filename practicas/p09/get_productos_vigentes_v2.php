<?php
    header("Content-Type: application/json; charset=utf-8"); 
    $data = array();

    // SE CREA EL OBJETO DE CONEXION
    @$link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');
    
    // Comprobar la conexión
    if ($link->connect_errno) 
    {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }

    // ESTABLECER CHARSET UTF-8
    $link->set_charset("utf8");

    // Consultar todos los productos vigentes (no eliminados)
    $sql = "SELECT * FROM productos WHERE eliminado = 0";

    // Si se proporciona un tope, filtrar por unidades
    if(isset($_GET['tope']) && !empty($_GET['tope']))
    {
        $tope = $_GET['tope'];
        $sql = "SELECT * FROM productos WHERE eliminado = 0 AND unidades <= {$tope}";
    }

    // Ejecutar la consulta
    if ( $result = $link->query($sql) ) 
    {
        // Obtener todos los resultados
        $row = $result->fetch_all(MYSQLI_ASSOC);

        // Procesar cada producto
        foreach($row as $num => $registro) {            
            foreach($registro as $key => $value) {      
                $data[$num][$key] = $value;
            }
            // Agregar enlace para modificar el producto
            $data[$num]['modificar'] = "formulario_productos_v2.php?id=" . $registro['id'];
        }

        // Liberar memoria
        $result->free();
    }

    // Cerrar conexión
    $link->close();

    // Retornar datos en formato JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>