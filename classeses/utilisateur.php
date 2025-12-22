<?php 
class Utilisateur{
    private $id = null;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;
    private $telephone;
    private $db = Database::getInstance()->getConnection();
    public function __construct($nom, $prenom, $email, $password , $role,$telephone){
        $this->nom = $nom;
        $this->prenom= $prenom ;
        $this->email = $email;
        $this->password = password_hash($password) ;
        $this->role = $role;
        $this->telephone= $telephone;
    }
    public function __toString(){
        return $this->nom . " ".$this->prenom;
    }

    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getTelephone(){
        return $this->telephone;
    }
    public function getRole(){
        return $this->role;
    }

    public function InscriptionUser(){

    }
    public function getIdUser(){
        $stmt = $this->db->prepare("select * from utilisateur where email = :email");
        $stmt->bindParam(':email',$this->email , PDO::PARAM_STR);
        if($stmt->execute()){
            $this->id= $stmt->fetch(PDO::FETCH_ASSOC)["id_utilisateur"];
        }
    }

    public function insererUser(){
        $stmt = $this->db->prepare("insert into utilisateur(nom, prenom , email , telephone, role, mot_de_pass , date_creation,iscritComplet) values (:nom,:prenom,:email,:telephone,:role,,:password,NOW(),true)");
        $stmt->bindParam(':nom',$this->nom , PDO::PARAM_STR);
        $stmt->bindParam(':prenom',$this->prenom , PDO::PARAM_STR);
        $stmt->bindParam(':email',$this->email , PDO::PARAM_STR);
        $stmt->bindParam(':telephone',$this->telephone , PDO::PARAM_STR);
        $stmt->bindParam(':role',$this->role , PDO::PARAM_STR);
        $stmt->bindParam(':mot_de_pass',$this->password , PDO::PARAM_STR);
        $stmt->execute();
    }
    
}