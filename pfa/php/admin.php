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
    $sql = "SELECT DISTINCT rv.Date_rv, rv.Heure_rv, rv.Nom_patient, rv.Prenom_patient, patient.Telephone_pat, Nom_medecin, specialite
    FROM rv
    JOIN patient ON rv.Nom_patient = patient.Nom_pat AND rv.Prenom_patient=patient.Prenom_pat 
    ORDER BY rv.Date_rv ASC , rv.Heure_rv ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Afficher les résultats de la requête
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
                  <a class="nav-link" href="acc1.html"> Home </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="ajout.html"> Add doctor </a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="admin.php"> appointements <span class="sr-only">(current)</span> </a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="planing.html"> planning</a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="planing1.php"> vacation</a>
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
        <table class="table-fill">
            <head>
                <tr>
                    <th class="text-left">Date</th>
                    <th class="text-left">Time</th>
                    <th class="text-left">patient Last name</th>
                    <th class="text-left">patient first name</th>
                    <th class="text-left">Phone number</th>
                    <th class="text-left">Doctor name</th>
                    <th class="text-left">speciality</th>
                </tr>
            </head>
            <tbody class="table-hover">
        <?php
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Accéder aux colonnes par leurs noms
            $date_rv = $row['Date_rv'];
            $heure_rv = $row['Heure_rv'];
            $nom_patient = $row['Nom_patient'];
            $telephone = $row['Telephone_pat'];
            $prenom_patient = $row['Prenom_patient'];
            $nom_medecin = $row['Nom_medecin'];
            $specialite = $row['specialite'];
        ?>
                <tr>
                    <td class="text-left"><?= $date_rv ?></td>
                    <td class="text-left"><?= $heure_rv ?></td>
                    <td class="text-left"><?= $nom_patient ?></td>
                    <td class="text-left"><?= $prenom_patient ?></td>
                    <td class="text-left"><?= $telephone ?></td>
                    <td class="text-left"><?= $nom_medecin ?></td>
                    <td class="text-left"><?= $specialite?></td>
                </tr>
        <?php
        }
        ?>
            </tbody>
        </table>
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
