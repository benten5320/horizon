<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="tableau_de_bord.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <!-- Bandeau bleu en haut de la page -->
    <div class="header-banner">
        <span>Mon Tableau de Bord</span>
    </div>

    <!-- Bandeau de bienvenue avec le nom d'utilisateur -->
    <div class="welcome-banner">
        <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</span>
        <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </div>

    <h1>Bienvenue sur votre tableau de bord</h1>

    <!-- Conteneur pour les boutons et le formulaire -->
    <div class="main-container">
        <!-- Conteneur des boutons -->
        <div class="button-container">
            <a href="submit_form.php" class="btn">Portefeuille</a>
            <button class="btn" id="chat-bot-btn">Chat Bot (en attente)</button>
        </div>

        <!-- Formulaire déplacé à droite -->
        <div class="form-container">
            <h2>Formulaire</h2>
            <form action="submit_form.php" method="POST">
                <!-- Nom du dossier -->
                <div class="input-group">
                    <label for="nom_dossier">Nom du dossier</label>
                    <input type="text" id="nom_dossier" name="nom_dossier" required>
                </div>

                <!-- Numéro d'affaire -->
                <div class="input-group">
                    <label for="numero_affaire">Numéro d'affaire</label>
                    <input type="text" id="numero_affaire" name="numero_affaire" required>
                </div>

                <!-- Adresse -->
                <div class="input-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>

                <!-- Téléphone -->
                <div class="input-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>

                <!-- E-mail -->
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <!-- Puissance projet -->
                <div class="input-group">
                    <label for="puissance_projet">Puissance projet</label>
                    <input type="number" id="puissance_projet" name="puissance_projet" required>
                </div>

                <!-- IRVE -->
                <div class="input-group">
                    <label for="irve">IRVE</label>
                    <select id="irve" name="irve" required>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>

                <!-- En AG -->
                <div class="input-group">
                    <label for="en_ag">En AG</label>
                    <select id="en_ag" name="en_ag" required>
                        <option value="oui">Oui</option>
                        <option value="non">Non</option>
                    </select>
                </div>

                <!-- Type de branchement -->
                <div class="input-group">
                    <label for="branchement">Type de branchement</label><br>
                    <input type="radio" id="branchement_simple" name="branchement" value="simple">
                    <label for="branchement_simple">Branchement simple</label><br>
                    <input type="radio" id="depart_direct" name="branchement" value="departdirect">
                    <label for="depart_direct">Départ direct</label><br>
                    <input type="radio" id="pose_de_poste" name="branchement" value="posedeposte">
                    <label for="pose_de_poste">Pose de poste</label>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn">Soumettre</button>
            </form>
        </div>
    </div>

</body>
</html>

