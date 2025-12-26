<?php
session_start();
require_once "../../classeses/sportif.php";

$userConnected = Utilisateur::getUserConnected();
$sportifConnected = Sportif::getConnectedSportif();
var_dump($sportifConnected);
var_dump($userConnected);
$sportif = new sportif($userConnected,$sportifConnected);

$nom       = $_POST["nom"];
$prenom    = $_POST["prenom"];
$email     = $_POST["email"];
$telephone = $_POST["telephone"];
$img = $_POST["url_image"];

$sportif->nom=$nom;
$sportif->prenom=$prenom;
$sportif->email=$email;
$sportif->telephone=$telephone;
$sportif->image = $img;

$sportif->UpdateInfoSportif();



if ($sportif->updateInfoUser()) {
    echo  $sportif;
} else {
    echo "Erreur lors de la mise à jour";
}
