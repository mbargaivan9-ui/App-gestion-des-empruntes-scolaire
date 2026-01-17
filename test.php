<?php
/**
 * Fichier de Test - Biblio App
 * À supprimer après vérification
 */

echo "<h1>Test Biblio App</h1>";

// Test 1: Vérifier la connexion à la base de données
echo "<h2>Test 1: Connexion à la Base de Données</h2>";
try {
    require_once 'config/Database.php';
    $db = new Database();
    $pdo = $db->connect();
    echo "<p style='color: green;'><strong>✓ Connexion à la base de données réussie!</strong></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>✗ Erreur: " . htmlspecialchars($e->getMessage()) . "</strong></p>";
}

// Test 2: Vérifier les sessions
echo "<h2>Test 2: Sessions PHP</h2>";
session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p style='color: green;'><strong>✓ Sessions activées!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Sessions désactivées!</strong></p>";
}

// Test 3: Vérifier les fichiers d'inclusion
echo "<h2>Test 3: Fichiers d'Inclusion</h2>";
$files = [
    'includes/Auth.php' => 'Authentification',
    'includes/header.php' => 'En-tête',
    'includes/footer.php' => 'Pied de page',
    'controllers/EtudiantController.php' => 'Contrôleur Étudiant',
    'controllers/LivreController.php' => 'Contrôleur Livre',
    'controllers/EmpruntController.php' => 'Contrôleur Emprunt',
];

foreach ($files as $file => $name) {
    if (file_exists($file)) {
        echo "<p style='color: green;'><strong>✓</strong> $name ($file)</p>";
    } else {
        echo "<p style='color: red;'><strong>✗</strong> $name ($file) - MANQUANT!</p>";
    }
}

// Test 4: Vérifier les répertoires
echo "<h2>Test 4: Répertoires</h2>";
$directories = [
    'assets/uploads' => 'Uploads',
    'database' => 'Base de Données',
    'views' => 'Vues',
];

foreach ($directories as $dir => $name) {
    if (is_dir($dir)) {
        echo "<p style='color: green;'><strong>✓</strong> $name ($dir)</p>";
    } else {
        echo "<p style='color: red;'><strong>✗</strong> $name ($dir) - MANQUANT!</p>";
    }
}

echo "<hr>";
echo "<p>Visitez <a href='index.php'>la page d'accueil</a> ou <a href='login.php'>la page de connexion</a></p>";
?>
