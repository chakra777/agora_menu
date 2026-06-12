<?php
require_once dirname(__FILE__) . '/conexion.php';

// Set proper character set
$conn->set_charset("utf8mb4");

$result = $conn->query("SELECT id_usuario, usuario, contraseña FROM usuarios");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plainPassword = $row['contraseña'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $hashedPassword, $row['id_usuario']);
        if ($stmt->execute()) {
            echo "Updated password for user: {$row['usuario']}\n";
        } else {
            echo "Error updating password for {$row['usuario']}: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
} else {
    echo "No users found\n";
}

$conn->close();
?>