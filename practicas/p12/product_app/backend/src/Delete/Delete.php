<?php
namespace App\Delete;

use App\DataBase;

class Delete extends DataBase {

    public function __construct($db = 'marketzone') {
        parent::__construct($db, 'root', 'daniela20');
    }

    public function delete($id) {

        $this->data = [
            'status' => 'error',
            'message' => 'No se pudo eliminar el producto'
        ];

        if ($id > 0) {

            $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";

            if ($this->conexion->query($sql)) {
                $this->data['status'] = 'success';
                $this->data['message'] = 'Producto eliminado correctamente';
            } else {
                $this->data['message'] = 'ERROR SQL: ' . $this->conexion->error;
            }
        } else {
            $this->data['message'] = 'ID inválido';
        }

        return $this->data;   
    }
}
