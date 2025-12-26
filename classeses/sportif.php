<?php 
require __DIR__."/utilisateur.php";
require_once __DIR__ . "/database.php";

class Sportif extends Utilisateur{
    private ?int $id_sportif = null ;
    private ?string $objectif = null ;
    private ?string $niveau= null ;
    private PDO $db; 
    
    public function __construct($user,$sportif){
        parent::__construct($user["nom"] , $user["prenom"] , $user["email"] , $user["mot_de_pass"], $user["telephone"] , $user["role"] , $user["img_utilisateur"] );        $this->db = Database::getInstance()->getConnection();
        $this->id_sportif = $sportif["id_sportif"];
        $this->objectif = $sportif["objectif"] ;
        $this->niveau = $sportif["niveau"];
    }
    public function __get($name){
        return $name ;
    }
    public function __set($name, $value){
        $name = $value ;
    }
    public static function getConnectedSportif(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select * from sportif where id_utilisateur = ?");
        if($stmt->execute([$_SESSION["user_id"]])){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null ; 
    }
    public function getNombreReservationByStatus($status){
        $stmt = $this->db->prepare("select count(*) as total from reservation r 
        INNER JOIN sportif s on r.id_sportif = s.id_sportif
        Where status = ?
        ");
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["total"];
    }
    public function getNextSeance(){

        $stmt=  $this->db->prepare("
            SELECT r.*, c.biographie, u.nom, u.prenom, s.nom_sport,
                d.date, d.heure_debut, d.heure_fin
            FROM reservation r
            INNER JOIN coach c ON r.id_coach = c.id_coach
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            INNER JOIN sport s ON r.id_sport = s.id_sport
            INNER JOIN disponibilite d on d.id_disponibilite = r.id_disponibilite
            WHERE r.id_sportif = ? 
            AND r.status = 'confirmee'
            AND d.date >= CURDATE()
            ORDER BY d.date ASC, d.heure_debut ASC
            LIMIT 1
        ");
        $stmt->execute([$this->id_sportif]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}