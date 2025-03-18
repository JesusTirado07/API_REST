<?php
require_once '../config/database.php';

class Modulo {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createModulo($nombre) {

        try {
            
            $query = "INSERT INTO modulos (nombre) VALUES (:nombre)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);

            if ($stmt->execute()) {

                return ["message" => "Módulo creado correctamente"];
            } else {

                return ["message" => "Error al crear módulo"];
            }
        } catch (Exception $e) {

            return ["message" => "Error: " . $e->getMessage()];
        }
    }

    public function getModulos() {

        $query = "SELECT * FROM modulos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getModulo($id) {

        $query = "SELECT * FROM modulos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateModulo($id, $nombre) {

        try {

            $query = "UPDATE modulos SET nombre = :nombre WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {

                return ["message" => "Módulo actualizado correctamente"];
            } else {
                return ["message" => "Error al actualizar módulo"];
            }
        } catch (Exception $e) {

            return ["message" => "Error: " . $e->getMessage()];
        }
    }

    public function deleteModulo($id) {

        try {

            $query = "DELETE FROM modulos WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {

                return ["message" => "Módulo eliminado correctamente"];
            } else {

                return ["message" => "Error al eliminar módulo"];
            }
        } catch (Exception $e) {

            return ["message" => "Error: " . $e->getMessage()];
        }
    }

       public function asignarModulos($rol_id, $modulos) {

        try {

            $query = "DELETE FROM rol_modulos WHERE rol_id = :rol_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':rol_id', $rol_id);
            $stmt->execute();

            foreach ($modulos as $modulo_id) {

                $query = "INSERT INTO rol_modulos (rol_id, modulo_id) VALUES (:rol_id, :modulo_id)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':rol_id', $rol_id);
                $stmt->bindParam(':modulo_id', $modulo_id);
                $stmt->execute();
            }

            return ["message" => "Módulos asignados correctamente"];
        } catch (Exception $e) {

            return ["message" => "Error al asignar módulos: " . $e->getMessage()];
        }
    }

}
?>
