<?php
/**
 * Contrôleur Étudiant
 * Fichier: controllers/EtudiantController.php
 */

require_once __DIR__ . '/../config/Database.php';

class EtudiantController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    /**
     * Récupérer tous les étudiants
     */
    public function getAllEtudiants() {
        $query = "SELECT * FROM etudiant WHERE actif = 1 ORDER BY nom, prenom";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer un étudiant par ID
     */
    public function getEtudiantById($code_etudiant) {
        $query = "SELECT * FROM etudiant WHERE code_etudiant = :code_etudiant AND actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Ajouter un nouvel étudiant
     */
    public function addEtudiant($data) {
        $query = "INSERT INTO etudiant (nom, prenom, adresse, classe, email, telephone) 
                 VALUES (:nom, :prenom, :adresse, :classe, :email, :telephone)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':classe', $data['classe']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->db->lastInsertId(), 'message' => 'Étudiant ajouté avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    }
    
    /**
     * Modifier un étudiant
     */
    public function updateEtudiant($code_etudiant, $data) {
        $query = "UPDATE etudiant 
                 SET nom = :nom, prenom = :prenom, adresse = :adresse, classe = :classe, 
                     email = :email, telephone = :telephone
                 WHERE code_etudiant = :code_etudiant";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':adresse', $data['adresse']);
        $stmt->bindParam(':classe', $data['classe']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Étudiant modifié avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la modification'];
    }
    
    /**
     * Supprimer un étudiant (soft delete)
     */
    public function deleteEtudiant($code_etudiant) {
        $query = "UPDATE etudiant SET actif = 0 WHERE code_etudiant = :code_etudiant";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Étudiant supprimé'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la suppression'];
    }
    
    /**
     * Obtenir les emprunts actifs d'un étudiant
     */
    public function getEmpruntsByEtudiant($code_etudiant) {
        $query = "SELECT e.code_emprunt, l.titre, l.auteur, e.date_emprunt, e.date_retour_prevue,
                        e.statut, e.amendes,
                        DATEDIFF(CURDATE(), e.date_retour_prevue) as jours_retard
                 FROM emprunt e
                 JOIN livre l ON e.code_livre = l.code_livre
                 WHERE e.code_etudiant = :code_etudiant AND e.statut = 'en_cours'
                 ORDER BY e.date_emprunt DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
