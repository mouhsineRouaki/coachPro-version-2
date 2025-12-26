<?php
require_once "../../classeses/coach.php";
require_once "../../components/card.php";


$recherche = isset($_GET['query']) ? $_GET['query'] : '';

$html = "";
$coachs = Coach::getCoach($recherche);
foreach($coachs as $c){
    $html .= createCoachCard($c);
}
echo $html;
