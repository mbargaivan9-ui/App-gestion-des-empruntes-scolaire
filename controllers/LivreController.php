<?php
/**
 * Contrôleur Livre
 * Fichier: controllers/LivreController.php
 */

require_once __DIR__ . '/../config/Database.php';

class LivreController {
    private $db;
    private $upload_dir = __DIR__ . '/../assets/uploads/';
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    /**
     * Récupérer tous les livres
     */
    public function getAllLivres() {
        $query = "SELECT * FROM livre WHERE actif = 1 ORDER BY titre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les livres disponibles
     */
    public function getLivresDisponibles() {
        $query = "SELECT * FROM livre WHERE actif = 1 AND nombre_disponibles > 0 ORDER BY titre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer un livre par ID
     */
    public function getLivreById($code_livre) {
        $query = "SELECT * FROM livre WHERE code_livre = :code_livre AND actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Ajouter un nouveau livre
     */
    public function addLivre($data, $file = null) {
        $couverture = null;
        
        // Gérer l'upload de la couverture
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $couverture = $this->uploadCouverture($file);
            if (!$couverture) {
                return ['success' => false, 'message' => 'Erreur lors de l\'upload de la couverture'];
            }
        }
        
        $query = "INSERT INTO livre (titre, auteur, date_edition, editeur, isbn, nombre_copies, 
                 nombre_disponibles, description, couverture)
                 VALUES (:titre, :auteur, :date_edition, :editeur, :isbn, :nombre_copies,
                 :nombre_disponibles, :description, :couverture)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':auteur', $data['auteur']);
        $stmt->bindParam(':date_edition', $data['date_edition']);
        $stmt->bindParam(':editeur', $data['editeur']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':nombre_copies', $data['nombre_copies'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre_disponibles', $data['nombre_copies'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':couverture', $couverture);
        
        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->db->lastInsertId(), 'message' => 'Livre ajouté avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    }
    
    /**
     * Modifier un livre
     */
    public function updateLivre($code_livre, $data, $file = null) {
        $livre = $this->getLivreById($code_livre);
        if (!$livre) {
            return ['success' => false, 'message' => 'Livre non trouvé'];
        }
        
        $couverture = $livre['couverture'];
        
        // Gérer l'upload de la nouvelle couverture
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            if ($livre['couverture'] && file_exists($this->upload_dir . $livre['couverture'])) {
                unlink($this->upload_dir . $livre['couverture']);
            }
            $couverture = $this->uploadCouverture($file);
            if (!$couverture) {
                return ['success' => false, 'message' => 'Erreur lors de l\'upload'];
            }
        }
        
        $query = "UPDATE livre 
                 SET titre = :titre, auteur = :auteur, date_edition = :date_edition,
                     editeur = :editeur, isbn = :isbn, nombre_copies = :nombre_copies,
                     description = :description, couverture = :couverture
                 WHERE code_livre = :code_livre";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':auteur', $data['auteur']);
        $stmt->bindParam(':date_edition', $data['date_edition']);
        $stmt->bindParam(':editeur', $data['editeur']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':nombre_copies', $data['nombre_copies'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':couverture', $couverture);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Livre modifié avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la modification'];
    }
    
    /**
     * Supprimer un livre (soft delete)
     */
    public function deleteLivre($code_livre) {
        $query = "UPDATE livre SET actif = 0 WHERE code_livre = :code_livre";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Livre supprimé'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la suppression'];
    }
    
    /**
     * Upload de la couverture
     */
    private function uploadCouverture($file) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $file['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Vérifier l'extension
        if (!in_array($ext, $allowed)) {
            return false;
        }
        
        // Vérifier la taille (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0755, true);
        }
        
        // Générer un nom unique
        $new_filename = time() . '_' . uniqid() . '.' . $ext;
        $filepath = $this->upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $new_filename;
        }
        
        return false;
    }
    
    /**
     * Obtenir les emprunts d'un livre
     */
    public function getEmpruntsByLivre($code_livre) {
        $query = "SELECT e.code_emprunt, et.nom, et.prenom, e.date_emprunt, e.date_retour_prevue,
                        e.statut, e.amendes
                 FROM emprunt e
                 JOIN etudiant et ON e.code_etudiant = et.code_etudiant
                 WHERE e.code_livre = :code_livre AND e.statut = 'en_cours'
                 ORDER BY e.date_emprunt DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
