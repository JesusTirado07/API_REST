<?php
require_once '../config/database.php';

class Usuario {
    private $conn;

    public function __construct() {
        
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUsuarios() {

        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuarioById($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUsuario($nombre, $correo, $password) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":correo", $correo);
            $stmt->bindParam(":password", $hashed_password);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }

    public function updateUsuario($id, $nombre, $correo, $password = null) {
        try {
            $query = "UPDATE usuarios SET nombre = :nombre, correo = :correo";
            if ($password) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $query .= ", password = :password";
            }
            $query .= " WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":correo", $correo);
            if ($password) {
                $stmt->bindParam(":password", $hashed_password);
            }
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    public function deleteUsuario($id) {
        try {
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    public function asignarRoles($usuario_id, $roles) {
        try {
            $this->conn->beginTransaction();

            $query = "DELETE FROM usuario_roles WHERE usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            foreach ($roles as $rol_id) {
                $query = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":usuario_id", $usuario_id);
                $stmt->bindParam(":rol_id", $rol_id);
                $stmt->execute();
            }

            $this->conn->commit();
            return ["message" => "Roles asignados correctamente"];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["message" => "Error al asignar roles: " . $e->getMessage()];
        }
    }



}
?>
