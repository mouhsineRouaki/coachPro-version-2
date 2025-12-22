<?php
require_once __DIR__ . "./database.php";

class Utilisateur {

    private ?int $id = null;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $telephone;
    private string $role;
    private string $image;

    private $db = Database::getInstance()->getConnection();

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        string $telephone,
        string $role,
        ?string $image = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->telephone = $telephone;
        $this->role = $role;
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
        return [
            "success" => true,
            "message" => "Inscription réussie",
            "id_utilisateur" => $this->id
        ];
    }

    public static function login(string $email, string $password): array {

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
}

