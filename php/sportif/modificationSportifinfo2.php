<?php
session_start();
require_once "../../classeses/sportif.php";

$userConnected = Utilisateur::getUserConnected();
$sportifConnected = Sportif::getConnectedSportif();
$sportif = new sportif($userConnected,$sportifConnected);

$niveau   = $_POST["niveau"];
$objectif = $_POST["objectif"];

$sportif->niveau=$niveau;
$sportif->objectif=$objectif;

$sportif->UpdateInfoSportif();



if ($sportif->updateInfoUser()) {
 header("Location: ../../pages/sportif/profilSportif.php");
} else {
    echo "Erreur lors de la mise à jour";
}
