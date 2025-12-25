<?php 
require_once __DIR__ . "/database.php";
Class Sport{
    private $id_sport ;
    private $nom_sport ;
    private $description_sport ;
    private $db;
    public function __construct($id_sport , $nom_sport,$description_sport){
        $this->id_sport = $id_sport;
        $this->nom_sport = $nom_sport;
        $this->description_sport= $description_sport;
    }
    public function __get($name){
        return $name;
    }
    public function __set($name, $value){
        $name = $value;
    }
    public static function getSportCoachConnected(){
        $db = Database::getInstance()->getConnection();
        $id_coach = Coach::getConnectedCoach()["id_coach"];
        $stmt = $db->prepare("select s.id_sport,s.nom_sport,s.description_sport from coach_sport cs 
            INNER JOIN sport s on s.id_sport = cs.id_sport
            INNER JOIN coach c on c.id_coach = cs.id_coach
            where c.id_coach = ?
        ");
        $stmt->execute([$id_coach]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public static function getNonLierSportCoachConnected(){
        $db = Database::getInstance()->getConnection();
        $id_coach = Coach::getConnectedCoach()["id_coach"];
        $stmt = $db->prepare("SELECT s.id_sport, s.nom_sport FROM sport s
            LEFT JOIN coach_sport cs ON cs.id_sport = s.id_sport AND cs.id_coach = ?
            WHERE cs.id_sport IS NULL");
        $stmt->execute([$id_coach]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}