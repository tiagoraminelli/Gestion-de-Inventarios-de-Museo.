<?php
namespace Model;

class Bd {
    public $conection;

    public function __construct() {
        try {
            $this->conection = new \PDO('mysql:host=localhost;dbname=museo', 'root', '');
            $this->conection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }
    public function getConection() {
        return $this->conection;
    }
}
