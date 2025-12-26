<?php
require_once __DIR__ . "/database.php";

class Utilisateur {

    protected int $id ;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $password;
    protected string $telephone;
    protected string $role;
    protected string $image;

    private PDO $db;

    public function __construct(string $nom,string $prenom,string $email,string $password,string $telephone,string $role, $image, $id) {
        $this->db =  Database::getInstance()->getConnection();
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->telephone = $telephone;
        $this->role = $role;
        $this->image = $image;
        $this->id = $_SESSION["user_id"];
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTelephone(): string {
        return $this->telephone;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTelephone(string $telephone): void {
        $this->telephone = $telephone;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function register(): array {

        $check = $this->db->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
        $check->execute([$this->email]);
        if ($check->fetch()) {
            return [
                "success" => false,
                "message" => "Email deja utilise"
            ];
        }
        $stmt = $this->db->prepare("INSERT INTO utilisateur(nom, prenom, email, mot_de_pass, telephone, role, img_utilisateur, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$this->nom,$this->prenom,$this->email,$this->password,$this->telephone,$this->role,$this->image]);
        $this->id = $this->db->lastInsertId();
        if($this->role === "coach"){
            $stmtC = $this->db->prepare("INSERT INTO coach(id_utilisateur) values (?)");
            $stmtC->execute([$this->id]);
        }else{
            $stmtC = $this->db->prepare("INSERT INTO sportif(id_utilisateur) values (?)");
            $stmtC->execute([$this->id]);
        }
        return [
            "success" => true,
            "message" => "Inscription réussie",
            "id_utilisateur" => $this->id
        ];
    }

    public static function login(string $email, string $password): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['mot_de_pass'])) {
            return [
                "success" => false,
                "message" => "Email ou mot de passe incorrect"
            ];
        }

        session_start();
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['role'] = $user['role'];

        return [
            "success" => true,
            "message" => "Connexion réussie",
            "user" => $user
        ];
    }
    public static function getUserConnected(){
        if(!isset($_SESSION["user_id"])){
            return null;
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public static function lougoutUser(){
        session_destroy();
    }
    public function updateInfoUser(): bool {
        $stmt = $this->db->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, img_utilisateur = ? WHERE id_utilisateur = ?");
        return $stmt->execute([$this->nom, $this->prenom, $this->email, $this->telephone, $this->image, $this->id]);
    }

    public function deleteUser(): bool {
        $stmt = $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
        return $stmt->execute([$this->id]);
    }
    public function updateInfoUserByIdUser($id): bool {
        $stmt = $this->db->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, img_utilisateur = ? WHERE id_utilisateur = ?");
        return $stmt->execute([$this->nom, $this->prenom, $this->email, $this->telephone, $this->image, $id]);
    }
    public function changePassword(string $currentPassword, string $newPassword, string $confirmPassword): array
{
    if ($newPassword !== $confirmPassword) {
        return [
            "success" => false,
            "message" => "Les mots de passe ne correspondent pas"
        ];
    }
    $stmt = $this->db->prepare(
        "SELECT mot_de_pass FROM utilisateur WHERE id_utilisateur = ?"
    );
    $stmt->execute([$this->id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return [
            "success" => false,
            "message" => "Utilisateur introuvable"
        ];
    }
    if (!password_verify($currentPassword, $user["mot_de_pass"])) {
        return [
            "success" => false,
            "message" => "Mot de passe actuel incorrect"
        ];
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $this->db->prepare(
        "UPDATE utilisateur SET mot_de_pass = ? WHERE id_utilisateur = ?"
    );

    if ($update->execute([$newHash, $this->id])) {
        return [
            "success" => true,
            "message" => "Mot de passe modifié avec succès"
        ];
    }
    return [
        "success" => false,
        "message" => "Erreur lors du changement du mot de passe"
    ];
}

}

