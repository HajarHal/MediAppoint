<?php
// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: conne.html");
    exit;
}

// Connexion à la base de données (remplacez les valeurs par celles de votre configuration)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les informations de la table "rv" et "patient" pour un "Nom_medecin" donné
    $sql = "SELECT DISTINCT rv.Date_rv, rv.Heure_rv, rv.Nom_patient, rv.Prenom_patient, patient.Telephone_pat, medecin.Nom_med, medecin.Prenom_med, rv.specialite
    FROM rv
    JOIN medecin ON rv.Nom_medecin = medecin.Nom_med
    JOIN patient ON rv.Nom_patient = patient.Nom_pat AND rv.Prenom_patient = patient.Prenom_pat 
    WHERE medecin.med_email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $nom_medecin = $prenom_medecin = "";
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            $nom_medecin = $rows[0]['Nom_med'];
            $prenom_medecin = $rows[0]['Prenom_med'];
        }

        // Afficher les résultats de la requête
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>MediAppoint</title>
            <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
            <link href="https://fonts.googleapis.com/css?family=Baloo+Chettan|Poppins:400,600,700&display=swap" rel="stylesheet">
            <link href="css/style.css" rel="stylesheet">
            <link href="file.css" rel="stylesheet">
        </head>
        <body>
            <div class="hero_area">
                <header class="header_section">
                    <div class="container">
                        <nav class="navbar navbar-expand-lg custom_nav-container">
                            <a class="navbar-brand" href="index.html">
                                <span>MediAppoint</span>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div class="d-flex mx-auto flex-column flex-lg-row align-items-center">
                                    <ul class="navbar-nav">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="medecin.php">Planing <span class="sr-only">(current)</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="logout.php">Log out</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                </header>
                <h1>Bienvenue <?php echo $nom_medecin . ' ' . $prenom_medecin; ?></h1>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient last name</th>
                            <th>Patient first name</th>
                            <th>Phone Number</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $row['Date_rv']; ?></td>
                            <td><?php echo $row['Heure_rv']; ?></td>
                            <td><?php echo $row['Nom_patient']; ?></td>
                            <td><?php echo $row['Prenom_patient']; ?></td>
                            <td><?php echo $row['Telephone_pat']; ?></td>
                            <td class="text-left">
    <form method="POST" action="annuler_rendezvous1.php" onsubmit="return confirmCancellation(event)">
        <input type="hidden" name="date_rv" value="<?= $row['Date_rv'] ?>">
        <input type="hidden" name="heure_rv" value="<?= $row['Heure_rv'] ?>">
        <button type="submit" class="btn btn-danger">Annuler</button>
    </form>
</td>

<script>
    function confirmCancellation(event) {
        var confirmationMessage = "Voulez-vous vraiment annuler ce rendez-vous ?"; 

        var result = confirm(confirmationMessage);

        if (!result) {
            event.preventDefault();

        return result;
    }
}
</script>


                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/owl.carousel.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        header("Location: aucun.html");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
