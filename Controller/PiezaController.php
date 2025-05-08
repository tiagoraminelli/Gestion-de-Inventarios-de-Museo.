<?php

namespace Controller;
use Model\Bd; // Importa la clase Db para la conexión a la base de datos
use Model\Pieza;

class PiezaController {
    private $modelo;

    // Constructor
    public function __construct() {
        $this->modelo = new Bd();
    }

    public function allPiezas(){
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result; // Devuelve el resultado como un array
    }

    public function getPiezaById($id) {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza WHERE idPrimaria = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Devuelve un solo resultado
    }    

    public function destroy($id) {
        $stmt = $this->modelo->getConection()->prepare("DELETE FROM pieza WHERE idPieza = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        header('Content-Type: application/json');

        if ($stmt->rowCount() > 0) {
            $response = [
                'success' => true,
                'message' => 'Pieza eliminada correctamente'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No se encontró la pieza o ya fue eliminada.'
            ];
        }

        echo json_encode($response);
        exit;
    }

    public function destroyMultiple($ids = []) {
        // Asegúrate de que $ids es un array y que no está vacío
        if (is_array($ids) && count($ids) > 0) {
            // Convierte el array de IDs en una cadena separada por comas
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
            // Prepara la consulta de eliminación
            $stmt = $this->modelo->getConection()->prepare("DELETE FROM pieza WHERE idPieza IN ($placeholders)");
            
            // Vincula los parámetros
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, \PDO::PARAM_INT);
            }
    
            // Ejecuta la consulta
            $stmt->execute();
    
            // Responde en formato JSON
            header('Content-Type: application/json');
            
            // Verifica si se eliminaron filas
            if ($stmt->rowCount() > 0) {
                $response = [
                    'success' => true,
                    'message' => 'Las piezas seleccionadas han sido eliminadas correctamente.'
                ];
            } else {
                // En caso de que no se hayan eliminado piezas
                $response = [
                    'success' => false,
                    'message' => 'No se encontraron piezas o ya fueron eliminadas.'
                ];
            }
    
            // Enviar la respuesta en formato JSON
            echo json_encode($response);
        } else {
            // En caso de que el array $ids esté vacío o no sea un array
            echo json_encode([
                'success' => false,
                'message' => 'No se seleccionaron piezas para eliminar.'
            ]);
        }
    
        // Terminar el script para evitar otros posibles errores
        exit;
    }

    public function generarDatosAleatorios($cantidad) {
        // Lista de valores posibles para los campos
        $estadoConservacion = ['Excelente', 'Bueno', 'Regular', 'Malo'];
        $especies = ['Tigre', 'León', 'Elefante', 'Cebra', 'Rinoceronte', 'Jirafa', 'Canguro'];
        $clasificaciones = ['Paleontología', 'Osteología', 'Ictiología', 'Geología', 'Botánica', 'Zoología', 'Arqueología', 'Octología'];
        
        // Crear array de datos
        $datosGenerados = [];
    
        for ($i = 0; $i < $cantidad; $i++) {
            // Generación de datos aleatorios
            $numInventario = 'PIE-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $especie = $especies[array_rand($especies)];
            $estado = $estadoConservacion[array_rand($estadoConservacion)];
            $fechaIngreso = date('Y-m-d', strtotime('-' . rand(1, 365) . ' days')); // Fecha aleatoria en el último año
            $cantidadPiezas = rand(1, 10);
            $clasificacion = $clasificaciones[array_rand($clasificaciones)];
            $observacion = "Observación aleatoria para " . $especie;     // Generar el Donante_idDonante aleatorio entre 2 y 28
            $donanteId = rand(2, 28);
    
            // Crear una entrada de datos para insertar
            $datosGenerados[] = [
                'num_inventario' => $numInventario,
                'especie' => $especie,
                'estado_conservacion' => $estado,
                'fecha_ingreso' => $fechaIngreso,
                'cantidad_de_piezas' => $cantidadPiezas,
                'clasificacion' => $clasificacion,
                'observacion' => $observacion,
                'imagen' => 'imagen_' . rand(1, 5) . '.jpg', // Imagen aleatoria
                'Donante_idDonante' =>  $donanteId // ID aleatorio de donante
            ];
        }
    
        return $datosGenerados;
    }
    
    
    
    
} // Fin de la clase PiezaController

// ---------------------------
// Lógica para procesar la petición AJAX
// ---------------------------

require_once __DIR__ . '/../Model/Bd.php';
require_once __DIR__ . '/../Model/Pieza.php';




// Crear una instancia del controlador
$controller = new PiezaController();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'destroy' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $controller->destroy($id);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acción no válida o faltan parámetros']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'No se especificó acción']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    // Verifica si 'ids' es un array y si contiene elementos
    if (is_array($_POST['ids']) && count($_POST['ids']) > 0) {
        if (isset($_POST['action']) && $_POST['action'] === 'destroyMultiple') {
            $ids = $_POST['ids'];  // Los IDs de las piezas a eliminar
            $controller->destroyMultiple($ids);
}}}

