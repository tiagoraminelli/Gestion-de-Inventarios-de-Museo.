<?php

namespace Controller;

use Model\Bd; // Importa la clase Db para la conexión a la base de datos
use Model\Pieza;

class PiezaController
{
    private $modelo;

    // Constructor
    public function __construct()
    {
        $this->modelo = new Bd();
    }

    public function allPiezas()
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result; // Devuelve el resultado como un array
    }

    public function getPiezaById($id)
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza WHERE idPieza = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Devuelve un solo resultado
    }

    public function destroy($id)
    {
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

    public function buscarDonadores($searchTerm = '')
    {
        $stmt = $this->modelo->getConection()->prepare("
        SELECT idDonante, nombre, apellido 
        FROM donante 
        WHERE nombre LIKE :term OR apellido LIKE :term 
        LIMIT 10");
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindParam(':term', $likeTerm, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numInventario = $_POST['num_inventario'] ?? '';
            $especie = $_POST['especie'] ?? '';
            $estado = $_POST['estado_conservacion'] ?? '';
            $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
            $cantidad = $_POST['cantidad_de_piezas'] ?? '';
            $clasificacion = $_POST['clasificacion'] ?? '';
            $observacion = $_POST['observacion'] ?? '';
            $donanteId = $_POST['Donante_idDonante'] ?? '';
            $nombreImagen = '';

            // Manejo de la imagen si se proporciona
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $directorioDestino = __DIR__ . '/../assets/upload/';
                if (!is_dir($directorioDestino)) {
                    mkdir($directorioDestino, 0755, true);
                }

                $nombreOriginal = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreImagen = uniqid('pieza_') . '.' . $extension;
                $rutaCompleta = $directorioDestino . $nombreImagen;

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al subir la imagen'
                    ]);
                    exit;
                }
            }

            // Preparar datos para insertar
            $datos = [[
                'num_inventario' => $numInventario,
                'especie' => $especie,
                'estado_conservacion' => $estado,
                'fecha_ingreso' => $fechaIngreso,
                'cantidad_de_piezas' => $cantidad,
                'clasificacion' => $clasificacion,
                'observacion' => $observacion,
                'imagen' => $nombreImagen,
                'Donante_idDonante' => $donanteId
            ]];

            // Insertar datos
            $resultado = $this->insertarDatosGenerados($datos);

            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
        }
    }


    public function destroyMultiple($ids = [])
    {
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

    /**
     * Genera datos aleatorios para piezas
     * 
     * @param int $cantidad Número de registros a generar
     * @return array Array con los datos generados
     */
    public function generarDatosAleatorios($cantidad)
    {
        // Listas de valores posibles para los campos
        $estadosConservacion = ['Excelente', 'Bueno', 'Regular', 'Malo', 'Pésimo'];
        $especies = [
            'Tigre de Bengala',
            'León Africano',
            'Elefante Asiático',
            'Cebra Común',
            'Rinoceronte Blanco',
            'Jirafa Masai',
            'Canguro Rojo',
            'Oso Polar',
            'Pingüino Emperador',
            'Lobo Gris',
            'Águila Real',
            'Cocodrilo del Nilo'
        ];
        $clasificaciones = [
            'Paleontología',
            'Osteología',
            'Ictiología',
            'Geología',
            'Botánica',
            'Zoología',
            'Arqueología',
            'Entomología'
        ];
        $observaciones = [
            'En buen estado de conservación',
            'Requiere restauración urgente',
            'Pieza rara en la colección',
            'Donación reciente',
            'Procede de excavación arqueológica',
            'Especimen de gran valor científico',
            'Necesita análisis adicional'
        ];

        $datosGenerados = [];
        // Rango de fechas para 2024
        $inicio2024 = strtotime('2024-01-01');
        $fin2024 = strtotime('2024-12-31');

        for ($i = 0; $i < $cantidad; $i++) {
            // Generar timestamp aleatorio dentro de 2024
            $fechaAleatoria = mt_rand($inicio2024, $fin2024);
            $fechaIngreso = date('Y-m-d', $fechaAleatoria);
            $numInventario = 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $especie = $especies[array_rand($especies)];
            $estado = $estadosConservacion[array_rand($estadosConservacion)];
            $cantidadPiezas = rand(1, 15);
            $clasificacion = $clasificaciones[array_rand($clasificaciones)];
            $observacion = $observaciones[array_rand($observaciones)] . ' - ' . substr(md5(rand()), 0, 6);
            $donanteId = rand(1, 30); // IDs de donantes entre 1 y 30

            $datosGenerados[] = [
                'num_inventario' => $numInventario,
                'especie' => $especie,
                'estado_conservacion' => $estado,
                'fecha_ingreso' => $fechaIngreso,
                'cantidad_de_piezas' => $cantidadPiezas,
                'clasificacion' => $clasificacion,
                'observacion' => $observacion,
                'imagen' => 'especimen_' . rand(1, 10) . '.jpg',
                'Donante_idDonante' => $donanteId
            ];
        }

        return $datosGenerados;
    }

    public function insertarDatosGenerados($datos)
    {
        try {
            $conexion = $this->modelo->getConection();
            $sql = "INSERT INTO pieza (
                num_inventario, especie, estado_conservacion, fecha_ingreso, 
                cantidad_de_piezas, clasificacion, observacion, imagen, Donante_idDonante
            ) VALUES (
                :num_inventario, :especie, :estado_conservacion, :fecha_ingreso,
                :cantidad_de_piezas, :clasificacion, :observacion, :imagen, :donante_id
            )";

            $stmt = $conexion->prepare($sql);
            $insertados = 0;

            foreach ($datos as $dato) {
                $stmt->execute([
                    ':num_inventario' => $dato['num_inventario'],
                    ':especie' => $dato['especie'],
                    ':estado_conservacion' => $dato['estado_conservacion'],
                    ':fecha_ingreso' => $dato['fecha_ingreso'],
                    ':cantidad_de_piezas' => $dato['cantidad_de_piezas'],
                    ':clasificacion' => $dato['clasificacion'],
                    ':observacion' => $dato['observacion'],
                    ':imagen' => $dato['imagen'],
                    ':donante_id' => $dato['Donante_idDonante']
                ]);
                $insertados++;
            }

            return [
                'success' => true,
                'message' => "Se insertaron $insertados registros correctamente"
            ];
        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al insertar datos: ' . $e->getMessage()
            ];
        }
    }
} // Fin de la clase PiezaController

