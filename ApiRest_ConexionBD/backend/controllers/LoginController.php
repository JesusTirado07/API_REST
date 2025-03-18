<?php
require_once '../config/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['correo']) || !isset($data['password'])) {
        echo json_encode(["error" => "Correo y contraseña son obligatorios"]);
        http_response_code(400);
        exit;
    }

    $correo = $data['correo'];
    $password = $data['password'];

    try {

        $database = new Database();
        $pdo = $database->getConnection();

        $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            echo json_encode([
                "message" => "Inicio de sesión exitoso",
                "user" => ["id" => $usuario['id'], "nombre" => $usuario['nombre']],
                "status" => 200
            ]);
        } else {

            echo json_encode(["error" => "Credenciales incorrectas"]);
            http_response_code(401);
        }

        $database->disconnect();
        
    } catch (Exception $e) {
        
        echo json_encode(["error" => "Error en el servidor", "details" => $e->getMessage()]);
        http_response_code(500);
    }
}
?>
