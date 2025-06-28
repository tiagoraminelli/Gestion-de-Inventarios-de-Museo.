<?php
namespace Model;

require_once __DIR__ . '/Bd.php';
use Model\Bd;

class Donador {
    protected $table = "donadores";
    protected $conection;

    private $idDonante;
    private $nombre;
    private $apellido;
    private $fecha;
    private $createdAt;
    private $updatedAt;

    // Constructor
    public function __construct(
        $idDonante = null,
        $nombre = null,
        $apellido = null,
        $fecha = null,
        $createdAt = null,
        $updatedAt = null
    ) {
        $this->idDonante = $idDonante;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->fecha = $fecha;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->getConection();
    }

        // Establece la conexión con la base de datos
    public function getConection() {
        return $this->conection;
    }
    // Getters
    public function getIdDonante() {
        return $this->idDonante;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    // Setters
    public function setIdDonante($idDonante) {
        $this->idDonante = $idDonante;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
}