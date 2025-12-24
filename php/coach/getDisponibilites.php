<?php
session_start();
require "../../classeses/coach.php";
require "../../classeses/disponobilite.php";

$id_coach = Coach::getConnectedCoach()["id_coach"];
$disponibilites = Disponibilite::getDisponibilites($id_coach);

echo json_encode($disponibilites);
