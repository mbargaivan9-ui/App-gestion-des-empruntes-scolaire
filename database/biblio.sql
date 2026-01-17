-- ============================================
-- BASE DE DONNÉES BIBLIO - Gestion des Emprunts
-- ============================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS biblio;
USE biblio;

-- ============================================
-- TABLE UTILISATEUR (Authentification)
-- ============================================
CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_utilisateur VARCHAR(50) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE ETUDIANT
-- ============================================
CREATE TABLE etudiant (
    code_etudiant INT PRIMARY KEY AUTO_INCREMENT,
    nom TEXT(100) NOT NULL,
    prenom TEXT(100) NOT NULL,
    adresse TEXT(255),
    classe TEXT(50) NOT NULL,
    email TEXT(100),
    telephone VARCHAR(20),
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE LIVRE
-- ============================================
CREATE TABLE livre (
    code_livre INT PRIMARY KEY AUTO_INCREMENT,
    titre TEXT(255) NOT NULL,
    auteur TEXT(150) NOT NULL,
    date_edition DATE,
    editeur VARCHAR(150),
    isbn VARCHAR(20) UNIQUE,
    nombre_copies INT DEFAULT 1,
    nombre_disponibles INT DEFAULT 1,
    description LONGTEXT,
    couverture VARCHAR(255),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actif TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE EMPRUNT
-- ============================================
CREATE TABLE emprunt (
    code_emprunt INT PRIMARY KEY AUTO_INCREMENT,
    code_etudiant INT,
    code_livre INT,
    date_emprunt DATE NOT NULL,
    date_retour_prevue DATE NOT NULL,
    date_retour_reelle DATE,
    statut ENUM('en_cours', 'retourne', 'perdu') DEFAULT 'en_cours',
    amendes DECIMAL(10, 2) DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (code_etudiant) REFERENCES etudiant(code_etudiant) ON DELETE CASCADE,
    FOREIGN KEY (code_livre) REFERENCES livre(code_livre) ON DELETE CASCADE,
    INDEX idx_etudiant (code_etudiant),
    INDEX idx_livre (code_livre),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DONNÉES DE TESTE
-- ============================================

-- Utilisateurs par défaut
INSERT INTO utilisateur (nom_utilisateur, mot_de_passe, email, role) VALUES
('admin', '$2y$10$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5YmMxSUGyFcom', 'admin@biblio.com', 'admin'),
('user1', '$2y$10$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5YmMxSUGyFcom', 'user1@biblio.com', 'user');

-- Étudiants
INSERT INTO etudiant (nom, prenom, adresse, classe, email, telephone) VALUES
('Dupont', 'Jean', '123 rue de Paris', 'L2 Informatique', 'jean.dupont@univ.fr', '0601020304'),
('Martin', 'Marie', '456 avenue Lyon', 'L2 Informatique', 'marie.martin@univ.fr', '0605060708'),
('Bernard', 'Pierre', '789 chemin Marseille', 'L1 Informatique', 'pierre.bernard@univ.fr', '0609101112'),
('Laurent', 'Sophie', '321 rue Nice', 'L3 Informatique', 'sophie.laurent@univ.fr', '0613141516');

-- Livres
INSERT INTO livre (titre, auteur, date_edition, editeur, isbn, nombre_copies, nombre_disponibles, description) VALUES
('PHP Moderne', 'John Doe', '2022-01-15', 'Tech Press', '978-1234567890', 3, 2, 'Guide complet du PHP moderne et de ses meilleures pratiques.'),
('MySQL Avancé', 'Jane Smith', '2021-06-20', 'Data Books', '978-0987654321', 2, 1, 'Masteriser MySQL avec des techniques avancées.'),
('JavaScript ES6+', 'Bob Johnson', '2023-03-10', 'Web Dev', '978-1111222233', 4, 3, 'Apprendre JavaScript ES6 et au-delà.'),
('Design Patterns', 'David Brown', '2020-11-05', 'Code Masters', '978-4444555566', 2, 2, 'Les design patterns essentiels en développement.'),
('HTML5 et CSS3', 'Emma Wilson', '2022-09-12', 'Web Books', '978-7777888899', 5, 4, 'Web moderne avec HTML5 et CSS3.');

-- ============================================
-- PROCÉDURES STOCKÉES
-- ============================================

-- Procédure pour enregistrer un emprunt
DELIMITER $$
CREATE PROCEDURE enregistrer_emprunt(
    IN p_code_etudiant INT,
    IN p_code_livre INT,
    IN p_date_retour_prevue DATE
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'ERREUR' AS resultat;
    END;
    
    START TRANSACTION;
    
    -- Vérifier la disponibilité
    IF (SELECT nombre_disponibles FROM livre WHERE code_livre = p_code_livre) > 0 THEN
        -- Insérer l'emprunt
        INSERT INTO emprunt (code_etudiant, code_livre, date_emprunt, date_retour_prevue)
        VALUES (p_code_etudiant, p_code_livre, CURDATE(), p_date_retour_prevue);
        
        -- Mettre à jour la disponibilité
        UPDATE livre 
        SET nombre_disponibles = nombre_disponibles - 1 
        WHERE code_livre = p_code_livre;
        
        COMMIT;
        SELECT 'SUCCESS' AS resultat;
    ELSE
        ROLLBACK;
        SELECT 'INDISPONIBLE' AS resultat;
    END IF;
END$$
DELIMITER ;

-- Procédure pour enregistrer un retour
DELIMITER $$
CREATE PROCEDURE enregistrer_retour(
    IN p_code_emprunt INT
)
BEGIN
    DECLARE v_code_livre INT;
    DECLARE v_code_etudiant INT;
    DECLARE v_jours_retard INT;
    DECLARE v_amendes DECIMAL(10, 2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'ERREUR' AS resultat;
    END;
    
    START TRANSACTION;
    
    -- Récupérer les informations
    SELECT code_livre, code_etudiant INTO v_code_livre, v_code_etudiant
    FROM emprunt 
    WHERE code_emprunt = p_code_emprunt;
    
    -- Calculer les amendes (0.50€ par jour de retard)
    SET v_jours_retard = DATEDIFF(CURDATE(), (SELECT date_retour_prevue FROM emprunt WHERE code_emprunt = p_code_emprunt));
    
    IF v_jours_retard > 0 THEN
        SET v_amendes = v_jours_retard * 0.50;
    ELSE
        SET v_amendes = 0;
    END IF;
    
    -- Mettre à jour l'emprunt
    UPDATE emprunt 
    SET statut = 'retourne', 
        date_retour_reelle = CURDATE(),
        amendes = v_amendes
    WHERE code_emprunt = p_code_emprunt;
    
    -- Remettre à jour la disponibilité
    UPDATE livre 
    SET nombre_disponibles = nombre_disponibles + 1 
    WHERE code_livre = v_code_livre;
    
    COMMIT;
    SELECT 'SUCCESS' AS resultat;
END$$
DELIMITER ;

-- ============================================
-- INDEX SUPPLÉMENTAIRES POUR PERFORMANCE
-- ============================================
CREATE INDEX idx_livre_actif ON livre(actif);
CREATE INDEX idx_etudiant_actif ON etudiant(actif);
CREATE INDEX idx_emprunt_date ON emprunt(date_emprunt);
CREATE INDEX idx_utilisateur_actif ON utilisateur(actif);
