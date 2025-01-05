function validateForm() {
    // Récupérer les valeurs des champs
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Vérifier si les champs sont vides
    if (username === "" || password === "") {
        document.getElementById('error-message').textContent = "Veuillez remplir tous les champs.";
        return false;
    }

    // Vérifier les informations avec les utilisateurs enregistrés
    fetch('users.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement des utilisateurs.');
            }
            return response.json();
        })
        .then(users => {
            const user = users.find(user => user.username === username);

            if (user) {
                // Vérifier si le mot de passe est correct
                fetch('verify_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ password, hashedPassword: user.password })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            alert('Connexion réussie !');
                            window.location.href = 'tableau_de_bord.html'; // Rediriger après connexion réussie
                        } else {
                            document.getElementById('error-message').textContent = "Mot de passe incorrect.";
                        }
                    });
            } else {
                document.getElementById('error-message').textContent = "Utilisateur introuvable.";
            }
        })
        .catch(error => {
            document.getElementById('error-message').textContent = "Erreur de connexion.";
            console.error(error);
        });

    return false; // Empêche l'envoi du formulaire pour le traitement asynchrone
}
