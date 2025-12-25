<?php
session_start();
require "../../classeses/coach.php";
require "../../classeses/disponobilite.php";
$id_coach = Coach::getConnectedCoach()["id_coach"];

$id    = $_POST['id'] ?? null;
$date  = $_POST['date'] ?? null;
$start = $_POST['startTime'] ?? null;
$end   = $_POST['endTime'] ?? null;


if (!$id || !$id_coach || !$date || !$start || !$end) {
    echo json_encode([
        'success' => false,
        'message' => 'Données manquantes'
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
$disponibiliteGet = Disponibilite::getDisponibiliteById($id);
$disponibilites = new Disponibilite($disponibiliteGet['id_disponibilite'],$disponibiliteGet['id_coach'] , $disponibiliteGet['date'] , $disponibiliteGet['heure_debut'],$disponibiliteGet['heure_fin']);
$disponibilites->setDate($date);
$disponibilites->setHeureDebut($start);
$disponibilites->setHeureFin($end);
$update = $disponibilites->modifierDisponibilite();
echo json_encode($update);

