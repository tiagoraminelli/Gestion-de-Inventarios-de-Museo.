<?php
namespace Controller;

require_once __DIR__ . '/../Model/Geologia.php';

use Model\Geologia;

class GeologiaController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Geologia();
    }

    public function guardar($post, $idPieza)
    {
        $data = [
            'tipo_rocas' => $post['tipo_rocas'] ?? '',
            'descripcion' => $post['descripcion_geologia'] ?? '',
            'Pieza_idPieza' => $idPieza
        ];

        // Si ya existe un registro de geología para esta pieza, actualiza; si no, crea uno nuevo
        $existente = $this->modelo->obtenerPorPieza($idPieza);
        if ($existente) {
            return $this->modelo->actualizar($data);
        } else {
            return $this->modelo->crear($data);
        }
    }

    public function eliminar($idPieza)
    {
        return $this->modelo->eliminarPorPieza($idPieza);
    }

    public function obtener($idPieza)
    {
        return $this->modelo->obtenerPorPieza($idPieza);
    }
}
