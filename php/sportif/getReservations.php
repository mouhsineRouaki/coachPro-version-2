<?php
session_start();
require_once "../../classeses/reservation.php";


$data = Reservation::getReservationBySportif();

echo json_encode($data);
