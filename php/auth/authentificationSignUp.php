<?php
require_once "../../classeses/utilisateur.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = new Utilisateur(
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['password'],
        $_POST['telephone'],
        $_POST['role'],
        $_POST['urlImage'],null
    );

    $result = $user->register();

    if ($result['success']) {
        header("Location: ../../public/index.php?message=" . urlencode($result['message']));
        exit();   
    } else {
        header("Location: ../../public/index.php?message=" . urlencode($result['message']));
        exit();      
    }
}
