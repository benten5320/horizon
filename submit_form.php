<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$json_file = 'formulaires.json'; // Fichier JSON pour stocker les formulaires

// Fonction pour charger les données du fichier JSON
function load_formulaires($filename)
{
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true) ?: [];
    }
    return [];
}

// Fonction pour sauvegarder les données dans le fichier JSON
function save_formulaires($filename, $formulaires)
{
    return file_put_contents($filename, json_encode($formulaires, JSON_PRETTY_PRINT)) !== false;
}

// Charger les formulaires existants
$formulaires = load_formulaires($json_file);

// Vérifier si un nouveau formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom_dossier'])) {

    // Validation des champs obligatoires
    if (empty($_POST['nom_dossier']) || empty($_POST['numero_affaire']) || empty($_POST['adresse']) || empty($_POST['telephone']) || empty($_POST['email'])) {
        echo "<p style='color: red;'>Tous les champs obligatoires doivent être remplis.</p>";
    } else {
        // Récupérer et assainir les données du formulaire
        $nouveau_formulaire = [
            'nom_dossier' => htmlspecialchars($_POST['nom_dossier']),
            'numero_affaire' => htmlspecialchars($_POST['numero_affaire']),
            'adresse' => htmlspecialchars($_POST['adresse']),
            'telephone' => htmlspecialchars($_POST['telephone']),
            'email' => htmlspecialchars($_POST['email']),
            'puissance_projet' => htmlspecialchars($_POST['puissance_projet']),
            'irve' => $_POST['irve'],
            'ag' => $_POST['ag'],
            'fr_ag' => $_POST['fr_ag'],
            'branchement' => $_POST['branchement'],
            'date_creation' => date('Ymd H:i:s'),
            'utilisateur' => $_SESSION['username'],
            'nombre_place_exterieur' => $_POST['nombre_place_exterieur'] ?? null,
            'nombre_place_interieur' => $_POST['nombre_place_interieur'] ?? null,
            'date_prime_advenir_demande' => $_POST['date_prime_advenir_demande'] ?? null,
            'date_prime_advenir_fin' => $_POST['date_prime_advenir_fin'] ?? null,
            'date_prime_advenir_8mois' => $_POST['date_prime_advenir_8mois'] ?? null,
            'presence_en_ag' => $_POST['presence_en_ag'] ?? null,
            'delais_envoi_convention' => $_POST['delais_envoi_convention'] ?? null,
            'convention_validite' => $_POST['convention_validite'] ?? null,
            'convention_envoye_le' => $_POST['convention_envoye_le'] ?? null,
            'convention_signe_le' => $_POST['convention_signe_le'] ?? null,
            // Nouveaux champs ajoutés
            'code_postal' => $_POST['code_postal'] ?? null,
            'recu_le' => $_POST['recu_le'] ?? null,
            'visite_le' => $_POST['visite_le'] ?? null,
            'etude_valide_le' => $_POST['etude_valide_le'] ?? null,
            'DST_envoye_le' => $_POST['DST_envoye_le'] ?? null,
            'DST_valide_le' => $_POST['DST_valide_le'] ?? null,
            'ORR_envoye_le' => $_POST['ORR_envoye_le'] ?? null,
            'ORR_signe_le' => $_POST['ORR_signe_le'] ?? null,
            'devis_3mois_le' => $_POST['devis_3mois_le'] ?? null,
        ];

        // Ajouter le formulaire aux données existantes
        $formulaires[] = $nouveau_formulaire;

        // Sauvegarder les formulaires dans le fichier JSON
        if (save_formulaires($json_file, $formulaires)) {
            // Redirection après soumission pour éviter la ré-exécution
            header('Location: submit_form.php');
            exit();
        } else {
            echo "<p style='color: red;'>Erreur lors de l'enregistrement du formulaire. Veuillez réessayer.</p>";
        }
    }
}

// Supprimer un formulaire si demandé
if (isset($_POST['delete_index'])) {
    $index = intval($_POST['delete_index']);
    if (isset($formulaires[$index]) && $formulaires[$index]['utilisateur'] === $_SESSION['username']) {
        unset($formulaires[$index]); // Supprimer le formulaire
        $formulaires = array_values($formulaires); // Réindexer les clés
        if (save_formulaires($json_file, $formulaires)) {
            header('Location: submit_form.php');
            exit();
        } else {
            echo "<p style='color: red;'>Erreur lors de la suppression du formulaire. Veuillez réessayer.</p>";
        }
    }
}

