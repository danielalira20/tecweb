<?php
    header("Content-Type: application/json; charset=utf-8"); 
    $data = array();

    
    @$link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');
    

    
    if ($link->connect_errno) 
    {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }

    
    $link->set_charset("utf8");

    
    $sql = "SELECT * FROM productos WHERE eliminado = 0";

    
    if(isset($_GET['tope']) && !empty($_GET['tope']))
    {
        $tope = $_GET['tope'];
        $sql = "SELECT * FROM productos WHERE eliminado = 0 AND unidades <= {$tope}";
    }

 
    if ( $result = $link->query($sql) ) 
    {
       
        $row = $result->fetch_all(MYSQLI_ASSOC);

        
        foreach($row as $num => $registro) {            
            foreach($registro as $key => $value) {      
                $data[$num][$key] = $value;
            }
        }

        
        $result->free();
    }

    $link->close();

    echo json_encode($data, JSON_PRETTY_PRINT);
?>