<?php
namespace App;

abstract class DataBase {
    protected $conexion;
    protected $data;

    public function __construct($db, $user = 'root', $pass = 'daniela20') {
        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );

        if(!$this->conexion) {
            die('¡Base de datos NO conectada! Error: ' . mysqli_connect_error());
        }
        
        $this->conexion->set_charset("utf8");
        $this->data = array();
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    protected function setData($data) {
        $this->data = $data;
    }

    public function __destruct() {
        if($this->conexion) {
            mysqli_close($this->conexion);
        }
    }
}