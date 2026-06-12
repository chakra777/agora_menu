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
$id_prod = filter_var($input['id_prod'] ?? null, FILTER_VALIDATE_INT);

if ($id_prod === false || $id_prod === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de producto inválido o ausente.']);
    exit;
}

// Check if product exists
$stmtCheck = $conn->prepare("SELECT ID_PROD FROM productos WHERE ID_PROD = ?");
$stmtCheck->bind_param("i", $id_prod);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado.']);
    $stmtCheck->close();
    $conn->close();
    exit;
}
$stmtCheck->close();

// Delete related records first (if any) to avoid foreign key constraint issues
// Delete from venta table (sales records)
$stmtVenta = $conn->prepare("DELETE FROM venta WHERE ID_PRODUCTO = ?");
$stmtVenta->bind_param("i", $id_prod);
$stmtVenta->execute();
$stmtVenta->close();

// Delete from mesas_historico if needed (though this references tickets, not products directly)
// Actually, mesas_historico references id_ticket, not products, so no need

// Now delete the product
$stmt = $conn->prepare("DELETE FROM productos WHERE ID_PROD = ?");
$stmt->bind_param("i", $id_prod);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>