<?php
    header("Content-Type: application/json; charset=utf-8"); 
    $data = array();

    /** SE CREA EL OBJETO DE CONEXION */
    @$link = new mysqli('localhost', 'root', 'santi2016', 'marketzone');
    /** NOTA: con @ se suprime el Warning para gestionar el error por medio de código */

    /** comprobar la conexión */
    if ($link->connect_errno) 
    {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }

    /** Establecer charset UTF-8 */
    $link->set_charset("utf8");

    /** Verificar si existe el parámetro tope */
    if(isset($_GET['tope']) && !empty($_GET['tope']))
    {
        $tope = $_GET['tope'];
        /** Consulta con filtro de unidades */
        $sql = "SELECT * FROM productos WHERE unidades <= {$tope}";
    }
    else
    {
        /** Consulta sin filtro - muestra todos los productos */
        $sql = "SELECT * FROM productos";
    }

    /** Ejecutar la consulta */
    if ( $result = $link->query($sql) ) 
    {
        /** Se extraen las tuplas obtenidas de la consulta */
        $row = $result->fetch_all(MYSQLI_ASSOC);

        /** Se crea un arreglo con la estructura deseada */
        foreach($row as $num => $registro) {            // Se recorren tuplas
            foreach($registro as $key => $value) {      // Se recorren campos
                $data[$num][$key] = $value;  // Ya no necesitas utf8_encode si usas set_charset
            }
        }

        /** útil para liberar memoria asociada a un resultado con demasiada información */
        $result->free();
    }

    $link->close();

    /** Se devuelven los datos en formato JSON */
    echo json_encode($data, JSON_PRETTY_PRINT);
?>