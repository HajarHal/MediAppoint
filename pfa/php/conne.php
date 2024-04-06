<?php
session_start(); // Démarrer la session

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si les identifiants correspondent à ceux d'un médecin
        $sql = "SELECT * FROM medecin WHERE med_email = :email AND mot_de_passe_med = :mot_de_passe";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Enregistrement de l'email en session
            $_SESSION['email'] = $email;

            // Redirection vers la page médecin
            header("Location: medecin.php");
            exit;
        }

        // Vérifier si les identifiants correspondent à ceux d'un patient
        $sql = "SELECT * FROM patient WHERE email = :email AND mot_de_passe = :mot_de_passe";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Enregistrement de l'email en session
            $_SESSION['email'] = $email;

            // Redirection vers la page d'accueil
            header("Location: acc.html");
            exit;
        }
        $sql = "SELECT * FROM admin WHERE admin_email = :email AND mot_de_passe_admin = :mot_de_passe";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Enregistrement de l'email en session
            $_SESSION['email'] = $email;

            // Redirection vers la page d'accueil
            header("Location: acc1.html");
            exit;

        // Aucun utilisateur avec les identifiants fournis
        echo "Invalid email or password.";
    }
 }   catch(PDOException $e) {
        die("Error: Could not connect to the database. " . $e->getMessage());
    }
}
?>
