<?php
session_start();
require "../../classeses/coach.php";
$userConnected = Utilisateur::getUserConnected();
$coachConnected = Coach::getConnectedCoach();
$coach = new Coach($userConnected,$coachConnected);

$id_reservation = $_POST['id_reservation'];
$id_disponibilite = $_POST['id_disponibilite'];
if($coach->confirmReservation($id_reservation,$id_disponibilite)){
    echo json_encode(["success" => true]);
}else{
    echo json_encode(["success" => false]);
}

