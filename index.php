<?php
session_start(); // Démarrer la session dès le début du fichier

// Si l'utilisateur est déjà connecté, on le redirige vers le tableau de bord
if (isset($_SESSION['username'])) {
    header('Location: tableau_de_bord.php');
    exit();
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validation des informations d'identification avec le fichier users.json
    if (valid_credentials($username, $password)) {
        $_SESSION['username'] = $username;  // Sauvegarde du nom d'utilisateur dans la session
        header('Location: tableau_de_bord.php');  // Redirection vers le tableau de bord
        exit();
    } else {
        $error_message = "Identifiants invalides";  // Message d'erreur si les identifiants sont incorrects
    }
}

function valid_credentials($username, $password) {
    // Chemin vers le fichier users.json
    $users_file = 'users.json';

    // Vérifier si le fichier existe
    if (!file_exists($users_file)) {
        die("Fichier des utilisateurs introuvable.");
    }

    // Lire le fichier users.json
    $users_data = file_get_contents($users_file);
    $users = json_decode($users_data, true);

    // Vérifier les erreurs de décodage JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erreur lors de l'analyse du fichier JSON : " . json_last_error_msg());
    }

    // Parcourir les utilisateurs et vérifier les identifiants
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            // Vérifier si le mot de passe correspond à celui stocké (haché)
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    }

    return false;
}
?>
