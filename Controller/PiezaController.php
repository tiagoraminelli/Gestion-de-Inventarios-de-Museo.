<?php

namespace Controller;

require_once __DIR__ . '/../Model/Bd.php';

use Model\Bd;

class PiezaController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Bd();
    }

    // Devuelve todas las piezas
    public function allPiezas()
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Cuenta total de piezas
    public function countPiezas()
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT COUNT(*) FROM pieza");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPiezasPorClasificacion()
    {
        $stmt = $this->modelo->getConection()->query("
        SELECT clasificacion, COUNT(*) as total
        FROM pieza
        GROUP BY clasificacion
    ");
        $result = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
        return $result;
    }

    public function getPiezasPorAno() {
    $stmt = $this->modelo->getConection()->query("
        SELECT YEAR(fecha_ingreso) as ano, COUNT(*) as total
        FROM pieza
        GROUP BY YEAR(fecha_ingreso)
        ORDER BY ano
    ");
    $result = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    return $result;
}
public function getPiezasPorEstado() {
    $stmt = $this->modelo->getConection()->query("
        SELECT estado_conservacion, COUNT(*) as total
        FROM pieza
        GROUP BY estado_conservacion
    ");
    $result = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    return $result;
}

    public function getUltimasPiezasCreadas($limit)
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUltimasPiezasActualizadas($limit)
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza
            ORDER BY updated_at DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarPiezas($termino, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM pieza 
            WHERE especie LIKE :termino 
               OR estado_conservacion LIKE :termino 
               OR clasificacion LIKE :termino 
               OR num_inventario LIKE :termino";

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->modelo->getConection()->prepare($sql);
        $likeTerm = "%$termino%";
        $stmt->bindParam(':termino', $likeTerm, \PDO::PARAM_STR);

        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    // Piezas paginadas
    public function getPiezasPaginadas($limit, $offset)
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Obtener pieza por ID
    public function getPiezaById($id)
    {
        $stmt = $this->modelo->getConection()->prepare("SELECT * FROM pieza WHERE idPieza = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Crear nueva pieza
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $tipos_especies = ["Biologia", "Zoologia", "Botanica", "Geologia", "Paleontologia", "Arqueologia", "Entomologia", "Ictiología", "Octologia"];

        $numInventario = $_POST['num_inventario'] ?? '';
        $especie = $_POST['especie'] ?? '';
        $estado = $_POST['estado_conservacion'] ?? '';
        $fechaIngreso = $_POST['fecha_ingreso'] ?? '';
        $cantidad = $_POST['cantidad_de_piezas'] ?? '';
        $clasificacion = $_POST['clasificacion'] ?? '';
        $observacion = $_POST['observacion'] ?? '';
        $donanteId = $_POST['Donante_idDonante'] ?? '';
        $nombreImagen = '';

        $errors = [];

        // Validaciones
        if (empty($numInventario)) $errors[] = "El campo 'Número de Inventario' es obligatorio.";
        if (empty($especie)) $errors[] = "El campo 'Especie' es obligatorio.";
        if (is_numeric($estado)) $errors[] = "El campo 'Estado de Conservación' es inválido.";
        if (!in_array($clasificacion, $tipos_especies)) $errors[] = "La clasificación debe ser: " . implode(", ", $tipos_especies);
        if (!empty($fechaIngreso) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaIngreso)) $errors[] = "La fecha de ingreso debe tener formato YYYY-MM-DD.";
        if (!empty($fechaIngreso) && $fechaIngreso > date('Y-m-d')) $errors[] = "La fecha de ingreso no puede ser futura.";
        if (!is_numeric($cantidad)) $errors[] = "El campo 'Cantidad de Piezas' es inválido.";
        if (empty($donanteId)) $errors[] = "Debe seleccionar un donante.";

        if (!empty($errors)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        // Manejo de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $dir = __DIR__ . '/../assets/upload/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreImagen = uniqid('pieza_') . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombreImagen);
        }

        // Sanitización simple
        $numInventario = htmlspecialchars(trim($numInventario), ENT_QUOTES, 'UTF-8');
        $especie = htmlspecialchars(trim($especie), ENT_QUOTES, 'UTF-8');
        $estado = htmlspecialchars(trim($estado), ENT_QUOTES, 'UTF-8');
        $fechaIngreso = $fechaIngreso ?? null;
        $cantidad = htmlspecialchars(trim($cantidad), ENT_QUOTES, 'UTF-8');
        $observacion = htmlspecialchars(trim($observacion ?? ''), ENT_QUOTES, 'UTF-8');

        $stmt = $this->modelo->getConection()->prepare("INSERT INTO pieza (num_inventario, especie, estado_conservacion, fecha_ingreso,
                           cantidad_de_piezas, clasificacion, observacion, imagen, Donante_idDonante)
        VALUES (:num_inventario, :especie, :estado_conservacion, :fecha_ingreso,
                :cantidad_de_piezas, :clasificacion, :observacion, :imagen, :donante_id)
    ");

        $success = $stmt->execute([
            ':num_inventario' => $numInventario,
            ':especie' => $especie,
            ':estado_conservacion' => $estado,
            ':fecha_ingreso' => $fechaIngreso,
            ':cantidad_de_piezas' => $cantidad,
            ':clasificacion' => $clasificacion,
            ':observacion' => $observacion,
            ':imagen' => $nombreImagen,
            ':donante_id' => $donanteId
        ]);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Pieza creada correctamente' : 'Error al crear la pieza'
        ]);
        exit;
    }


    // Eliminar pieza por ID


    public function update($id, $data)
    {
        $tipos_especies = ["Biologia", "Zoologia", "Botanica", "Geologia", "Paleontologia", "Arqueologia", "Entomologia", "Ictiología"];

        // Validaciones
        if (empty($data['num_inventario'])) {
            throw new \Exception("El campo 'num_inventario' es obligatorio.");
        }
        if (empty($data['especie'])) {
            throw new \Exception("El campo 'especie' es obligatorio.");
        }
        if (is_numeric($data['estado_conservacion'])) {
            throw new \Exception("El campo 'estado_conservacion' es inválido.");
        }
        if (!in_array($data['clasificacion'], $tipos_especies)) {
            throw new \Exception("La clasificación debe ser: " . implode(", ", $tipos_especies));
        }
        if (!empty($data['fecha_ingreso']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_ingreso'])) {
            throw new \Exception("La fecha de ingreso debe tener formato YYYY-MM-DD.");
        }

        if ($data['fecha_ingreso'] > date('Y-m-d')) {
            throw new \Exception("La fecha de ingreso no puede ser futura.");
        }

        if (!is_numeric($data['cantidad_de_piezas'])) {
            throw new \Exception("El campo 'cantidad_de_piezas' es inválido.");
        }

        // Sanitización simple
        $num_inventario = htmlspecialchars(trim($data['num_inventario']), ENT_QUOTES, 'UTF-8');
        $especie = htmlspecialchars(trim($data['especie']), ENT_QUOTES, 'UTF-8');
        $estado_conservacion = htmlspecialchars(trim($data['estado_conservacion'] ?? ''), ENT_QUOTES, 'UTF-8');
        $fecha_ingreso = $data['fecha_ingreso'] ?? null;
        $cantidad_de_piezas = htmlspecialchars(trim($data['cantidad_de_piezas'] ?? ''), ENT_QUOTES, 'UTF-8');
        $clasificacion = $data['clasificacion'];
        $observacion = htmlspecialchars(trim($data['observacion'] ?? ''), ENT_QUOTES, 'UTF-8');
        $imagen = $data['imagen'] ?? null;

        $sql = "UPDATE pieza SET 
                num_inventario = :num_inventario,
                especie = :especie,
                estado_conservacion = :estado_conservacion,
                fecha_ingreso = :fecha_ingreso,
                cantidad_de_piezas = :cantidad_de_piezas,
                clasificacion = :clasificacion,
                observacion = :observacion";

        if (!empty($imagen)) $sql .= ", imagen = :imagen";
        $sql .= " WHERE idPieza = :id";

        $stmt = $this->modelo->getConection()->prepare($sql);
        $stmt->bindParam(':num_inventario', $num_inventario);
        $stmt->bindParam(':especie', $especie);
        $stmt->bindParam(':estado_conservacion', $estado_conservacion);
        $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
        $stmt->bindParam(':cantidad_de_piezas', $cantidad_de_piezas);
        $stmt->bindParam(':clasificacion', $clasificacion);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        if (!empty($imagen)) $stmt->bindParam(':imagen', $imagen);

        if (!$stmt->execute()) {
            throw new \Exception("Error al actualizar la pieza.");
        } else {
            $success = true;
        }
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Pieza actualizada correctamente' : 'Error al actualizar la pieza'
        ]);
        exit;
    }


    public function destroy($id)
    {
        $stmt = $this->modelo->getConection()->prepare("DELETE FROM pieza WHERE idPieza = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $stmt->rowCount() > 0,
            'message' => $stmt->rowCount() > 0 ? 'Pieza eliminada correctamente' : 'Pieza no encontrada'
        ]);
        exit;
    }
}


