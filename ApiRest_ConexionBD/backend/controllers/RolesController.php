<?php
require_once '../models/Rol.php';

class RolesController {

    private $rol;

    public function __construct() {

        $this->rol = new Rol();
    }

    public function asignarRolesAUsuario($usuario_id) {

        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->roles) && is_array($data->roles)) {
            
            foreach ($data->roles as $rol_id) {

                $this->rol->asignarRolAUsuario($usuario_id, $rol_id);
            }
            echo json_encode(["message" => "Roles asignados correctamente"]);
        } else {

            echo json_encode(["message" => "Faltan datos de roles"]);
            http_response_code(400);
        }
    }

    public function obtenerRoles() {

        $roles = $this->rol->obtenerRoles();
        echo json_encode($roles);
    }

    public function obtenerRolPorId($id) {


        $rol = $this->rol->obtenerRolPorId($id);
        if ($rol) {

            echo json_encode($rol);
        } else {

            echo json_encode(["message" => "Rol no encontrado"]);
            http_response_code(404);
        }
    }

    public function crearRol() {

        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre)) {

            $nombre = $data->nombre;
            if ($this->rol->crearRol($nombre)) {

                echo json_encode(["message" => "Rol creado correctamente"]);
            } else {

                echo json_encode(["message" => "Error al crear rol"]);
            }
        } else {

            echo json_encode(["message" => "Faltan datos"]);
            http_response_code(400);
        }
    }

    public function actualizarRol($id) {

        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre)) {
            $nombre = $data->nombre;
            if ($this->rol->actualizarRol($id, $nombre)) {
                echo json_encode(["message" => "Rol actualizado correctamente"]);
            } else {
                echo json_encode(["message" => "Error al actualizar rol"]);
            }
        } else {
            echo json_encode(["message" => "Faltan datos"]);
            http_response_code(400);
        }
    }

    public function eliminarRol($id) {

        if ($this->rol->eliminarRol($id)) {
            echo json_encode(["message" => "Rol eliminado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al eliminar rol"]);
        }
    }
    
    public function asignarModulos() {

        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->modulos) && is_array($data->modulos)) {
            $rol_id = $_GET['rol_id']; 
            $modulos = $data->modulos;  
        
            $response = $this->rol->asignarModulos($rol_id, $modulos);
            
            if ($response) {
                echo json_encode(["message" => "Modulos a Rol asignados correctamente"]);
            } else {
                echo json_encode(["message" => "Error al asignar al rol los modulos"]);
                http_response_code(500); 
            }
        } else {
            echo json_encode(["message" => "Módulos no proporcionados o formato incorrecto"]);
            http_response_code(400);
        }
    }

    
}

$controller = new RolesController();

switch ($_SERVER['REQUEST_METHOD']) {


    case 'GET':
        if (isset($_GET['id'])) {

            $controller->obtenerRolPorId($_GET['id']);
        } else {
            $controller->obtenerRoles();
        }
        break;


    case 'POST':
        if (!isset($_GET['rol_id'])) {

            $controller->crearRol();
        } else {
            $controller->asignarModulos();
        }
        break;
    

    case 'PUT':
        if (isset($_GET['id'])) {

            $controller->actualizarRol($_GET['id']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {

            $controller->eliminarRol($_GET['id']);
        }
        break;

    default:
        echo json_encode(["message" => "Método no soportado"]);
        break;

}

?>
