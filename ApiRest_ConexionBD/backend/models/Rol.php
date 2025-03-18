<?php
require_once '../config/database.php';

class Rol {
    
    private $conn;
    private $table_name = "roles";
    public $id;
    public $nombre;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function asignarRolAUsuario($usuario_id, $rol_id) {
        try {
            $query = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->bindParam(":rol_id", $rol_id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerRoles() {

        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRolPorId($id) {

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearRol($nombre) {

        $query = "INSERT INTO " . $this->table_name . " (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function actualizarRol($id, $nombre) {

        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminarRol($id) {

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

     public function asignarModulos($rol_id, $modulos) {

        $db = $this->conn;

        $db->beginTransaction();

        try {
            $query = "DELETE FROM rol_modulos WHERE rol_id = :rol_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->execute();

            foreach ($modulos as $modulo_id) {
                $query = "INSERT INTO rol_modulos (rol_id, modulo_id) VALUES (:rol_id, :modulo_id)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
                $stmt->bindParam(':modulo_id', $modulo_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $db->commit();

            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

}
?>
