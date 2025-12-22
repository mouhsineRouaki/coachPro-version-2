<?php
require_once "../../classeses/utilisateur.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $result = Utilisateur::login($email,$password);
    if ($result['success']) {
        if($result["user"]["role"] === "coach"){
            header("Location: ../../pages/coach/dashbordCoach.php");
        }else{
            header("Location: ../../pages/sportif/dashbordSportif.php");
        }
        exit();   
    } else {
        header("Location: ../../public/index.php?message=" . urlencode($result['message']));
        exit();      
    }

    
}
