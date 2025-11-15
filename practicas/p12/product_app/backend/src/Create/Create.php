<?php
namespace App\Create;

use App\DataBase;

class Create extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct('root', 'root', $db);
    }

    public function add($object) {
        $this->data = array(
            'status' => 'error',
            'message' => 'Error al agregar el producto'
        );

        if (isset($object->nombre)) {
            // Validar que el producto no exista
            $sql = "SELECT * FROM productos WHERE nombre = '{$object->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);

            if ($result->num_rows == 0) {
                // Insertar el nuevo producto
                $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                        VALUES (
                            '{$object->nombre}', 
                            '{$object->marca}', 
                            '{$object->modelo}', 
                            {$object->precio}, 
                            '{$object->detalles}', 
                            {$object->unidades}, 
                            '{$object->imagen}'
                        )";

                if ($this->conexion->query($sql)) {
                    $this->data['status'] = 'success';
                    $this->data['message'] = 'Producto agregado exitosamente';
                    $this->data['id'] = $this->conexion->insert_id;
                }
            } else {
                $this->data['message'] = 'El producto ya existe';
            }
        }
    }
}