<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado.']);
    exit;
}

require_once dirname(__DIR__) . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);

$action = trim($input['action'] ?? $_POST['action'] ?? '');
$id_prod = filter_var($input['id_prod'] ?? $_POST['id_prod'] ?? null, FILTER_VALIDATE_INT);
$descrip = trim($input['descrip'] ?? $_POST['descrip'] ?? '');
$costo = filter_var($input['costo'] ?? $_POST['costo'] ?? null, FILTER_VALIDATE_FLOAT);
$stock = isset($input['stock']) ? (int) $input['stock'] : (isset($_POST['stock']) ? (int) $_POST['stock'] : 1);
$catego = trim($input['catego'] ?? $_POST['catego'] ?? '');
$imagen_url = trim($input['imagen_url'] ?? $_POST['imagen_url'] ?? '');

if ($id_prod === false || $id_prod === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de producto inválido o ausente.']);
    exit;
}

if (empty($descrip)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La descripción es obligatoria.']);
    exit;
}

if ($costo === false || $costo === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El costo es inválido o ausente.']);
    exit;
}

if (empty($catego)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La categoría es obligatoria.']);
    exit;
}

// Check database table columns
$tieneImagenUrl = false;
$cols = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen_url'");
if ($cols && $cols->num_rows > 0) {
    $tieneImagenUrl = true;
}

if ($action === 'add') {
    // Check if ID_PROD already exists
    $stmtCheck = $conn->prepare("SELECT ID_PROD FROM productos WHERE ID_PROD = ?");
    $stmtCheck->bind_param("i", $id_prod);
    $stmtCheck->execute();
    $stmtCheck->store_result();
    if ($stmtCheck->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'Ya existe un producto con el ID especificado.']);
        $stmtCheck->close();
        $conn->close();
        exit;
    }
    $stmtCheck->close();

    // Insert new product
    if ($tieneImagenUrl) {
        $stmt = $conn->prepare("INSERT INTO productos (ID_PROD, DESCRIP, COSTO, Stock, CATEGO, imagen_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdiss", $id_prod, $descrip, $costo, $stock, $catego, $imagen_url);
    } else {
        $stmt = $conn->prepare("INSERT INTO productos (ID_PROD, DESCRIP, COSTO, Stock, CATEGO) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdis", $id_prod, $descrip, $costo, $stock, $catego);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto agregado con éxito.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al agregar el producto: ' . $stmt->error]);
    }
    $stmt->close();

} else if ($action === 'edit') {
    // Update existing product
    if ($tieneImagenUrl) {
        $stmt = $conn->prepare("UPDATE productos SET DESCRIP = ?, COSTO = ?, Stock = ?, CATEGO = ?, imagen_url = ? WHERE ID_PROD = ?");
        $stmt->bind_param("sdissi", $descrip, $costo, $stock, $catego, $imagen_url, $id_prod);
    } else {
        $stmt = $conn->prepare("UPDATE productos SET DESCRIP = ?, COSTO = ?, Stock = ?, CATEGO = ? WHERE ID_PROD = ?");
        $stmt->bind_param("sdisi", $descrip, $costo, $stock, $catego, $id_prod);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto actualizado con éxito.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al actualizar el producto: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Acción inválida.']);
}

$conn->close();