$controller = new PiezaController();

# Metodo para obtener pieza por ID

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'ver') {
    if (isset($_GET['idPieza'])) {
        $id = intval($_GET['idPieza']);
        $pieza = $controller->getPiezaById($id);

        header('Content-Type: application/json');
        if ($pieza) {
            echo json_encode($pieza);
        } else {
            echo json_encode(['success' => false, 'message' => 'Pieza no encontrada']);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID de pieza no proporcionado']);
    }
    exit;
}

# Metodo para editarlo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id = intval($_POST['idPieza']);
    $data = $_POST; // Si usas AJAX con formData, aquí van todos los campos
    try {
        $controller->update($id, $data);
        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'errors' => [$e->getMessage()]
        ]);
    }
    exit;
}


# Eliminar pieza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);
    $success = $controller->destroy($id);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Pieza eliminada correctamente' : 'Error al eliminar la pieza'
    ]);
    exit;
}

// crear pieza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $data = $_POST; // todos los campos del formulario

    // Manejo de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];
        $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('pieza_') . "." . $ext;
        $rutaImagen = '../assets/upload/' . $nombreArchivo;

        if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            $data['imagen'] = $nombreArchivo;
        } else {
            echo json_encode([
                'success' => false,
                'errors' => ['Error al subir la imagen.']
            ]);
            exit;
        }
    }

    try {
        $resultado = $controller->create($data); // llama al método del controlador
        if ($resultado['success']) {
            echo json_encode(['success' => true, 'message' => $resultado['message']]);
        } else {
            echo json_encode(['success' => false, 'errors' => [$resultado['message']]]);
        }
    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'errors' => [$e->getMessage()]
        ]);
    }
    exit;
}
