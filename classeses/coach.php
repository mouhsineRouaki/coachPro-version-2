<?php 
require_once __DIR__."/utilisateur.php";
require_once __DIR__ . "/database.php";

class Coach extends Utilisateur{
    private ?int$id_coach = null ;
    private ?string $biographie= null ;
    private ?string $niveau= null ; 
    private $annee_exp= null ;
    private PDO $db; 
    
    public function __construct($user, $coach){
        parent::__construct($user["nom"] , $user["prenom"] , $user["email"] , $user["mot_de_pass"], $user["telephone"] , $user["role"] , $user["img_utilisateur"] );
        $this->db = Database::getInstance()->getConnection();
        $this->id_coach = $coach["id_coach"];
        $this->biographie = $coach["biographie"] ;
        $this->niveau = $coach["niveau"];
        $this->annee_exp = $coach["annee_experience"];
    }


    public static function getConnectedCoach(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select * from coach where id_utilisateur = ?");
        if($stmt->execute([$_SESSION["user_id"]])){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null ; 
    }
    public function __set($name, $value){
        return $name = $value;
    }
    public function __get($name){
        return $name;
    }

    public function getNextSportifSeance(){
        $stmt = $this->db->prepare("select u.nom , u.prenom , u.img_utilisateur , d.date , s.nom_sport , d.heure_debut , d.heure_fin from reservation r
            INNER JOIN sportif sf on sf.id_sportif = r.id_sportif 
            INNER JOIN utilisateur u on u.id_utilisateur = sf.id_utilisateur 
            Inner join disponibilite d on d.id_disponibilite = r.id_disponibilite 
            INNER JOIN sport s on s.id_sport = r.id_sport
            INNER JOIN coach c on u.id_utilisateur = c.id_utilisateur 
            where c.id_coach = ? and r.status = 'confirmee'
            order by d.date desc 
            limit 1 
        ");
        $id_coach = Coach::getConnectedCoach()["id_coach"];
        $stmt->execute([$id_coach]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getNombreReservationByStatus($status){
        $stmt = $this->db->prepare("select count(*) as total from reservation r 
        INNER JOIN coach c on r.id_coach = c.id_coach
        Where status = ?
        ");
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["total"];
    }
    public function getHistoriqueReservation(){
        $stmt = $this->db->prepare("select r.* , d.date,d.heure_debut,d.heure_fin , u.nom , prenom , s.nom_sport from reservation r 
            INNER JOIN sportif sp on r.id_sportif = sp.id_sportif
            INNER JOIN utilisateur u on u.id_utilisateur = sp.id_utilisateur
            INNER JOIN disponibilite d on d.id_disponibilite = r.id_disponibilite
            INNER JOIN sport s on s.id_sport = r.id_sport 
            where r.id_coach =  ?
            order by d.date desc
        ");
        $id_coach = Coach::getConnectedCoach()["id_coach"];
        $stmt->execute([$id_coach]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getReservations(){
        $id_coach =  Coach::getConnectedCoach()["id_coach"];
        $stmt = $this->db->prepare("SELECT r.id_reservation,d.date ,d.id_disponibilite,d.heure_debut,d.heure_fin,s.nom_sport,u.nom,u.prenom,u.img_utilisateur , r.status,u.telephone FROM reservation r
            INNER JOIN sportif sp on sp.id_sportif = r.id_sportif 
            INNER JOIN utilisateur u on u.id_utilisateur = sp.id_utilisateur 
            Inner join disponibilite d on d.id_disponibilite = r.id_disponibilite 
            INNER JOIN sport s  on s.id_sport = r.id_sport 
            WHERE r.id_coach = ?
            order by d.date
        ");
        $stmt->execute([$id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function confirmReservation($id_reservation, $id_disponibilite){
        $stmt = $this->db->prepare(
            "UPDATE reservation 
             SET status = 'confirmee' 
             WHERE id_reservation = ?"
        );
        $stmt->execute([$id_reservation]);

        $stmt2 = $this->db->prepare(
            "UPDATE disponibilite 
             SET isReserved = 1 
             WHERE id_disponibilite = ?"
        );
        return $stmt2->execute([$id_disponibilite]);

}
    public function updateInfo(){
        parent::updateInfoUser();
        $stmt = $this->db->prepare("update coach set niveau = ? , biographie = ? where id_coach = ?");
        if($stmt->execute([$this->niveau,$this->biographie,$this->id_coach])){
            return ['success'=>true , 'message'=>"bien update"];
        }else{
            return ['success'=>true , 'message'=>"ne pas update"];
        }
    }
    public function addSport($id_sport){
        $stmt = $this->db->prepare("insert into coach_sport values (?,?)");
        if($stmt->execute([$this->id_coach,$id_sport])){
            return ['success'=>true , 'message'=>"bien ajouer le sport"];
        }
    }
    public function addExperience($domaine , $duree , $dateDebut , $dateFin){
        $stmt = $this->db->prepare("insert into experiences values (null , ?,?,?,?,?)");
        if($stmt->execute([$this->id_coach,$dateDebut, $dateFin ,$duree, $domaine])){
            return ['success'=>true , 'message'=>"bien ajouter lexperience "];
        }
    }
    public function deleteSport($id_sport){
        $stmt = $this->db->prepare("delete from coach_sport where id_sport = ? , id_coach = ? ");
        if($stmt->execute([$this->id_coach,$id_sport])){
            return ['success'=>true , 'message'=>"bien supprimer le sport "];
        }
    }
    public function deleteExperience($id_sport){
        $stmt = $this->db->prepare("delete from experiences  where id_coach = ? ");
        if($stmt->execute([$this->id_coach])){
            return ['success'=>true , 'message'=>"bien supprimer"];
        }
    }

    public static function getCoachsDisponible(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select count(*) as total from coach " );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
    }
    public static function getCoach($queryR = ""){
        $db = Database::getInstance()->getConnection();
        $query = "%".$queryR."%";
        if(empty($queryR)){
        $stmt = $db->prepare("
            SELECT c.*, u.nom,u.img_utilisateur, u.prenom, u.email, u.telephone, c.biographie, c.niveau,
               GROUP_CONCAT(DISTINCT s.nom_sport SEPARATOR ', ') as sports
            FROM coach c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            LEFT JOIN coach_sport cs ON c.id_coach = cs.id_coach
            LEFT JOIN sport s ON cs.id_sport = s.id_sport
            GROUP BY c.id_coach
            ORDER BY u.nom ASC
        ");
        $stmt->execute();
        }else{
            $stmt = $db->prepare("
            SELECT c.*, u.nom, u.prenom, u.email, u.telephone, c.biographie, c.niveau,
               GROUP_CONCAT(DISTINCT s.nom_sport SEPARATOR ', ') as sports
            FROM coach c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            LEFT JOIN coach_sport cs ON c.id_coach = cs.id_coach
            LEFT JOIN sport s ON cs.id_sport = s.id_sport
            where u.nom like ? or u.prenom like ?
            GROUP BY c.id_coach
            ORDER BY u.nom ASC
        ");
        $stmt->execute([$query,$query]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    public static function getSportsCoach($id_coach){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT s.id_sport, s.nom_sport FROM coach_sport cs
            JOIN sport s ON s.id_sport = cs.id_sport
            WHERE cs.id_coach = ?
        ");
        $stmt->execute([$id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getExperiencesCoach($id_coach){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT domaine, date_debut, date_fin, duree
            FROM experiences
            WHERE id_coach = ?
            ORDER BY date_debut DESC
        ");
        $stmt->execute([$id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getdisponibiliteCoach($id_coach){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id_disponibilite ,DATE(date) AS date, TIME(heure_debut) AS heure_debut, TIME(heure_fin) AS heure_fin, isReserved
            FROM disponibilite
            WHERE id_coach = ?
            ORDER BY date, heure_debut
        ");
        $stmt->execute([$id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getCoachById($id_coach){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT c.*,u.img_utilisateur, u.nom, u.prenom, u.email, u.telephone, c.biographie, c.niveau,
               GROUP_CONCAT(DISTINCT s.nom_sport SEPARATOR ', ') as sports
            FROM coach c
            INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            LEFT JOIN coach_sport cs ON c.id_coach = cs.id_coach
            LEFT JOIN sport s ON cs.id_sport = s.id_sport
            where c.id_coach = ?
            GROUP BY c.id_coach
            ORDER BY u.nom ASC
        ");
        $stmt->execute([$id_coach]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    





}