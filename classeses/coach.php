<?php 
require __DIR__."/utilisateur.php";
class Coach extends Utilisateur{
    private $id_coach = null ;
    private $biographie= null ;
    private $niveauv= null ;
    private $annee_exp= null ;
    public function __construct(string $nom, string $prenom, string $email, string $password, string $telephone, string $role, string $image = null)

}