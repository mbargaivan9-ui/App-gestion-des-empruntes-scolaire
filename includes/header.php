<?php
/**
 * Démarrer la session et inclure l'authentification
 */
ob_start(); // Activer le output buffering
session_start();

require_once __DIR__ . '/../includes/Auth.php';

$auth = new Auth();

// Rediriger vers la connexion si non authentifié
$current_page = basename($_SERVER['PHP_SELF']);
$pages_publiques = ['login.php', 'register.php', 'index.php'];

if (!$auth->isLoggedIn() && !in_array($current_page, $pages_publiques)) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Biblio App - Gestion des Emprunts</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="fas fa-book-open"></i> Biblio App
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if ($auth->isLoggedIn()): ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuEtudiants" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-users"></i> Étudiants
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuEtudiants">
                            <li><a class="dropdown-item" href="etudiants/list.php">Liste des Étudiants</a></li>
                            <li><a class="dropdown-item" href="etudiants/add.php">Ajouter un Étudiant</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuLivres" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-book"></i> Livres
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuLivres">
                            <li><a class="dropdown-item" href="livres/list.php">Liste des Livres</a></li>
                            <li><a class="dropdown-item" href="livres/add.php">Ajouter un Livre</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuEmprunts" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-exchange-alt"></i> Emprunts
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuEmprunts">
                            <li><a class="dropdown-item" href="emprunts/list.php">Tous les Emprunts</a></li>
                            <li><a class="dropdown-item" href="emprunts/new.php">Nouvel Emprunt</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php">Mon Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
                <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Inscription</a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <main class="container-fluid py-4">
