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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function redirectToSubmitForm() {
            const password = prompt("Veuillez entrer le mot de passe pour accéder à cette page :");
            if (password === "B1j@m120031") {
                window.location.href = "user_management.html";
            } else {
                alert("Mot de passe incorrect !");
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <button onclick="redirectToSubmitForm()" style="position: absolute; top: 10px; left: 10px;">Accès spécial</button>
        <h2>Connexion</h2>
        <form action="index.php" method="POST">
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if (isset($error_message)) { echo '<div class="error-message">' . $error_message . '</div>'; } ?>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>

