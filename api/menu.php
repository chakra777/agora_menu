<?php
require_once dirname(__DIR__) . '/conexion.php';
require_once dirname(__DIR__) . '/lib/imagen_producto.php';

header('Content-Type: application/json; charset=utf-8');

$ordenCategorias = [];
$resCat = $conn->query("SELECT categoria FROM cat_productos ORDER BY id_catego");
if ($resCat && $resCat->num_rows > 0) {
    while ($rowCat = $resCat->fetch_assoc()) {
        if (!empty($rowCat['categoria'])) {
            $ordenCategorias[] = $rowCat['categoria'];
        }
    }
} else {
    $ordenCategorias = ['Cocina', 'Cafe', 'Bebida', 'Mostrador', 'Barra'];
}

$tieneImagenUrl = false;
$cols = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen_url'");
if ($cols && $cols->num_rows > 0) {
    $tieneImagenUrl = true;
}

$campos = 'ID_PROD, DESCRIP, COSTO, CATEGO';
if ($tieneImagenUrl) {
    $campos .= ', imagen_url';
}

$sql = "SELECT {$campos}
        FROM productos
        WHERE Stock = 1
        ORDER BY CATEGO, DESCRIP";

$result = $conn->query($sql);

$porCategoria = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoria = $row['CATEGO'];
        $imagenUrl = $tieneImagenUrl ? ($row['imagen_url'] ?? null) : null;
        unset($row['CATEGO']);
        if ($tieneImagenUrl) {
            unset($row['imagen_url']);
        }
        $row['imagen'] = resolverImagenProducto((string) $row['ID_PROD'], $imagenUrl, $categoria);
        $porCategoria[$categoria][] = $row;
    }
}

$categorias = [];
foreach ($ordenCategorias as $nombre) {
    $categorias[] = [
        'nombre' => $nombre,
        'productos' => !empty($porCategoria[$nombre]) ? $porCategoria[$nombre] : [],
    ];
    unset($porCategoria[$nombre]);
}

foreach ($porCategoria as $nombre => $productos) {
    $categorias[] = [
        'nombre' => $nombre,
        'productos' => $productos,
    ];
}

echo json_encode([
    'categorias' => $categorias,
    'placeholderDefault' => 'assets/placeholders/default.svg',
], JSON_UNESCAPED_UNICODE);
$conn->close();
