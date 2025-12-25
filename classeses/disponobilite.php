<?php 
require_once __DIR__ . "/database.php";
class Disponibilite{
    private $id_coach  ;
    private $id_disponibilite  ;
    private $date ;
    private $heure_debut ;
    private $heure_fin ;
    private PDO $db; 
    public function __construct($id_disponibilite,$id_coach,$date,$heure_debut,$heure_fin){
        $this->db = Database::getInstance()->getConnection();
        $this->id_coach = $id_coach;
        $this->id_disponibilite = $id_disponibilite;
        $this->date = $date;
        $this->heure_debut = $heure_debut;
        $this->heure_fin = $heure_fin;
    }
    public function setDate($newDate){
        $this->date = $newDate;
    }
    public function setHeureDebut($newHeureDebut){
        $this->heure_debut = $newHeureDebut;
    }
    public function setHeureFin($newHeureFin){
        $this->heure_fin = $newHeureFin;
    }
    public static function getDisponibilites($id_coach){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "select *  from disponibilite  
             WHERE id_coach = ?"
        );
        $stmt->execute([$id_coach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public  function ajouterDisponibilite(){
        $stmtCheck  = $this->db->prepare("select * from disponibilite where id_coach = ? and date = ? and (?<heure_fin and  ?>heure_debut)");
        $stmtCheck->execute([$this->id_coach, $this->date , $this->heure_debut , $this->heure_fin]);
        $result = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);
        if(!$result){
            $stmt = $this->db->prepare("insert into disponibilite values (null , ?,?,?,?,0)");
            if($stmt->execute([$this->id_coach , $this->date  , $this->heure_debut , $this->heure_fin])){
                return ['success' => true,'message' => 'Disponibilité ajoutee avec succès'];
            }else{
                return ['success' => false,'message' => 'erreur de ajout '];
            }
        }else{
            return ['success' => false,'message' => 'Créneau déjà existant ou chevauchement détecté'];
        }  
    }
    public function modifierDisponibilite(){
        $stmtCheck  = $this->db->prepare("select * from disponibilite where id_coach = ? and date = ? and (? <  heure_fin and  ? > heure_debut)");
        $stmtCheck->execute([$this->id_coach, $this->date , $this->heure_debut , $this->heure_fin]);
        $result = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);
        if(!$result){
            $stmt = $this->db->prepare("update disponibilite set 
            date = ? , 
            heure_debut = ?,
            heure_fin = ? 
            where id_disponibilite = ?
            ");
            if($stmt->execute([$this->date  , $this->heure_debut , $this->heure_fin , $this->id_disponibilite])){
                return ['success' => true,'message' => 'Disponibilité ajoutee avec succès'];
            }else{
                return ['success' => false,'message' => 'erreur de ajout '];
            }
        }else{
            return ['success' => false,'message' => 'Créneau déjà existant ou chevauchement détecté'];
        }  
    }
    public static function supprimerDisponibilite($id_disponibilite){
        $db = Database::getInstance()->getConnection();
        $stmtCheck  = $db->prepare("delete from disponibilite where id_disponibilite = ? ");
        try {
            $stmtCheck->execute([$id_disponibilite]);
            return ['success'=>false,'message'=>"suppression avec succes "];
        } catch (PDOException $e) {
            return ['success'=>false,'message'=>$e->getMessage()];
        }
    }
    public static function getDisponibiliteById($id_disponibilite){
        $db = Database::getInstance()->getConnection();
        $lier = Disponibilite::checkDisponibiliteById($id_disponibilite);
        $stmtCheck  = $db->prepare("SELECT d.* ,".$lier." as lier from disponibilite d where id_disponibilite = ? ");
        $stmtCheck->execute([$id_disponibilite]);
        return  $stmtCheck->fetch(PDO::FETCH_ASSOC);
    } 
     public static function checkDisponibiliteById($id_disponibilite){
        $db = Database::getInstance()->getConnection();
        $stmtCheck  = $db->prepare("SELECT * from reservation where id_disponibilite = ? ");
        $stmtCheck->execute([$id_disponibilite]);
        if($stmtCheck->fetch(PDO::FETCH_ASSOC)){
            return true; 
        }
        return false ;
    } 

}