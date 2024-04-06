<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: conne.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rdv_date = $_POST['date_rv'];
    $rdv_heure = $_POST['heure_rv'];
    // Connexion à la base de données (remplacez les valeurs par celles de votre configuration)
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL pour supprimer le rendez-vous de la table "rv" pour une date et une heure données
        $sql = "DELETE FROM rv WHERE Date_rv = :date_rv AND Heure_rv = :heure_rv";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date_rv', $rdv_date);
        $stmt->bindParam(':heure_rv', $rdv_heure);
        $stmt->execute();

        header("Location: medecin.php"); // Redirection vers la page du médecin
        exit;
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    echo "Une erreur s'est produite lors de l'annulation du rendez-vous.";
}
?>
