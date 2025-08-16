<?php

namespace Controller;
use Model\Bd;

class DonanteController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Bd();
    }

    // Obtener todos los donantes
    public function allDonantes() {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM donante");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener un donante por ID
    public function getDonanteById($id) {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM donante WHERE idDonante = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function countDonantes() {
        $stmt = $this->modelo->getConection()->query("SELECT COUNT(*) as total FROM donante");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Obtener donantes por especie
public function getPiezasPorDonante() {
    $stmt = $this->modelo->getConection()->query("
        SELECT CONCAT(d.nombre, ' ', d.apellido) as donante, COUNT(p.idPieza) as total
        FROM pieza p
        JOIN donante d ON p.Donante_idDonante = d.idDonante
        GROUP BY p.Donante_idDonante
    ");
    
    $result = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    return $result;
}

    // Crear un nuevo donante
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $fecha = $_POST['fecha'] ?? '';

            if (empty($nombre) || empty($apellido) || empty($fecha)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Faltan datos obligatorios'
                ]);
                exit;
            }

            $stmt = $this->modelo->getConection()->prepare("INSERT INTO donante (nombre, apellido, fecha) VALUES (:nombre, :apellido, :fecha)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':fecha', $fecha);

            header('Content-Type: application/json');
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Donante creado correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear donante'
                ]);
            }
            exit;
        }
    }

    // Eliminar un donante por id
    public function destroy($id) {
        $stmt = $this->modelo->getConection()->prepare("DELETE FROM donante WHERE idDonante = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        header('Content-Type: application/json');

        if ($stmt->rowCount() > 0) {
            $response = [
                'success' => true,
                'message' => 'Donante eliminado correctamente'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No se encontró el donante o ya fue eliminado.'
            ];
        }

        echo json_encode($response);
        exit;
    }

    // Eliminar múltiples donantes
    public function destroyMultiple($ids = []) {
        if (is_array($ids) && count($ids) > 0) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->modelo->getConection()->prepare("DELETE FROM donante WHERE idDonante IN ($placeholders)");

            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, \PDO::PARAM_INT);
            }

            $stmt->execute();

            header('Content-Type: application/json');

            if ($stmt->rowCount() > 0) {
                $response = [
                    'success' => true,
                    'message' => 'Donantes eliminados correctamente.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No se encontraron donantes o ya fueron eliminados.'
                ];
            }

            echo json_encode($response);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se seleccionaron donantes para eliminar.'
            ]);
        }
        exit;
    }

    // Buscar donantes por nombre o apellido
    public function buscarDonantes($searchTerm = '') {
        $stmt = $this->modelo->getConection()->prepare("
            SELECT idDonante, nombre, apellido 
            FROM donante 
            WHERE nombre LIKE :term OR apellido LIKE :term 
            LIMIT 10
        ");
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindParam(':term', $likeTerm, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
