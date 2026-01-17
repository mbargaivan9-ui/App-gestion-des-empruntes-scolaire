<?php
/**
 * Contrôleur Emprunt
 * Fichier: controllers/EmpruntController.php
 */

require_once __DIR__ . '/../config/Database.php';

class EmpruntController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    /**
     * Récupérer tous les emprunts
     */
    public function getAllEmprunts() {
        $query = "SELECT e.code_emprunt, et.code_etudiant, et.nom, et.prenom, et.classe,
                        l.code_livre, l.titre, l.auteur, e.date_emprunt, e.date_retour_prevue,
                        e.date_retour_reelle, e.statut, e.amendes,
                        DATEDIFF(CURDATE(), e.date_retour_prevue) as jours_retard
                 FROM emprunt e
                 JOIN etudiant et ON e.code_etudiant = et.code_etudiant
                 JOIN livre l ON e.code_livre = l.code_livre
                 ORDER BY e.date_emprunt DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer les emprunts actifs
     */
    public function getEmpruntActifs() {
        $query = "SELECT e.code_emprunt, et.code_etudiant, et.nom, et.prenom, et.classe,
                        l.code_livre, l.titre, l.auteur, e.date_emprunt, e.date_retour_prevue,
                        e.statut, e.amendes,
                        DATEDIFF(CURDATE(), e.date_retour_prevue) as jours_retard
                 FROM emprunt e
                 JOIN etudiant et ON e.code_etudiant = et.code_etudiant
                 JOIN livre l ON e.code_livre = l.code_livre
                 WHERE e.statut = 'en_cours'
                 ORDER BY e.date_retour_prevue ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupérer un emprunt par ID
     */
    public function getEmpruntById($code_emprunt) {
        $query = "SELECT e.*, et.nom, et.prenom, et.classe, et.email, et.telephone,
                        l.titre, l.auteur, l.isbn
                 FROM emprunt e
                 JOIN etudiant et ON e.code_etudiant = et.code_etudiant
                 JOIN livre l ON e.code_livre = l.code_livre
                 WHERE e.code_emprunt = :code_emprunt";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_emprunt', $code_emprunt, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Créer un nouvel emprunt
     */
    public function createEmprunt($code_etudiant, $code_livre, $jours_emprunt = 14) {
        // Vérifier la disponibilité du livre
        $query = "SELECT nombre_disponibles FROM livre WHERE code_livre = :code_livre AND actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->execute();
        $livre = $stmt->fetch();
        
        if (!$livre || $livre['nombre_disponibles'] <= 0) {
            return ['success' => false, 'message' => 'Livre non disponible'];
        }
        
        // Vérifier que l'étudiant n'a pas déjà emprunté ce livre
        $query = "SELECT code_emprunt FROM emprunt 
                 WHERE code_etudiant = :code_etudiant AND code_livre = :code_livre 
                 AND statut = 'en_cours'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Cet étudiant a déjà emprunté ce livre'];
        }
        
        // Créer l'emprunt
        $date_retour = date('Y-m-d', strtotime('+' . $jours_emprunt . ' days'));
        
        $query = "INSERT INTO emprunt (code_etudiant, code_livre, date_emprunt, date_retour_prevue)
                 VALUES (:code_etudiant, :code_livre, CURDATE(), :date_retour_prevue)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_etudiant', $code_etudiant, PDO::PARAM_INT);
        $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
        $stmt->bindParam(':date_retour_prevue', $date_retour);
        
        if ($stmt->execute()) {
            // Mettre à jour la disponibilité
            $query = "UPDATE livre SET nombre_disponibles = nombre_disponibles - 1 
                     WHERE code_livre = :code_livre";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':code_livre', $code_livre, PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => true, 'id' => $this->db->lastInsertId(), 'message' => 'Emprunt enregistré avec succès'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement'];
    }
    
    /**
     * Enregistrer un retour
     */
    public function enregistrerRetour($code_emprunt) {
        $emprunt = $this->getEmpruntById($code_emprunt);
        
        if (!$emprunt) {
            return ['success' => false, 'message' => 'Emprunt non trouvé'];
        }
        
        if ($emprunt['statut'] !== 'en_cours') {
            return ['success' => false, 'message' => 'Cet emprunt n\'est pas actif'];
        }
        
        // Calculer les amendes
        $date_retour_prevue = new DateTime($emprunt['date_retour_prevue']);
        $date_aujourd_hui = new DateTime();
        $jours_retard = $date_aujourd_hui->diff($date_retour_prevue)->days;
        
        $amendes = 0;
        if ($jours_retard > 0) {
            $amendes = $jours_retard * 0.50; // 0.50€ par jour
        }
        
        // Mettre à jour l'emprunt
        $query = "UPDATE emprunt 
                 SET statut = 'retourne', date_retour_reelle = CURDATE(), amendes = :amendes
                 WHERE code_emprunt = :code_emprunt";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code_emprunt', $code_emprunt, PDO::PARAM_INT);
        $stmt->bindParam(':amendes', $amendes);
        
        if ($stmt->execute()) {
            // Remettre à jour la disponibilité
            $query = "UPDATE livre SET nombre_disponibles = nombre_disponibles + 1 
                     WHERE code_livre = :code_livre";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':code_livre', $emprunt['code_livre'], PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => true, 'amendes' => $amendes, 'message' => 'Retour enregistré'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement'];
    }
    
    /**
     * Obtenir les statistiques
     */
    public function getStatistiques() {
        $stats = [];
        
        // Total des emprunts actifs
        $query = "SELECT COUNT(*) as total FROM emprunt WHERE statut = 'en_cours'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['emprunts_actifs'] = $stmt->fetch()['total'];
        
        // Total des retards
        $query = "SELECT COUNT(*) as total FROM emprunt 
                 WHERE statut = 'en_cours' AND CURDATE() > date_retour_prevue";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['emprunts_en_retard'] = $stmt->fetch()['total'];
        
        // Total des amendes
        $query = "SELECT SUM(amendes) as total FROM emprunt WHERE amendes > 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        $stats['amendes_totales'] = $result['total'] ?? 0;
        
        // Nombre total d'étudiants
        $query = "SELECT COUNT(*) as total FROM etudiant WHERE actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_etudiants'] = $stmt->fetch()['total'];
        
        // Nombre total de livres
        $query = "SELECT COUNT(*) as total FROM livre WHERE actif = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_livres'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}
