document.addEventListener('DOMContentLoaded', function () {
    loadUsers(); // Charger les utilisateurs au démarrage
});

// Charger les utilisateurs depuis le fichier JSON
function loadUsers() {
    fetch('users.json')
        .then(response => response.json())
        .then(users => {
            const userListContainer = document.getElementById('user-list-container');
            userListContainer.innerHTML = ''; // Effacer l'ancien contenu

            users.forEach(user => {
                const userDiv = document.createElement('div');
                userDiv.classList.add('user-item');
                userDiv.innerHTML = `
                    <p><strong>Nom d'utilisateur:</strong> ${user.username}</p>
                    <button class="btn delete-btn" onclick="deleteUser('${user.username}')">Supprimer</button>
                `;
                userListContainer.appendChild(userDiv);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des utilisateurs:', error);
        });
}

// Fonction pour supprimer un utilisateur
function deleteUser(username) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        fetch(`user_management.php?delete_username=${username}`, { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('error-message').textContent = "Utilisateur supprimé avec succès!";
                    loadUsers(); // Recharger la liste des utilisateurs après suppression
                } else {
                    document.getElementById('error-message').textContent = "Erreur lors de la suppression de l'utilisateur.";
                }
            })
            .catch(error => {
                console.error("Erreur:", error);
                document.getElementById('error-message').textContent = "Erreur lors de la suppression de l'utilisateur.";
            });
    }
}

// Fonction de soumission du formulaire de création
function submitCreateForm(event) {
    event.preventDefault(); // Empêcher la soumission normale du formulaire

    var username = document.getElementById('new_username').value;
    var password = document.getElementById('new_password').value;
    var confirmPassword = document.getElementById('confirm_password').value;

    // Vérification des champs vides
    if (username === "" || password === "" || confirmPassword === "") {
        document.getElementById('error-message').textContent = "Veuillez remplir tous les champs.";
        return;
    }

    // Vérification des mots de passe
    if (password !== confirmPassword) {
        document.getElementById('error-message').textContent = "Les mots de passe ne correspondent pas.";
        return;
    }

    // Envoi du formulaire de création via AJAX
    var formData = new FormData();
    formData.append("new_username", username);
    formData.append("new_password", password);

    fetch("user_management.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('error-message').textContent = "Utilisateur créé avec succès!";
            loadUsers(); // Recharger la liste des utilisateurs après création
        } else {
            document.getElementById('error-message').textContent = "Erreur lors de la création de l'utilisateur.";
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'AJAX:', error);
        document.getElementById('error-message').textContent = "Erreur lors de la création de l'utilisateur.";
    });
}

// Fonction de soumission du formulaire de suppression
function submitDeleteForm(event) {
    event.preventDefault(); // Empêcher la soumission normale du formulaire

    var username = document.getElementById('delete_username').value;

    if (username === "") {
        document.getElementById('error-message').textContent = "Veuillez entrer un nom d'utilisateur à supprimer.";
        return;
    }

    // Envoi du formulaire de suppression via AJAX
    var formData = new FormData();
    formData.append("delete_username", username);

    fetch("user_management.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('error-message').textContent = "Utilisateur supprimé avec succès!";
            loadUsers(); // Recharger la liste des utilisateurs après suppression
        } else {
            document.getElementById('error-message').textContent = "Erreur lors de la suppression de l'utilisateur.";
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'AJAX:', error);
        document.getElementById('error-message').textContent = "Erreur lors de la suppression de l'utilisateur.";
    });
}

// Fonction pour basculer entre les formulaires de création et de suppression (si besoin)
function toggleForms() {
    const createForm = document.getElementById('createFormContainer');
    const deleteForm = document.getElementById('deleteFormContainer');
    
    if (createForm.style.display === 'none') {
        createForm.style.display = 'block';
        deleteForm.style.display = 'none';
    } else {
        createForm.style.display = 'none';
        deleteForm.style.display = 'block';
    }
}
