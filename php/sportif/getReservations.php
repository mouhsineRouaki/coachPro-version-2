<?php
session_start();
require_once "../../config/connexion.php";

$id_utilisateur = $_SESSION['user_id'] ?? null;
if (!$id_utilisateur) {
    http_response_code(401);
    echo json_encode(["error" => "Non authentifié"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id_sportif
    FROM sportif
    WHERE id_utilisateur = ?
");
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode([]);
    exit;
}

$id_sportif = $res->fetch_assoc()['id_sportif'];

$stmt = $conn->prepare("
    SELECT 
        r.id_reservation,
        d.date,
        d.heure_debut,
        d.heure_fin,
        r.status,
        r.date_reservation,
        s.nom_sport,
        u.nom AS coach_nom,
        u.prenom AS coach_prenom,
        c.coach_img
    FROM reservation r
    JOIN coach c ON r.id_coach = c.id_coach
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    JOIN sport s ON r.id_sport = s.id_sport
    JOIN disponibilite d ON d.id_disponibilite = r.id_disponibilite
    WHERE r.id_sportif = ?
    ORDER BY r.date_seance DESC
");
$stmt->bind_param("i", $id_sportif);
$stmt->execute();

$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);