// Sauvegarder les modifications après mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_index'])) {
    $index = intval($_POST['update_index']);
    if (isset($formulaires[$index]) && $formulaires[$index]['utilisateur'] === $_SESSION['username']) {
        // Mettre à jour les données du formulaire
        $formulaires[$index]['irve'] = $_POST['irve'];
        $formulaires[$index]['ag'] = $_POST['ag'];
        $formulaires[$index]['nombre_place_exterieur'] = $_POST['nombre_place_exterieur'] ?? null;
        $formulaires[$index]['nombre_place_interieur'] = $_POST['nombre_place_interieur'] ?? null;
        $formulaires[$index]['date_prime_advenir_demande'] = $_POST['date_prime_advenir_demande'] ?? null;
        $formulaires[$index]['date_prime_advenir_fin'] = $_POST['date_prime_advenir_fin'] ?? null;
        $formulaires[$index]['date_prime_advenir_8mois'] = $_POST['date_prime_advenir_8mois'] ?? null;
        $formulaires[$index]['presence_en_ag'] = $_POST['presence_en_ag'] ?? null;
        $formulaires[$index]['delais_envoi_convention'] = $_POST['delais_envoi_convention'] ?? null;
        $formulaires[$index]['convention_validite'] = $_POST['convention_validite'] ?? null;
        $formulaires[$index]['convention_envoye_le'] = $_POST['convention_envoye_le'] ?? null;
        $formulaires[$index]['convention_signe_le'] = $_POST['convention_signe_le'] ?? null;
        // Nouveaux champs ajoutés
        $formulaires[$index]['code_postal'] = $_POST['code_postal'] ?? null;
        $formulaires[$index]['recu_le'] = $_POST['recu_le'] ?? null;
        $formulaires[$index]['visite_le'] = $_POST['visite_le'] ?? null;
        $formulaires[$index]['etude_valide_le'] = $_POST['etude_valide_le'] ?? null;
        $formulaires[$index]['DST_envoye_le'] = $_POST['DST_envoye_le'] ?? null;
        $formulaires[$index]['DST_valide_le'] = $_POST['DST_valide_le'] ?? null;
        $formulaires[$index]['ORR_envoye_le'] = $_POST['ORR_envoye_le'] ?? null;
        $formulaires[$index]['ORR_signe_le'] = $_POST['ORR_signe_le'] ?? null;
        $formulaires[$index]['devis_3mois_le'] = $_POST['devis_3mois_le'] ?? null;

        // Sauvegarder les formulaires mis à jour
        if (save_formulaires($json_file, $formulaires)) {
            header('Location: submit_form.php');
            exit();
        } else {
            echo "<p style='color: red;'>Erreur lors de la mise à jour du formulaire. Veuillez réessayer.</p>";
        }
    }
}

