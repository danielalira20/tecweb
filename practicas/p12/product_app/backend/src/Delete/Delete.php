<?php
namespace App\Delete;

use App\DataBase;

class Delete extends DataBase {
    
    public function __construct($db = 'marketzone') {
        parent::__construct($db, 'root', 'daniela20');
    }

    public function delete($id) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        
        if(isset($id)) {
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
            
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado";
            } else {
                $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
            }
        } 
    }
}