<?php
require_once "../../classeses/reservation.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

$id_reservation = $_POST['id_reservation'];
Reservation::annullerResevationSportif($id_reservation);


echo json_encode(["success" => true]);
