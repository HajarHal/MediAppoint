<?php
// Récupérer les données du formulaire
$deb_vac = $_POST['Debut_vac'];
$fin_vac = $_POST['Fin_vac'];
$nom = $_POST['Nom_med'];
$specialite = $_POST['specialite'];

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
$requete = "INSERT INTO planning ( Fin_vacance, Debut_vacance, Nom_medecin, specialite) VALUES ('$deb_vac', '$fin_vac', '$nom', '$specialite')";

if ($connexion->query($requete) === TRUE) {
    header("location: acc1.html");
    exit;
} else {
    echo "Erreur lors de l'ajout des informations : " . $connexion->error;
}

// Fermer la connexion à la base de données
$connexion->close();
?>
