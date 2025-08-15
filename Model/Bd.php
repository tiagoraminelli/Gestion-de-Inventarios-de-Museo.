<?php
namespace Model;

use PDO;

class Bd {
    private $conexion;

    public function __construct() {
        $this->conexion = new PDO('mysql:host=localhost;dbname=museo', 'root', '');
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConection() {
        return $this->conexion;
    }
}
