<?php
require "../../classeses/reservation.php";

$id_reservation = $_POST['id_reservation'];
$id_disponibilite = $_POST['id_disponibilite'];

if(Reservation::annullerResevation($id_reservation,$id_disponibilite)){
    echo json_encode(["success" => true]);
}else{
    echo json_encode(["success" => false]);

}


