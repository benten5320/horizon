<?php
header('Content-Type: application/json');

// Récupérer les données JSON du POST
$data = json_decode(file_get_contents('php://input'), true);

$password = $data['password'];
$hashedPassword = $data['hashedPassword'];

// Vérifier le mot de passe
if (password_verify($password, $hashedPassword)) {
    echo json_encode(['valid' => true]);
} else {
    echo json_encode(['valid' => false]);
}
?>
