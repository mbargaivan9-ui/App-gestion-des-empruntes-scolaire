<?php
/**
 * Configuration de la Base de Données
 * Fichier: config/Database.php
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'biblio';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    private $pdo;
    private $stmt;
    
    /**
     * Connexion à la base de données
     */
    public function connect() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=' . $this->charset;
        
        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            die('Erreur de connexion: ' . $e->getMessage());
        }
    }
    
    /**
     * Préparer une requête
     */
    public function prepare($query) {
        $this->stmt = $this->pdo->prepare($query);
        return $this;
    }
    
    /**
     * Lier les paramètres
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Exécuter la requête
     */
    public function execute() {
        return $this->stmt->execute();
    }
    
    /**
     * Récupérer tous les résultats
     */
    public function getAll() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Récupérer une seule ligne
     */
    public function getOne() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Obtenir le nombre de lignes
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Obtenir le dernier ID inséré
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
