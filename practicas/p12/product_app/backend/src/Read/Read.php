<?php
namespace App\Read;

use App\DataBase;

class Read extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct('root', 'root', $db);
    }

    // Listar todos los productos
    public function list() {
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $productos = array();
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
            $this->setData($productos);
        } else {
            $this->setData(array());
        }
    }

    // Buscar productos por término
    public function search($searchTerm) {
        $sql = "SELECT * FROM productos 
                WHERE (nombre LIKE '%{$searchTerm}%' 
                   OR marca LIKE '%{$searchTerm}%' 
                   OR detalles LIKE '%{$searchTerm}%')
                AND eliminado = 0";
        
        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $productos = array();
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
            $this->setData($productos);
        } else {
            $this->setData(array());
        }
    }

    // Obtener un producto específico
    public function single($id) {
        $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $this->setData($result->fetch_assoc());
        } else {
            $this->setData(array());
        }
    }
}