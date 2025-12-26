<?php 
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/sportif.php";
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
    public static function insererReservation($id_coach,$id_sportif,$id_disponibilite,$id_sport,$status){
        $db = Database::getInstance()->getConnection();
        $stmt  = $db->prepare("INSERT INTO reservation 
            (id_sportif, id_coach, id_sport,id_disponibilite,status,date_reservation)
            VALUES (?,?,?,?,?,NOW())");
        $stmt->execute([$id_sportif,$id_coach,$id_sport,$id_disponibilite,$status]);
    }
    public static function getReservationBySportif(){
        $id_sportif = Sportif::getConnectedSportif()["id_sportif"];
        $db = Database::getInstance()->getConnection();
        $stmt  = $db->prepare("SELECT 
                r.id_reservation,
                d.date as date_seance,
                d.heure_debut,
                d.heure_fin,
                r.status,
                r.date_reservation,
                s.nom_sport,
                u.nom AS coach_nom,
                u.prenom AS coach_prenom,
                u.img_utilisateur as coach_img
            FROM reservation r
            JOIN coach c ON r.id_coach = c.id_coach
            JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
            JOIN sport s ON r.id_sport = s.id_sport
            JOIN disponibilite d ON d.id_disponibilite = r.id_disponibilite
            WHERE r.id_sportif = ?
            ORDER BY d.date DESC");
        $stmt->execute([$id_sportif]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function annullerResevationSportif($id_reservation){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "UPDATE reservation 
             SET status = 'annulee' 
             WHERE id_reservation = ?"
        );
        $stmt->execute([$id_reservation]);
    }

    public static function UpdateResevation($id_disponibilite,$date, $heure_debut, $heure_fin){
        $db = Database::getInstance()->getConnection();
        $stmt2 = $db->prepare(
            "UPDATE disponibilite 
             SET isReserved = 0,
             date = ? , 
             heure_debut= ?,
             heure_fin = ?
             WHERE id_disponibilite = ?"
        );
        return $stmt2->execute([$date,$heure_debut,$heure_fin,$id_disponibilite]);
    }

}