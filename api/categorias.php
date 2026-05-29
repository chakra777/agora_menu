<?php
require_once dirname(__DIR__) . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$res = $conn->query("SELECT id_catego, categoria, f_preparacion FROM cat_productos ORDER BY id_catego");
$categorias = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $categorias[] = [
            'id_catego' => (int) $row['id_catego'],
            'categoria' => $row['categoria'],
            'f_preparacion' => (int) $row['f_preparacion']
        ];
    }
}

echo json_encode($categorias, JSON_UNESCAPED_UNICODE);
$conn->close();
