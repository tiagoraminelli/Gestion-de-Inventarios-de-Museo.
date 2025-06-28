<?php
namespace Model;

require_once __DIR__ . '/../config/db.php';

use PDO;
use PDOException;

class Geologia
{
    private $pdo;

    public function __construct()
    {
        global $config;
        $this->pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['username'],
            $config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    // Crear nuevo registro de geología
    public function crear($datos)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO geologia (tipo_rocas, descripcion, Pieza_idPieza) VALUES (:tipo_rocas, :descripcion, :pieza_id)");
            $stmt->execute([
                ':tipo_rocas' => $datos['tipo_rocas'],
                ':descripcion' => $datos['descripcion'],
                ':pieza_id' => $datos['Pieza_idPieza']
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Obtener geología por idPieza
    public function obtenerPorPieza($idPieza)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM geologia WHERE Pieza_idPieza = :id");
        $stmt->execute([':id' => $idPieza]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar registro existente
    public function actualizar($datos)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE geologia SET tipo_rocas = :tipo_rocas, descripcion = :descripcion WHERE Pieza_idPieza = :pieza_id");
            $stmt->execute([
                ':tipo_rocas' => $datos['tipo_rocas'],
                ':descripcion' => $datos['descripcion'],
                ':pieza_id' => $datos['Pieza_idPieza']
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Eliminar por pieza
    public function eliminarPorPieza($idPieza)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM geologia WHERE Pieza_idPieza = :id");
            $stmt->execute([':id' => $idPieza]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
