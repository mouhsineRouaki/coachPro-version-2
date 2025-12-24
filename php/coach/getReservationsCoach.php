<?php
session_start();
require "../../classeses/coach.php";
$userConnected = Utilisateur::getUserConnected();
$coachConnected = Coach::getConnectedCoach();
$coach = new Coach($userConnected,$coachConnected);
echo json_encode($coach->getReservations());
