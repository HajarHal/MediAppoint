<?php
// Récupérer les données du formulaire
$prenom = $_POST['Prenom_med'];
$nom = $_POST['Nom_med'];
$specialite = $_POST['specialite'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];

// Se connecter à la base de données (modifier les paramètres selon votre configuration)
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe_bdd = "";
$nom_base_de_donnees = "gestion_de_rendez_vous";

$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe_bdd, $nom_base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}

// Préparer et exécuter la requête d'insertion
$requete = "INSERT INTO medecin (Prenom_med, Nom_med, specialite, med_email, mot_de_passe_med) VALUES ('$prenom', '$nom', '$specialite', '$email', '$mot_de_passe')";

if ($connexion->query($requete) === TRUE) {
    header("location: acc1.html");
    exit;
} else {
    echo "Erreur lors de l'ajout des informations : " . $connexion->error;
}

// Fermer la connexion à la base de données
$connexion->close();
?>
