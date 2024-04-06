<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_de_rendez_vous";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérification de la soumission du formulaire
if(isset($_POST['submit'])){
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $newPassword = $_POST['mot_de_passe'];

    // Vérification si l'adresse e-mail existe dans la table "patient"
    $checkEmailQuery = "SELECT * FROM patient WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if($result->num_rows > 0){
        // Mise à jour du mot de passe dans la table "patient"
        $updatePasswordQuery = "UPDATE patient SET mot_de_passe = '$newPassword' WHERE email = '$email'";
        if ($conn->query($updatePasswordQuery) === TRUE) {
            header("location: conne.html");
            exit;
        } else {
            echo "Une erreur s'est produite lors de la mise à jour du mot de passe : " . $conn->error;
        }
    } else {
        echo "Adresse e-mail introuvable dans la base de données.";
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
