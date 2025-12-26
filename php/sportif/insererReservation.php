<?php
require_once "../../classeses/disponobilite.php";
require_once "../../classeses/reservation.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_coach   = $_POST['id_coach'];
    $id_sport   = $_POST['id_sport'];
    $id_sportif = $_POST['id_sportif'];
    $date       = $_POST['date_seance'];
    $creneaux   = $_POST['creneaux'] ?? [];

    if (empty($creneaux)) {
        die("Aucun créneau sélectionné.");
    }



    foreach ($creneaux as $c) {

        list($id_dispo, $heure_debut, $heure_fin) = explode('-', $c);
        Disponibilite::reserverDisponibiliteByid($id_dispo);
        Reservation::insererReservation($id_coach,$id_sportif,$id_dispo,$id_sport,"en_attente");
    }

    header("Location: ../../pages/sportif/detailsCoach.php?id_coach="."$id_coach");
}
?>
