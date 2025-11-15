<?php
namespace App;

abstract class DataBase {
    protected $conexion;
    protected $data;

    public function __construct($user, $pass, $db) {
        $this->conexion = new \mysqli('localhost', $user, $pass, $db);
        
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        
        $this->conexion->set_charset("utf8");
        $this->data = array();
    }

    public function getData() {
        return json_encode($this->data);
    }

    protected function setData($data) {
        $this->data = $data;
    }

    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}