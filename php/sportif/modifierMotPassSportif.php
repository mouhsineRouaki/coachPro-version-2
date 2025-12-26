<?php
session_start();
require_once "../../classeses/sportif.php";

$current = $_POST["currentPassword"];
$new     = $_POST["newPassword"];
$confirm = $_POST["confirmPassword"];

$userConnected = Utilisateur::getUserConnected();
$sportifConnected = Sportif::getConnectedSportif();
$sportif = new sportif($userConnected,$sportifConnected);
$r = $sportif->changePassword($current,$new,$confirm);

if ($r["success"]) {
     header("Location: ../../pages/sportif/profilSportif.php");
} else {
    echo "Erreur changement mot de passe";
}

