<?php 
require_once __DIR__ . "/database.php";
class Reservation{
    private $id_reservation  ;
    private $id_coach  ;
    private $id_disponibilite  ;
    private $id_sportif ;
    private $id_sport ;
    private $date_reservation ;
    private $status ;
    private PDO $db; 
    public function __construct($id_reservation,$id_coach,$id_sportif,$id_disponibilite,$id_sport,$status){
        $this->db = Database::getInstance()->getConnection();
        $this->id_reservation = $id_reservation;
        $this->id_coach = $id_coach;
        $this->id_disponibilite = $id_disponibilite;
        $this->id_sport = $id_sport;
        $this->id_sportif = $id_sportif;
        $this->status = $status;
    }
    public static function confirmeResevation($id_reservation,$id_disponibilite){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "UPDATE reservation 
             SET status = 'confirmee' 
             WHERE id_reservation = ?"
        );
        $stmt->execute([$id_reservation]);

        $stmt2 = $db->prepare(
            "UPDATE disponibilite 
             SET isReserved = 1 
             WHERE id_disponibilite = ?"
        );
        return $stmt2->execute([$id_disponibilite]);
    }
     public static function annullerResevation($id_reservation,$id_disponibilite){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "UPDATE reservation 
             SET status = 'annulee' 
             WHERE id_reservation = ?"
        );
        $stmt->execute([$id_reservation]);

        $stmt2 = $db->prepare(
            "UPDATE disponibilite 
             SET isReserved = 0
             WHERE id_disponibilite = ?"
        );
        return $stmt2->execute([$id_disponibilite]);
    }

}