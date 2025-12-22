<?php
require_once "../../classeses/utilisateur.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $result = Utilisateur::login($email,$password);
    if ($result['success']) {
        header("Location: ../../public/index.php?message=" . urlencode($result['message']));
        exit();   
    } else {
        header("Location: ../../public/index.php?message=" . urlencode($result['message']));
        exit();      
    }

    
}
