<?php 
class Database{
    private static $instance = null ;
    private $pdo ;
    private function __construct($dbname , $user, $pass){
        try{
            $this->pdo = new PDO($dbname,$user,$pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $pdoE){
            error_log("database connection error :".$pdoE->getMessage());
            throw new Exception("Database connection failed");

        }
    }

    public function getInstance($dsn= null , $user = null , $pass = null ){
        if(self::$instance === null){
            $dsn = $dsn ??  'mysql:host=localhost;dbname=coaching_platform';
            $user = $user ?? 'root';
            $pass = $pass ?? 'mouhsinerouaki';
            self::$instance = new Database($dsn ,$user,$pass );
        }
        return self::$instance ;
    }
    
    public function getConnection(){
        return $this->pdo;
    }












}