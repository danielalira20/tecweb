<?php
namespace App\Update;

use App\DataBase;

class Update extends DataBase {
    
   public function edit($object) {
    $this->data = array(
        'status'  => 'error',
        'message' => 'La consulta falló'
    );

    // ⚡ como json_decode(true) devuelve arrays, usamos corchetes:
    if (isset($object['id'])) {

        $sql = "UPDATE productos SET 
                nombre   = '{$object['nombre']}',
                marca    = '{$object['marca']}',
                modelo   = '{$object['modelo']}',
                precio   = {$object['precio']},
                detalles = '{$object['detalles']}',
                unidades = {$object['unidades']},
                imagen   = '{$object['imagen']}'
                WHERE id = {$object['id']}";

        $this->conexion->set_charset("utf8");

        if ($this->conexion->query($sql)) {
            $this->data['status'] = "success";
            $this->data['message'] = "Producto actualizado";
        } else {
            $this->data['message'] = "ERROR: " . mysqli_error($this->conexion);
        }
    }

    // ⚡⚡⚡ ESTO ES LO QUE FALTABA
    return $this->data;
}
}