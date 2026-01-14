<?php
/**
 * Classe d'Authentification
 * Fichier: includes/Auth.php
 */

require_once __DIR__ . '/../config/Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    /**
     * Connexion utilisateur
     */
    public function login($nom_utilisateur, $mot_de_passe) {
        $query = "SELECT * FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur AND actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nom_utilisateur'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Enregistrement nouvel utilisateur
     */
    public function register($nom_utilisateur, $email, $mot_de_passe) {
        // Vérifier si l'utilisateur existe déjà
        $query = "SELECT id FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur OR email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'L\'utilisateur ou l\'email existe déjà'];
        }
        
        // Hasher le mot de passe
        $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        
        // Insérer le nouvel utilisateur
        $query = "INSERT INTO utilisateur (nom_utilisateur, email, mot_de_passe, role) 
                 VALUES (:nom_utilisateur, :email, :mot_de_passe, 'user')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Enregistrement réussi!'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement'];
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        session_destroy();
        return true;
    }
    
    /**
     * Obtenir les informations de l'utilisateur actuel
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }
}
