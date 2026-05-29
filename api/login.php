<?php
session_start();
require_once dirname(__DIR__) . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Get JSON data
    $input = json_decode(file_get_contents('php://input'), true);
    $usuario = trim($input['usuario'] ?? $_POST['usuario'] ?? '');
    $contraseña = trim($input['contraseña'] ?? $_POST['contraseña'] ?? '');

    if (empty($usuario) || empty($contraseña)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Usuario y contraseña son requeridos.']);
        exit;
    }

    // Query to match credentials
    // Note: Database stores passwords in plain text currently
    $stmt = $conn->prepare("SELECT usuario, Rol FROM usuarios WHERE usuario = ? AND contraseña = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos al preparar la consulta.']);
        exit;
    }

    $stmt->bind_param("ss", $usuario, $contraseña);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $row['usuario'];
        $_SESSION['admin_role'] = $row['Rol'];

        echo json_encode([
            'success' => true,
            'user' => $row['usuario'],
            'role' => $row['Rol']
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Usuario o contraseña incorrectos.']);
    }

    $stmt->close();
    $conn->close();
} else if ($method === 'GET') {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        echo json_encode([
            'logged_in' => true,
            'user' => $_SESSION['admin_user'] ?? '',
            'role' => $_SESSION['admin_role'] ?? ''
        ]);
    } else {
        echo json_encode([
            'logged_in' => false
        ]);
    }
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    $conn->close();
}
