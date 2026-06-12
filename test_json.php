<?php
header('Content-Type: application/json; charset=utf-8');
$input = json_decode(file_get_contents('php://input'), true);
var_dump($input);
echo "\n";
echo bin2hex(file_get_contents('php://input'));
?>