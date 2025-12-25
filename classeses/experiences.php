<?php 
require_once __DIR__ . "/database.php";
Class Experience{
    private $id_experience ;
    private $id_coach ;
    private $domaine  ;
    private $duree  ;
    private $dateDebut  ;
    private $dateFin  ;
    private $db;
    public function __construct($exp){
        $this->id_experience = $exp["id_experience"];
        $this->id_coach  = $exp["id_coach"];
        $this->domaine = $exp["domaine"];
        $this->dateDebut = $exp["date_debut"];
        $this->dateFin = $exp["date_fin"];
    }
    public function __get($name){
        return $name;
    }
    public function __set($name, $value){
        $name = $value;
    }
    public static function getExperienceCoachConnected(){
        $db = Database::getInstance()->getConnection();
        $id_coach = Coach::getConnectedCoach()["id_coach"];
        $stmt = $db->prepare("select * from experiences where id_coach = ?");
        $stmt->execute([$id_coach]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}