<?php
session_start();
require "../../classeses/coach.php";
require "../../classeses/sport.php";
require "../../classeses/experiences.php";
header('Content-Type: application/json');

$userConnected = Utilisateur::getUserConnected();
$coachConnected=Coach::getConnectedCoach();
$id_coach = $coachConnected['id_coach'] ?? null;
$action = $_POST['action'] ?? null;
$coach = new Coach($userConnected,$coachConnected);

if (!$id_coach || !$action) {
    echo json_encode(['success'=>false,'message'=>"Accès refusé"]);
    exit;
}

$data = $_POST; 

switch ($action) {
    case 'get':
        echo json_encode($coach);
        break;

    case 'updateInfo':
        $coach->setNom($data["nom"]);
        $coach->setPrenom($data["prenom"]);
        $coach->setEmail("email");
        $coach->niveau = $data["niveau"];
        $coach->setTelephone($data["telephone"]);
        echo json_encode($coach->updateInfo());
        break;

    case 'updateBio':
        $coach->biographie = $data["biographie"];
        echo json_encode($coach->updateInfo());
        break;

    case 'addSport':
        echo json_encode($coach->addSport($data["id_sport"]));
        break;

    case 'addExperience':
        echo json_encode($coach->addExperience($data["domaine"] , $data["duree"] ,$data["date_debut"] , $data["date_fin"]));
        break;

    case 'deleteSport':
        echo json_encode($coach->deleteSport($data["id_sport"]));
        break;

    case 'deleteExperience':
        echo json_encode($coach->deleteExperience($data["id_sport"]));
        break;

    case 'getSportsCoach':
        echo json_encode(['data'=>Sport::getSportCoachConnected()]);
        break;

    case 'getExperiences':
        echo json_encode(['data'=>Experience::getExperienceCoachConnected()]);
        break;

    default:
        echo json_encode(['success'=>false, 'message'=>"Action inconnue"]);
}
