<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado.']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET['q'] ?? $_POST['q'] ?? '');

if (empty($query)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El término de búsqueda es requerido.']);
    exit;
}

$user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";

// 1. Get the VQD token from the search page
$url = "https://duckduckgo.com/?q=" . urlencode($query) . "&iax=images&ia=images";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$html = curl_exec($ch);
curl_close($ch);

$vqd = null;
if (preg_match("/vqd=['\"]?([^'\";#\s]+)['\"]?/", $html, $matches)) {
    $vqd = $matches[1];
}

if (!$vqd && preg_match('/vqd\s*=\s*[\'"]([^\'"]+)[\'"]/', $html, $matches)) {
    $vqd = $matches[1];
}

if (!$vqd && preg_match('/vqd\s*:\s*[\'"]([^\'"]+)[\'"]/', $html, $matches)) {
    $vqd = $matches[1];
}

if (!$vqd) {
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'No se pudo inicializar la búsqueda con el buscador web (Token VQD ausente).']);
    exit;
}

// 2. Query DuckDuckGo's internal images endpoint
$api_url = "https://duckduckgo.com/i.js?" . http_build_query([
    'q' => $query,
    'vqd' => $vqd,
    't' => 'h_',
    'iax' => 'images',
    'ia' => 'images',
    'f' => ',,,',
    'o' => 'json'
]);

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$json_response = curl_exec($ch);
curl_close($ch);

$results = json_decode($json_response, true);
$formatted = [];

if (isset($results['results']) && is_array($results['results'])) {
    // Limit to top 12 results
    $count = 0;
    foreach ($results['results'] as $res) {
        if ($count >= 12) break;
        if (!empty($res['image']) && !empty($res['thumbnail'])) {
            $formatted[] = [
                'image' => $res['image'],
                'thumbnail' => $res['thumbnail'],
                'title' => $res['title'] ?? ''
            ];
            $count++;
        }
    }
    echo json_encode(['success' => true, 'images' => $formatted]);
} else {
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'El buscador web no devolvió resultados de imágenes válidos.']);
}
