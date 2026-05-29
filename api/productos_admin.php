<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado.']);
    exit;
}

require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/lib/imagen_producto.php';

header('Content-Type: application/json; charset=utf-8');

$tieneImagenUrl = false;
$cols = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen_url'");
if ($cols && $cols->num_rows > 0) {
    $tieneImagenUrl = true;
}

$campos = 'ID_PROD, DESCRIP, COSTO, Stock, CATEGO';
if ($tieneImagenUrl) {
    $campos .= ', imagen_url';
}

$sql = "SELECT {$campos} FROM productos ORDER BY ID_PROD DESC";
$result = $conn->query($sql);
$productos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagenUrl = $tieneImagenUrl ? ($row['imagen_url'] ?? '') : '';
        $row['imagen_url_raw'] = $imagenUrl;
        $row['imagen_resuelta'] = resolverImagenProducto((string) $row['ID_PROD'], $imagenUrl, $row['CATEGO']);
        $productos[] = $row;
    }
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE);
$conn->close();
