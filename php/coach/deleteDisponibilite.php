<?php
session_start();
require "../../classeses/disponobilite.php";

$id = $_POST['id'];

$supp = Disponibilite::supprimerDisponibilite($id);

echo json_encode($supp);
