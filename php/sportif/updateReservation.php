<?php
require_once "../../classeses/disponobilite.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

$id_reservation = $_POST['id_reservation'];
$date = $_POST['date_seance'];
$heure_debut = $_POST['heure_debut'];
$heure_fin = $_POST['heure_fin'];
$id_disponibilite = Disponibilite::getDisponibiliteByIdReservation($id_reservation)["id_disponibilite"];
Reservation::UpdateResevation($id_disponibilite,$date,$heure_debut,$heure_fin);

echo json_encode(["success" => true]);
