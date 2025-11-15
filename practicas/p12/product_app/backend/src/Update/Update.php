<?php
namespace App\Update;

use App\DataBase;

class Update extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct('root', 'root', $db);
    }

    public function edit($object) {
        $this->data = array(
            'status' => 'error',
            'message' => 'Error al actualizar el producto'
        );

        if (isset($object->id)) {
            // Verificar que el producto existe
            $sql = "SELECT * FROM productos WHERE id = {$object->id} AND eliminado = 0";
            $result = $this->conexion->query($sql);

            if ($result->num_rows > 0) {
                // Actualizar el producto
                $sql = "UPDATE productos SET
                        nombre = '{$object->nombre}',
                        marca = '{$object->marca}',
                        modelo = '{$object->modelo}',
                        precio = {$object->precio},
                        detalles = '{$object->detalles}',
                        unidades = {$object->unidades},
                        imagen = '{$object->imagen}'
                        WHERE id = {$object->id}";

                if ($this->conexion->query($sql)) {
                    $this->data['status'] = 'success';
                    $this->data['message'] = 'Producto actualizado exitosamente';
                }
            } else {
                $this->data['message'] = 'El producto no existe';
            }
        }
    }
}