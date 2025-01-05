<?php
// Fonction pour ajouter un utilisateur dans le fichier JSON
function createUser($username, $password) {
    $file = 'users.json'; // Le fichier JSON qui stocke les utilisateurs

    // Vérifier si le fichier existe
    if (file_exists($file)) {
        $json_data = file_get_contents($file);
        $users = json_decode($json_data, true); // Décoder les données JSON dans un tableau PHP
    } else {
        $users = []; // Si le fichier n'existe pas, créer un tableau vide
    }

    // Hacher le mot de passe pour plus de sécurité
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Ajouter le nouvel utilisateur
    $users[] = ['username' => $username, 'password' => $hashed_password];

    // Réécrire les données dans le fichier JSON
    if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Échec de l\'écriture dans le fichier.']);
    }
}

// Fonction pour supprimer un utilisateur
function deleteUser($username) {
    $file = 'users.json'; // Le fichier JSON qui stocke les utilisateurs

    // Vérifier si le fichier existe
    if (file_exists($file)) {
        $json_data = file_get_contents($file);
        $users = json_decode($json_data, true); // Décoder les données JSON dans un tableau PHP

        // Filtrer l'utilisateur à supprimer
        $users = array_filter($users, function($user) use ($username) {
            return $user['username'] !== $username;
        });

        // Réindexer l'array
        $users = array_values($users);

        // Réécrire les données dans le fichier JSON
        if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Échec de la suppression.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Fichier non trouvé.']);
    }
}

// Si le formulaire de création est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username']) && isset($_POST['new_password'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    // Appeler la fonction pour ajouter l'utilisateur
    createUser($username, $password);
}

// Si une demande de suppression est reçue
if (isset($_GET['delete_username'])) {
    $username_to_delete = $_GET['delete_username'];

    // Appeler la fonction pour supprimer l'utilisateur
    deleteUser($username_to_delete);
}
?>
