<?php
namespace App\Delete;

use App\DataBase;

class Delete extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct('root', 'root', $db);
    }

    public function delete($id) {
        $this->data = array(
            'status' => 'error',
            'message' => 'Error al eliminar el producto'
        );

        if (!empty($id)) {
            // Eliminación lógica (soft delete)
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";

            if ($this->conexion->query($sql)) {
                $this->data['status'] = 'success';
                $this->data['message'] = 'Producto eliminado exitosamente';
            }
        }
    }
}