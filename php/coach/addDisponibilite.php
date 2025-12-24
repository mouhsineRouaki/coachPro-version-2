<?php
session_start();
require "../../classeses/coach.php";
require "../../classeses/disponobilite.php";
$id_coach = Coach::getConnectedCoach()["id_coach"];

$date = $_POST['date'];
$start = $_POST['startTime'];
$end = $_POST['endTime'];


if (!$date || !$start || !$end) {
    echo json_encode([
        'success' => false,
        'message' => 'Donnees manquantes'
    ]);
    exit;
}
if ($start < "08:00" || $end > "18:00" || $start >= $end) {
    echo json_encode([
        'success' => false,
        'message' => 'Heures hors du temps de travail (08:00 - 18:00)'
    ]);
    exit;
}
$disponibilite = new Disponibilite(null , $id_coach , $date , $start , $end);
echo json_encode($disponibilite->ajouterDisponibilite());



