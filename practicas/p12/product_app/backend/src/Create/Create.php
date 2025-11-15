<?php
namespace App\Create;

use App\DataBase;

class Create extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct($db, 'root', 'daniela20');               
    }

    public function add($object) {
        $this->data = array(
            'status' => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );

        if (isset($object->nombre)) {
            // Validar que el producto no exista
            $sql = "SELECT * FROM productos WHERE nombre = '{$object->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);

            if ($result->num_rows == 0) {
                // Insertar el nuevo producto
                $sql = "INSERT INTO productos VALUES (
                    null,
                    '{$object->nombre}', 
                    '{$object->marca}', 
                    '{$object->modelo}', 
                    {$object->precio}, 
                    '{$object->detalles}', 
                    {$object->unidades}, 
                    '{$object->imagen}',
                    0
                )";

                if ($this->conexion->query($sql)) {
                    $this->data['status'] = 'success';
                    $this->data['message'] = 'Producto agregado';
                } else {
                    $this->data['message'] = 'ERROR: ' . mysqli_error($this->conexion);
                }
            }
            $result->free();
        }
    }
}