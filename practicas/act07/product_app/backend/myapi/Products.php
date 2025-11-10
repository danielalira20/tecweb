<?php
namespace TECWEB\MYAPI;

use TECWEB\MYAPI\DataBase as DataBase;
require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $data = NULL;
    
    public function __construct($db, $user='root', $pass='12345678a') {
        $this->data = array();
        parent::__construct($user, $pass, $db);
    }

    
    public function list(){
        $this->data = array();
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if(!is_null($rows)) {
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

    
    public function search($search){
        $this->data = array();
        $search = $this->conexion->real_escape_string($search);
        
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if(!is_null($rows)) {
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

  
    public function single($id){
        $this->data = array();
        $id = $this->conexion->real_escape_string($id);
        
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}")) {
            $row = $result->fetch_assoc();
            if(!is_null($row)) {
                foreach($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }


    public function singleByName($nombre){
        $this->data = array();
        $nombre = $this->conexion->real_escape_string($nombre);
        
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0")) {
            if($result->num_rows > 0) {
                $this->data = array('existe' => true);
            } else {
                $this->data = array('existe' => false);
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

    
   // Agregar producto
public function add($jsonOBJ){
    $this->data = array();
    
    // Si viene como string JSON, decodificar
    if(is_string($jsonOBJ)) {
        $jsonOBJ = json_decode($jsonOBJ, true);
    }
    // Si no, asumir que ya es un array (viene de $_POST)
    
    // Validar que el nombre no exista
    $nombre = $this->conexion->real_escape_string($jsonOBJ['nombre']);
    $result = $this->conexion->query("SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0");
    
    if($result->num_rows == 0) {
        $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                VALUES (
                    '{$this->conexion->real_escape_string($jsonOBJ['nombre'])}',
                    '{$this->conexion->real_escape_string($jsonOBJ['marca'])}',
                    '{$this->conexion->real_escape_string($jsonOBJ['modelo'])}',
                    {$jsonOBJ['precio']},
                    '{$this->conexion->real_escape_string($jsonOBJ['detalles'])}',
                    {$jsonOBJ['unidades']},
                    '{$this->conexion->real_escape_string($jsonOBJ['imagen'])}'
                )";
        
        if($this->conexion->query($sql)){
            $this->data['status'] = "success";
            $this->data['message'] = "Producto agregado correctamente";
        } else {
            $this->data['status'] = "error";
            $this->data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
        }
    } else {
        $this->data['status'] = "error";
        $this->data['message'] = "ERROR: El producto ya existe";
    }
    
    $result->free();
    $this->conexion->close();
}

  
    // Editar producto
public function edit($jsonOBJ){
    $this->data = array();
    
    // Si viene como string JSON, decodificar
    if(is_string($jsonOBJ)) {
        $jsonOBJ = json_decode($jsonOBJ, true);
    }
    
    $sql = "UPDATE productos SET 
            nombre = '{$this->conexion->real_escape_string($jsonOBJ['nombre'])}',
            marca = '{$this->conexion->real_escape_string($jsonOBJ['marca'])}',
            modelo = '{$this->conexion->real_escape_string($jsonOBJ['modelo'])}',
            precio = {$jsonOBJ['precio']},
            detalles = '{$this->conexion->real_escape_string($jsonOBJ['detalles'])}',
            unidades = {$jsonOBJ['unidades']},
            imagen = '{$this->conexion->real_escape_string($jsonOBJ['imagen'])}'
            WHERE id = {$jsonOBJ['id']}";
    
    if($this->conexion->query($sql)){
        $this->data['status'] = "success";
        $this->data['message'] = "Producto actualizado correctamente";
    } else {
        $this->data['status'] = "error";
        $this->data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
    }
    
    $this->conexion->close();
}

   
    public function delete($id){
        $this->data = array();
        $id = $this->conexion->real_escape_string($id);
        
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
        
        if($this->conexion->query($sql)){
            $this->data['status'] = "success";
            $this->data['message'] = "Producto eliminado correctamente";
        } else {
            $this->data['status'] = "error";
            $this->data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
        }
        
        $this->conexion->close();
    }

    
    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>