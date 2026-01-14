<?php
session_start();

require_once 'includes/Auth.php';

$auth = new Auth();

// Si connecté, rediriger vers le dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblio App - Plateforme de Gestion des Emprunts</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -30px;
            left: 100px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: slideInDown 0.8s ease-out;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.95;
            animation: slideInUp 0.8s ease-out 0.2s both;
        }

        .hero-buttons {
            animation: slideInUp 0.8s ease-out 0.4s both;
        }

        .hero-buttons .btn {
            padding: 12px 30px;
            font-size: 1.1rem;
            margin-right: 15px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: white;
            color: #667eea;
            border: none;
        }

        .btn-primary:hover {
            background-color: #f0f0f0;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-light {
            border: 2px solid white;
        }

        .btn-outline-light:hover {
            background-color: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border-top: 4px solid #667eea;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 30px 0;
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 50px 0;
                min-height: auto;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .hero-buttons .btn {
                display: block;
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }

            .cta-section h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 hero-content">
                    <h1><i class="fas fa-book"></i> Biblio App</h1>
                    <p>Plateforme complète de gestion des emprunts de livres pour votre bibliothèque universitaire</p>
                    <div class="hero-buttons">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                        <a href="register.php" class="btn btn-outline-light">
                            <i class="fas fa-user-plus"></i> S'inscrire
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div style="font-size: 8rem; text-align: center;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 style="font-size: 2.5rem; margin-bottom: 20px; color: #333;">
                        <i class="fas fa-star" style="color: #667eea;"></i> Fonctionnalités Principales
                    </h2>
                    <p style="font-size: 1.1rem; color: #666;">
                        Découvrez tout ce que Biblio App peut faire pour votre bibliothèque
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Gestion des Étudiants</h3>
                        <p>Gérez facilement vos étudiants : ajouter, modifier, supprimer et consulter les profils</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3>Gestion des Livres</h3>
                        <p>Cataloguez tous vos livres avec titre, auteur, ISBN et couverture</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3>Gestion des Emprunts</h3>
                        <p>Enregistrez les emprunts et retours, gérez les retards et les amendes</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3>Tableau de Bord</h3>
                        <p>Consultez les statistiques et indicateurs clés en temps réel</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Sécurité</h3>
                        <p>Authentification sécurisée avec mots de passe hashés et requêtes préparées</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Responsive Design</h3>
                        <p>Accédez à l'application depuis n'importe quel appareil (mobile, tablette, PC)</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à commencer ?</h2>
            <p style="font-size: 1.1rem; margin-bottom: 30px;">
                Créez un compte gratuit et commencez à utiliser Biblio App dès maintenant
            </p>
            <div>
                <a href="register.php" class="btn btn-light" style="padding: 12px 40px; font-size: 1.1rem;">
                    <i class="fas fa-user-plus"></i> S'inscrire Maintenant
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 Biblio App - Plateforme de Gestion des Emprunts. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
