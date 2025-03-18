<?php
require_once '../models/Usuario.php';

class UsuariosController {

    private $usuarioModel;

    public function __construct() {

        $this->usuarioModel = new Usuario();
    }

    public function getUsuarios() {

        $usuarios = $this->usuarioModel->getUsuarios();
        echo json_encode($usuarios);
    }

    public function getUsuario($id) {

        $usuario = $this->usuarioModel->getUsuarioById($id);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            echo json_encode(["message" => "Usuario no encontrado"]);
            http_response_code(404);
        }
    }

    public function createUsuario($nombre, $correo, $password) {

        try {
            $this->usuarioModel->createUsuario($nombre, $correo, $password);
            echo json_encode(["message" => "Usuario creado correctamente"]);
        } catch (Exception $e) {
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function updateUsuario($id, $nombre, $correo, $password = null) {
        try {
            $this->usuarioModel->updateUsuario($id, $nombre, $correo, $password);
            echo json_encode(["message" => "Usuario actualizado correctamente"]);
        } catch (Exception $e) {
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function deleteUsuario($id) {
        try {
            $this->usuarioModel->deleteUsuario($id);
            echo json_encode(["message" => "Usuario eliminado correctamente"]);
        } catch (Exception $e) {
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function asignarRoles() {
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->roles) && is_array($data->roles)) {
            $usuario_id = $_GET['usuario_id'];
            $roles = $data->roles;
            $response = $this->usuarioModel->asignarRoles($usuario_id, $roles);
            echo json_encode($response);
        } else {
            echo json_encode(["message" => "Roles no proporcionados o formato incorrecto"]);
            http_response_code(400);
        }
    }

}

$controller = new UsuariosController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $controller->getUsuario($_GET['id']);
        } else {
            $controller->getUsuarios();
        }
        break;

    case 'POST':
        if (isset($_GET['usuario_id'])) {

            $controller->asignarRoles(); 
        } else {

            $data = json_decode(file_get_contents("php://input"));
            $controller->createUsuario($data->nombre, $data->correo, $data->password);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $controller->updateUsuario($_GET['id'], $data->nombre, $data->correo, $data->password);
        break;

    case 'DELETE':
        $controller->deleteUsuario($_GET['id']);
        break;

    default:
        echo json_encode(["message" => "Método no soportado"]);
        break;
}
?>