// ---------------------------
// Lógica para procesar la petición AJAX
// ---------------------------

require_once __DIR__ . '/../Model/Bd.php';
require_once __DIR__ . '/../Model/Pieza.php';




// Crear una instancia del controlador
$controller = new PiezaController();

// $datosAleatorios = $controller->generarDatosAleatorios(10); // Genera 10 registros
// $resultado = $controller->insertarDatosGenerados($datosAleatorios);
// if ($resultado['success']) {
//     echo $resultado['message'];
// } else {
//     echo 'Error: ' . $resultado['message'];
// }
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
    header('Content-Type: application/json'); // Siempre al inicio

    if (is_array($_POST['ids']) && count($_POST['ids']) > 0) {
        if (isset($_POST['action']) && $_POST['action'] === 'destroyMultiple') {
            $ids = $_POST['ids'];  // Los IDs de las piezas a eliminar
            $controller->destroyMultiple($ids);

            echo json_encode([
                'success' => true,
                'message' => 'Eliminado correctamente'
            ]);
            exit; // 🚨 Muy importante
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            exit; // 🚨 Muy importante
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se enviaron IDs válidos']);
        exit; // 🚨 Muy importante
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    if ($_GET['accion'] === 'ver' && isset($_GET['idPieza'])) {
        $idPieza = intval($_GET['idPieza']);
        //redireccionar a la vista
        header('Location: ../View/ver.php?idPieza=' . $idPieza);
        if ($pieza) {
            // Aquí puedes mostrar los detalles de la pieza
            echo json_encode($pieza);
        } else {
            echo json_encode(['error' => 'Pieza no encontrada']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'buscar_donador') {
    $search = $_POST['search'] ?? '';
    $resultados = $controller->buscarDonadores($search);
    header('Content-Type: application/json');
    echo json_encode($resultados);
    exit;
}

// Al inicio
if (isset($_GET['accion']) && $_GET['accion'] === 'obtener') {
    $id = intval($_GET['idPieza']);
    $pieza = $controller->getPiezaById($id); // Método que devuelve datos de la pieza
    header('Content-Type: application/json');
    echo json_encode($pieza);
    exit;
}
