<?php
namespace TECWEB\MYAPI;

use TECWEB\MYAPI\DataBase as DataBase;
require_once __DIR__ . '/DataBase.php';

class Products extends DataBase {
    private $response = NULL;
    
    public function __construct($db, $user='root', $pass='daniela20') {
        $this->response = array();
        parent::__construct($user, $pass, $db);
    }

    
    public function list(){
        $this->response = array();
        if ($consulta = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            $filas = $consulta->fetch_all(MYSQLI_ASSOC);
            if(!is_null($filas)) {
                foreach($filas as $indice => $fila) {
                    foreach($fila as $campo => $valor) {
                        $this->response[$indice][$campo] = $valor;
                    }
                }
            }
            $consulta->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

    
    public function search($search){
        $this->response = array();
        $search = $this->conexion->real_escape_string($search);
        
        $query = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
        
        if ($consulta = $this->conexion->query($query)) {
            $filas = $consulta->fetch_all(MYSQLI_ASSOC);
            if(!is_null($filas)) {
                foreach($filas as $indice => $fila) {
                    foreach($fila as $campo => $valor) {
                        $this->response[$indice][$campo] = $valor;
                    }
                }
            }
            $consulta->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

  
    public function single($id){
        $this->response = array();
        $id = $this->conexion->real_escape_string($id);
        
        if ($consulta = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}")) {
            $fila = $consulta->fetch_assoc();
            if(!is_null($fila)) {
                foreach($fila as $campo => $valor) {
                    $this->response[$campo] = $valor;
                }
            }
            $consulta->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }


    public function singleByName($nombre){
        $this->response = array();
        $nombre = $this->conexion->real_escape_string($nombre);
        
        if ($consulta = $this->conexion->query("SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0")) {
            if($consulta->num_rows > 0) {
                $this->response = array('existe' => true);
            } else {
                $this->response = array('existe' => false);
            }
            $consulta->free();
        } else {
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

    
   // Agregar producto
public function add($productData){
    $this->response = array();
    
    // Si viene como string JSON, decodificar
    if(is_string($productData)) {
        $productData = json_decode($productData, true);
    }
    // Si no, asumir que ya es un array (viene de $_POST)
    
    // Validar que el nombre no exista
    $nombreProducto = $this->conexion->real_escape_string($productData['nombre']);
    $verificacion = $this->conexion->query("SELECT * FROM productos WHERE nombre = '{$nombreProducto}' AND eliminado = 0");
    
    if($verificacion->num_rows == 0) {
        $queryInsert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                VALUES (
                    '{$this->conexion->real_escape_string($productData['nombre'])}',
                    '{$this->conexion->real_escape_string($productData['marca'])}',
                    '{$this->conexion->real_escape_string($productData['modelo'])}',
                    {$productData['precio']},
                    '{$this->conexion->real_escape_string($productData['detalles'])}',
                    {$productData['unidades']},
                    '{$this->conexion->real_escape_string($productData['imagen'])}'
                )";
        
        if($this->conexion->query($queryInsert)){
            $this->response['status'] = "success";
            $this->response['message'] = "Producto agregado correctamente";
        } else {
            $this->response['status'] = "error";
            $this->response['message'] = "ERROR: No se ejecutó la consulta. " . mysqli_error($this->conexion);
        }
    } else {
        $this->response['status'] = "error";
        $this->response['message'] = "ERROR: El producto ya existe";
    }
    
    $verificacion->free();
    $this->conexion->close();
}

  
    // Editar producto
public function edit($productData){
    $this->response = array();
    
    // Si viene como string JSON, decodificar
    if(is_string($productData)) {
        $productData = json_decode($productData, true);
    }
    
    $queryUpdate = "UPDATE productos SET 
            nombre = '{$this->conexion->real_escape_string($productData['nombre'])}',
            marca = '{$this->conexion->real_escape_string($productData['marca'])}',
            modelo = '{$this->conexion->real_escape_string($productData['modelo'])}',
            precio = {$productData['precio']},
            detalles = '{$this->conexion->real_escape_string($productData['detalles'])}',
            unidades = {$productData['unidades']},
            imagen = '{$this->conexion->real_escape_string($productData['imagen'])}'
            WHERE id = {$productData['id']}";
    
    if($this->conexion->query($queryUpdate)){
        $this->response['status'] = "success";
        $this->response['message'] = "Producto actualizado correctamente";
    } else {
        $this->response['status'] = "error";
        $this->response['message'] = "ERROR: No se pudo actualizar. " . mysqli_error($this->conexion);
    }
    
    $this->conexion->close();
}

   
    public function delete($id){
        $this->response = array();
        $id = $this->conexion->real_escape_string($id);
        
        $queryDelete = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
        
        if($this->conexion->query($queryDelete)){
            $this->response['status'] = "success";
            $this->response['message'] = "Producto eliminado correctamente";
        } else {
            $this->response['status'] = "error";
            $this->response['message'] = "ERROR: No se pudo eliminar. " . mysqli_error($this->conexion);
        }
        
    $this->conexion->close();
    }

    
    public function getData() {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
?>