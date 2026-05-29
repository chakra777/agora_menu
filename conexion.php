<?php
/**
 * Conexión MySQL: variables de entorno o valores por defecto XAMPP local.
 */
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
$port = (int) (getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306');
$username = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'comercial';

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    http_response_code(500);
    die('Conexión fallida: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
