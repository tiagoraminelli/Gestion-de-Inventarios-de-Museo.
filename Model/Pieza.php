<?php
namespace Model;
require_once __DIR__ . '/Bd.php';
use Model\Bd; // Importa la clase Db para la conexión a la base de datos

class Pieza {
    protected $table = "pieza"; // Nombre de la tabla
    protected $conection; // Conexión a la base de datos

    // Propiedades de la clase que coinciden con las columnas de la base de datos
    private $idPrimaria;
    private $numInventario;
    private $especie;
    private $estadoConservacion;
    private $fechaIngreso;
    private $cantidadPiezas;
    private $clasificacion;
    private $observacion;
    private $imagen;
    private $donanteIdDonante;


    // Constructor
    public function __construct(
        $idPrimaria = null,
        $numInventario = null,
        $especie = null,
        $estadoConservacion = null,
        $fechaIngreso = null,
        $cantidadPiezas = null,
        $clasificacion = null,
        $observacion = null,
        $imagen = null,
        $donanteIdDonante = null
    ) {
        $this->idPrimaria = $idPrimaria;
        $this->numInventario = $numInventario;
        $this->especie = $especie;
        $this->estadoConservacion = $estadoConservacion;
        $this->fechaIngreso = $fechaIngreso;
        $this->cantidadPiezas = $cantidadPiezas;
        $this->clasificacion = $clasificacion;
        $this->observacion = $observacion;
        $this->imagen = $imagen;
        $this->donanteIdDonante = $donanteIdDonante;
        $this->getConection(); // Establece la conexión a la base de datos
    }

    // Establece la conexión con la base de datos
    public function getConection() {
        return $this->conection;
    }
    


    // Getters
    public function getIdPrimaria() {
        return $this->idPrimaria;
    }

    public function getNumInventario() {
        return $this->numInventario;
    }

    public function getEspecie() {
        return $this->especie;
    }

    public function getEstadoConservacion() {
        return $this->estadoConservacion;
    }

    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    public function getCantidadPiezas() {
        return $this->cantidadPiezas;
    }

    public function getClasificacion() {
        return $this->clasificacion;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    // Reemplazar el método getImagen
    public function getImagen() {
    return $this->imagen;
    }

    public function getDonanteIdDonante() {
        return $this->donanteIdDonante;
    }

    // Setters
    public function setIdPrimaria($idPrimaria) {
        $this->idPrimaria = $idPrimaria;
    }

    public function setNumInventario($numInventario) {
        $this->numInventario = $numInventario;
    }

    public function setEspecie($especie) {
        $this->especie = $especie;
    }

    public function setEstadoConservacion($estadoConservacion) {
        $this->estadoConservacion = $estadoConservacion;
    }

    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;
    }

    public function setCantidadPiezas($cantidadPiezas) {
        $this->cantidadPiezas = $cantidadPiezas;
    }

    public function setClasificacion($clasificacion) {
        $this->clasificacion = $clasificacion;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function setDonanteIdDonante($donanteIdDonante) {
        $this->donanteIdDonante = $donanteIdDonante;
    }

}