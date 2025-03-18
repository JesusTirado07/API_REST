<?php
require_once '../models/Modulo.php';

class ModulosController {

    private $modulo;

    public function __construct() {

        $this->modulo = new Modulo();
    }

    public function getModulos() {

        $modulos = $this->modulo->getModulos();
        echo json_encode($modulos);
    }

    public function getModulo($id) {

        $modulo = $this->modulo->getModulo($id);
        if ($modulo) {

            echo json_encode($modulo);
        } else {

            echo json_encode(["message" => "Módulo no encontrado"]);
            http_response_code(404);
        }
    }

    public function createModulo($nombre) {

        if ($this->modulo->createModulo($nombre)) {

            echo json_encode(["message" => "Módulo creado correctamente"]);
        } else {

            echo json_encode(["message" => "Error al crear módulo"]);
        }
    }

    public function updateModulo($id, $nombre) {

        if ($this->modulo->updateModulo($id, $nombre)) {

            echo json_encode(["message" => "Módulo actualizado correctamente"]);
        } else {

            echo json_encode(["message" => "Error al actualizar módulo"]);
        }
    }

    public function deleteModulo($id) {

        if ($this->modulo->deleteModulo($id)) {

            echo json_encode(["message" => "Módulo eliminado correctamente"]);
        } else {

            echo json_encode(["message" => "Error al eliminar módulo"]);
        }
    }

    
}

$controller = new ModulosController();

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if (isset($_GET['id'])) {

            $controller->getModulo($_GET['id']);
        } else {

            $controller->getModulos();
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $controller->createModulo($data->nombre);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $controller->updateModulo($_GET['id'], $data->nombre);
        break;

    case 'DELETE':
        $controller->deleteModulo($_GET['id']);
        break;

    default:
        echo json_encode(["message" => "Método no soportado"]);
        break;
}
?>
