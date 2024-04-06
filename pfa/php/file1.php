<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: conne.html");
    exit;
}

// Connexion à la base de données (remplacez les valeurs par celles de votre configuration)
try {
    $connexion = new PDO("mysql:host=localhost;dbname=gestion_de_rendez_vous", "root", "");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT Prenom_pat, Nom_pat, adress, DATE, Telephone_pat, email FROM patient WHERE email = :email";
    $requete = $connexion->prepare($sql);
    $requete->bindParam(':email', $_SESSION['email']);
    $requete->execute();

    // Vérifier si des données ont été trouvées
    if ($requete->rowCount() > 0) {
        // Créer un tableau associatif avec les informations de l'utilisateur
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
        $prenom = $utilisateur['Prenom_pat'];
        $nom = $utilisateur['Nom_pat'];

        // Requête SQL pour récupérer les informations de rendez-vous de l'utilisateur
        $sqlRv = "SELECT * FROM rv WHERE Nom_patient = :nom AND Prenom_patient = :prenom";
        $requeteRv = $connexion->prepare($sqlRv);
        $requeteRv->bindParam(':nom', $nom);
        $requeteRv->bindParam(':prenom', $prenom);
        $requeteRv->execute();

        // Vérifier si des données de rendez-vous ont été trouvées
        if ($requeteRv->rowCount() > 0) {
            // Créer un tableau associatif avec les informations de rendez-vous
            $rdv = $requeteRv->fetch(PDO::FETCH_ASSOC);
            $dateRdv = $rdv['Date_rv'];
            $heureRdv = $rdv['Heure_rv'];
            $medecinRdv = $rdv['Nom_medecin'];
            $specialite = $rdv['specialite'];
        } else {
            $dateRdv = "";
            $heureRdv = "";
            $medecinRdv = "";
            $specialite ="";
        }

        ?>
        <html>
        <head>
            <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>MediAppoint</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Baloo+Chettan|Poppins:400,600,700&display=swap" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="conne.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
  <!--<link rel="stylesheet" href="acc.css" />-->
  <link rel="stylesheet" href="file.css" />
        </head>
        <body>
        <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              MediAppoint
            </span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex mx-auto flex-column flex-lg-row align-items-center">
              <ul class="navbar-nav  ">
                <li class="nav-item ">
                  <a class="nav-link" href="acc.html"> Home </a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="file1.php"> File <span class="sr-only">(current)</span> </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="appoi.html">Take appointement</a>
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
    <!-- end header section -->

            <h2>Bienvenue, <?= $prenom ?> <?= $nom ?>!</h2>
            <table class="table-fill">
                <thead>
                    <tr>
                        <th class="text-left">Informations</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                    <tr>
                        <td class="text-left">First Name</td>
                        <td class="text-left"><?= $utilisateur['Prenom_pat'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Last Name</td>
                        <td class="text-left"><?= $utilisateur['Nom_pat'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Adress</td>
                        <td class="text-left"><?= $utilisateur['adress'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Birth Date</td>
                        <td class="text-left"><?= $utilisateur['DATE'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Phone Number</td>
                        <td class="text-left"><?= $utilisateur['Telephone_pat'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Appointement date</td>
                        <td class="text-left"><?= $dateRdv ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Appointement time</td>
                        <td class="text-left"><?= $heureRdv ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">Doctor</td>
                        <td class="text-left"><?= $medecinRdv ?></td>
                    </tr>
                    <tr>
                        <td class="text-left"> Doctor speciality </td>
                        <td class="text-left"><?= $specialite ?></td>
                    </tr>
                    <tr>
                    <<td class="text-left">
    <form method="POST" action="annuler-rendezvous.php" onsubmit="return confirmCancellation(event)">
        <input type="hidden" name="rdv_id" value="<?= $rdv['Numero_rv'] ?>">
        <button type="submit" class="btn btn-danger" >Annuler</button>
    </form>
</td>

<script>
    function confirmCancellation(event) {
        var confirmationMessage = "Voulez-vous vraiment annuler ce rendez-vous ?"; 

        var result = confirm(confirmationMessage);

        if (!result) {
            event.preventDefault(); 
        }

        return result;
    }
</script>

                    </tr>
                </tbody>
            </table>

            <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "Aucune information trouvée pour les coordonnées fournies.";
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

// Fermer la connexion à la base de données
$connexion = null;
?>
