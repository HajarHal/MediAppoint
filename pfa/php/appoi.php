<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_de_rendez_vous";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }
} catch (mysqli_sql_exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}

// Récupération des données du formulaire
$date = isset($_POST["date"]) ? $_POST["date"] : "";
$heure = isset($_POST["heure"]) ? $_POST["heure"] : "";
$specialite = isset($_POST["specialite"]) ? $_POST["specialite"] : "";
$prenom = isset($_POST["Prenom_pat"]) ? $_POST["Prenom_pat"] : "";
$nom = isset($_POST["Nom_pat"]) ? $_POST["Nom_pat"] : "";

if (!empty($date) && !empty($heure) && !empty($specialite)) {
    // Vérification de l'existence du rendez-vous dans la table rv
    if (!isAppointmentExist($conn, $date, $heure)) {
        // Vérification de la date
        $currentDate = date("Y-m-d");
        if ($date != $currentDate) {
            // Vérification de l'heure et du médecin
            $specialite = $conn->real_escape_string($specialite);
            $medecin_query = "SELECT Nom_med FROM medecin WHERE specialite='$specialite'";
            $result = $conn->query($medecin_query);
            if ($result && $result->num_rows > 0) {
                $medecinData = $result->fetch_assoc();
                $medecin = trim($medecinData['Nom_med']);
                if (!isAppointmentExist($conn, $medecin, $heure)) {
                    // Vérification des jours de la semaine (samedi et dimanche)
                    $currentDayOfWeek = date("N", strtotime($date));
                    if ($currentDayOfWeek != 6 && $currentDayOfWeek != 7) {
                        // Récupération des dates de vacances
                        $medecin = $conn->real_escape_string($medecin);
                        $planning_query = "SELECT `Debut_vacance`, `Fin_vacance` FROM planning WHERE Nom_medecin='$medecin' AND '$date' BETWEEN `Debut_vacance` AND `Fin_vacance`";
                        $result_planning = $conn->query($planning_query);
                        if ($result_planning && $result_planning->num_rows > 0) {
                            echo "La date que vous avez choisie est pendant les vacances du Dr. " . $medecin;
                        } else {
                            // Vérification de la date par rapport aux vacances

                            // La plage horaire est disponible et toutes les conditions sont satisfaites
                            // Remplacez cette ligne par la récupération du nom du patient à partir du formulaire
                            $sql_rv = "INSERT INTO rv (Date_rv, Heure_rv,Nom_patient,Prenom_patient, Nom_medecin, specialite ) VALUES ('$date', '$heure', '$nom', '$prenom', '$medecin', '$specialite')";

                            if ($conn->query($sql_rv) === true) {
                                echo "Vous avez pris un rendez-vous avec le Dr. " . $medecin;
                                header("Location: file1.php");
                                exit();
                            } else {
                                echo "Erreur : " . $conn->error;
                                exit();
                            }
                        }
                    } else {
                        echo "Veuillez choisir une date autre que le samedi ou le dimanche.";
                    }
                } else {
                    echo "Un rendez-vous existe déjà pour cette heure avec le même médecin.";
                }
            } else {
                echo "Erreur : Aucun médecin correspondant à la spécialité trouvée.";
                exit();
            }
        } else {
            echo "Veuillez choisir une date différente de la date d'aujourd'hui.";
        }
    } else {
        echo "Un rendez-vous existe déjà pour cette date et cette heure.";
    }
} else {
    echo "Veuillez remplir tous les champs du formulaire.";
}

$conn->close();

function isAppointmentExist($conn, $date, $heure)
{
    $date = $conn->real_escape_string($date);
    $heure = $conn->real_escape_string($heure);
    $sql_appointment = "SELECT * FROM rv WHERE Date_rv='$date' AND Heure_rv='$heure'";
    $result_appointment = $conn->query($sql_appointment);
    return $result_appointment && $result_appointment->num_rows > 0;
}
?>
