<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: conne.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID du rendez-vous à annuler depuis le formulaire
    $rdv_id = $_POST['rdv_id'];

    // Connexion à la base de données (remplacez les valeurs par celles de votre configuration)
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous", "root", "");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprimer le rendez-vous de la table "rv" en utilisant l'ID
        $sql_delete = "DELETE FROM rv WHERE Numero_rv = :rdv_id";
        $requete_delete = $connexion->prepare($sql_delete);
        $requete_delete->bindParam(':rdv_id', $rdv_id);
        $requete_delete->execute();

        // Rediriger l'utilisateur vers la page des informations du patient
        header("Location: file1.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Fermer la connexion à la base de données
    $connexion = null;
}
?>