// Filtrer les formulaires pour l'utilisateur connecté
$formulaires_utilisateur = array_filter($formulaires, function ($formulaire) {
    return $formulaire['utilisateur'] === $_SESSION['username'];
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Enregistrés</title>
    <link rel="stylesheet" href="tableau_de_bord.css">
    <style>
        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            margin: 20px auto;
            width: 90%;
            max-width: 1200px;
        }

        .form-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            flex: 1 1 calc(25% - 20px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
            max-width: calc(25% - 20px);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .form-card:hover {
            transform: scale(1.05);
        }

        .form-card h3 {
            font-size: 18px;
            margin: 0 0 10px 0;
            color: #007bff;
        }

        .form-card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .form-card form {
            margin-top: 10px;
        }

        .form-card button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-card button:hover {
            background-color: #e60000;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
        }

        .modal.active {
            display: block;
        }

        .modal .close-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal .close-btn:hover {
            background-color: #e60000;
        }

        .modal input[type="text"], .modal input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header-banner {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }

        .back-btn {
        position: absolute;
        top: 10px;
        left: 20px;
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
    }

    .back-btn:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
<div class="header-banner">
    <span>Mes Formulaires</span>
</div>

<div class="header-banner">
    <span>Mes Formulaires</span>
    <!-- Ajout du bouton de retour au tableau de bord -->
    <a href="index.php" class="back-btn">Retour au Tableau de Bord</a>
</div>

<div class="form-container">
    <?php if (count($formulaires_utilisateur) > 0): ?>
        <?php foreach ($formulaires_utilisateur as $index => $formulaire): ?>
            <div class="form-card" onclick="openModal(<?php echo $index; ?>)">
                <h3><?php echo htmlspecialchars($formulaire['nom_dossier']); ?></h3>
                <p><strong>Numéro d'affaire :</strong> <?php echo htmlspecialchars($formulaire['numero_affaire']); ?></p>
                <p><strong>Adresse :</strong> <?php echo htmlspecialchars($formulaire['adresse']); ?></p>
                <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($formulaire['telephone']); ?></p>
                <p><strong>E-mail :</strong> <?php echo htmlspecialchars($formulaire['email']); ?></p>
                <p><strong>Puissance Projet :</strong> <?php echo htmlspecialchars($formulaire['puissance_projet']); ?> kW</p>
                <p><strong>IRVE :</strong> <?php echo htmlspecialchars($formulaire['irve']); ?></p>
                <p><strong>En AG :</strong> <?php echo htmlspecialchars($formulaire['ag']); ?></p>
                <p><strong>Branchement :</strong> <?php echo htmlspecialchars($formulaire['branchement']); ?></p>
                <p><small><strong>Date :</strong> <?php echo htmlspecialchars($formulaire['date_creation']); ?></small></p>
                <form method="POST">
                    <input type="hidden" name="delete_index" value="<?php echo $index; ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun formulaire enregistré.</p>
    <?php endif; ?>
</div>

<!-- Modal pour afficher et modifier les informations -->
<div class="modal" id="modal">
    <h3>Modifier les informations</h3>
    <form method="POST" id="form-modal">
        <input type="hidden" name="update_index" id="update_index">
        
        <!-- Tableau pour IRVE -->
        <table>
            <thead>
                <tr>
                    <th colspan="2">IRVE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="irve">IRVE:</label></td>
                    <td>
                        <select id="irve" name="irve" onchange="toggleAdditionalFields()">
                            <option value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>
                    </td>
                </tr>
                <tr id="additional-fields" style="display: none;">
                    <td><label for="nombre_place_exterieur">Nombre de places extérieur:</label></td>
                    <td><input type="text" id="nombre_place_exterieur" name="nombre_place_exterieur"></td>
                </tr>
                <tr id="additional-fields" style="display: none;">
                    <td><label for="nombre_place_interieur">Nombre de places intérieur:</label></td>
                    <td><input type="text" id="nombre_place_interieur" name="nombre_place_interieur"></td>
                </tr>
                <tr id="additional-fields" style="display: none;">
                    <td><label for="date_prime_advenir_demande">Date prime avenir demande:</label></td>
                    <td><input type="date" id="date_prime_advenir_demande" name="date_prime_advenir_demande"></td>
                </tr>
                <tr id="additional-fields" style="display: none;">
                    <td><label for="date_prime_advenir_fin">Date prime avenir fin:</label></td>
                    <td><input type="date" id="date_prime_advenir_fin" name="date_prime_advenir_fin"></td>
                </tr>
                <tr id="additional-fields" style="display: none;">
                    <td><label for="date_prime_advenir_8mois">Date prime avenir 8 mois:</label></td>
                    <td><input type="date" id="date_prime_advenir_8mois" name="date_prime_advenir_8mois"></td>
                </tr>
            </tbody>
        </table>

        <!-- Tableau pour AG -->
        <table>
            <thead>
                <tr>
                    <th colspan="2">AG</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="ag">Présence en AG:</label></td>
                    <td>
                        <select id="ag" name="ag" onchange="toggleAGFields()">
                            <option value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>
                    </td>
                </tr>
                <tr id="ag-details" style="display: none;">
                    <td><label for="presence_en_ag">Présence en AG:</label></td>
                    <td><input type="text" id="presence_en_ag" name="presence_en_ag"></td>
                </tr>
                <tr id="ag-details" style="display: none;">
                    <td><label for="delais_envoi_convention">Délai envoi convention:</label></td>
                    <td><input type="text" id="delais_envoi_convention" name="delais_envoi_convention"></td>
                </tr>
                <tr id="ag-details" style="display: none;">
                    <td><label for="convention_validite">Validité de la convention:</label></td>
                    <td><input type="text" id="convention_validite" name="convention_validite"></td>
                </tr>
                <tr id="ag-details" style="display: none;">
                    <td><label for="convention_envoye_le">Convention envoyée le:</label></td>
                    <td><input type="date" id="convention_envoye_le" name="convention_envoye_le"></td>
                </tr>
                <tr id="ag-details" style="display: none;">
                    <td><label for="convention_signe_le">Convention signée le:</label></td>
                    <td><input type="date" id="convention_signe_le" name="convention_signe_le"></td>
                </tr>
            </tbody>
        </table>

        <!-- Tableau pour les nouveaux champs -->
        <table>
            <thead>
                <tr>
                    <th colspan="2">Informations Complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="code_postal">Code postal:</label></td>
                    <td><input type="text" id="code_postal" name="code_postal"></td>
                </tr>
                <tr>
                    <td><label for="recu_le">Reçu le:</label></td>
                    <td><input type="date" id="recu_le" name="recu_le"></td>
                </tr>
                <tr>
                    <td><label for="visite_le">Visite le:</label></td>
                    <td><input type="date" id="visite_le" name="visite_le"></td>
                </tr>
                <tr>
                    <td><label for="etude_valide_le">Étude validée le:</label></td>
                    <td><input type="date" id="etude_valide_le" name="etude_valide_le"></td>
                </tr>
                <tr>
                    <td><label for="DST_envoye_le">DST envoyé le:</label></td>
                    <td><input type="date" id="DST_envoye_le" name="DST_envoye_le"></td>
                </tr>
                <tr>
                    <td><label for="DST_valide_le">DST validé le:</label></td>
                    <td><input type="date" id="DST_valide_le" name="DST_valide_le"></td>
                </tr>
                <tr>
                    <td><label for="ORR_envoye_le">ORR envoyé le:</label></td>
                    <td><input type="date" id="ORR_envoye_le" name="ORR_envoye_le"></td>
                </tr>
                <tr>
                    <td><label for="ORR_signe_le">ORR signé le:</label></td>
                    <td><input type="date" id="ORR_signe_le" name="ORR_signe_le"></td>
                </tr>
                <tr>
                    <td><label for="devis_3mois_le">Devis 3 mois le:</label></td>
                    <td><input type="date" id="devis_3mois_le" name="devis_3mois_le"></td>
                </tr>
            </tbody>
        </table>

        <button type="submit">Sauvegarder</button>
    </form>
    <button class="close-btn" onclick="closeModal()">Fermer</button>
</div>

<script>
    function openModal(index) {
        document.getElementById('modal').classList.add('active');
        document.getElementById('update_index').value = index;
        // Populate the form fields with existing values
        var formulaire = <?php echo json_encode($formulaires_utilisateur); ?>[index];
        document.getElementById('irve').value = formulaire.irve;
        document.getElementById('ag').value = formulaire.ag;
        document.getElementById('nombre_place_exterieur').value = formulaire.nombre_place_exterieur || '';
        document.getElementById('nombre_place_interieur').value = formulaire.nombre_place_interieur || '';
        document.getElementById('date_prime_advenir_demande').value = formulaire.date_prime_advenir_demande || '';
        document.getElementById('date_prime_advenir_fin').value = formulaire.date_prime_advenir_fin || '';
        document.getElementById('date_prime_advenir_8mois').value = formulaire.date_prime_advenir_8mois || '';
        document.getElementById('presence_en_ag').value = formulaire.presence_en_ag || '';
        document.getElementById('delais_envoi_convention').value = formulaire.delais_envoi_convention || '';
        document.getElementById('convention_validite').value = formulaire.convention_validite || '';
        document.getElementById('convention_envoye_le').value = formulaire.convention_envoye_le || '';
        document.getElementById('convention_signe_le').value = formulaire.convention_signe_le || '';
        document.getElementById('code_postal').value = formulaire.code_postal || '';
        document.getElementById('recu_le').value = formulaire.recu_le || '';
        document.getElementById('visite_le').value = formulaire.visite_le || '';
        document.getElementById('etude_valide_le').value = formulaire.etude_valide_le || '';
        document.getElementById('DST_envoye_le').value = formulaire.DST_envoye_le || '';
        document.getElementById('DST_valide_le').value = formulaire.DST_valide_le || '';
        document.getElementById('ORR_envoye_le').value = formulaire.ORR_envoye_le || '';
        document.getElementById('ORR_signe_le').value = formulaire.ORR_signe_le || '';
        document.getElementById('devis_3mois_le').value = formulaire.devis_3mois_le || '';
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
    }

    function toggleAdditionalFields() {
        var irveSelect = document.getElementById('irve');
        var additionalFields = document.querySelectorAll('#additional-fields');
        if (irveSelect.value === 'oui') {
            additionalFields.forEach(function(field) {
                field.style.display = 'table-row';
            });
        } else {
            additionalFields.forEach(function(field) {
                field.style.display = 'none';
            });
        }
    }

    function toggleAGFields() {
        var agSelect = document.getElementById('ag');
        var agDetails = document.querySelectorAll('#ag-details');
        if (agSelect.value === 'oui') {
            agDetails.forEach(function(detail) {
                detail.style.display = 'table-row';
            });
        } else {
            agDetails.forEach(function(detail) {
                detail.style.display = 'none';
            });
        }
    }
</script>

</body>
</html>