<?php
session_start();

if(isset($_POST['submit'])) {
    $prenom = $_POST['Prenom_pat'];
    $nom = $_POST['Nom_pat'];
    $adresse = $_POST['adress'];
    $date_naissance = $_POST['DATE'];
    $telephone = $_POST['Telephone_pat'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO patient (Prenom_pat, Nom_pat, adress, DATE, Telephone_pat, email, mot_de_passe) 
                VALUES (:Prenom_pat, :Nom_pat, :adress, :DATE, :Telephone_pat, :email, :mot_de_passe)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':Prenom_pat', $prenom);
        $stmt->bindParam(':Nom_pat', $nom);
        $stmt->bindParam(':adress', $adresse);
        $stmt->bindParam(':DATE', $date_naissance);
        $stmt->bindParam(':Telephone_pat', $telephone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);

        $stmt->execute();

        // Ajouter l'email à la session
        $_SESSION['email'] = $email;

        header("Location: acc.html");
        exit;
    } catch(PDOException $e) {
        die("Error: Could not connect to the database. " . $e->getMessage());
    }
}
?>
