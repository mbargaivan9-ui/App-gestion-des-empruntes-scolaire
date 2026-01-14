<?php
session_start();

require_once 'includes/Auth.php';

$auth = new Auth();
$error = '';
$success = '';

// Si déjà connecté, rediriger
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    
    if (empty($nom_utilisateur) || empty($mot_de_passe)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        if ($auth->login($nom_utilisateur, $mot_de_passe)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Identifiants invalides';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Biblio App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #667eea;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #999;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-group input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            border-color: #667eea;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-book-open"></i> Biblio App</h1>
            <p>Système de Gestion des Emprunts de Livres</p>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom_utilisateur">
                    <i class="fas fa-user"></i> Nom d'utilisateur
                </label>
                <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" 
                       required placeholder="Entrez votre nom d'utilisateur">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">
                    <i class="fas fa-lock"></i> Mot de passe
                </label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" 
                       required placeholder="Entrez votre mot de passe">
            </div>
            
            <button type="submit" class="btn btn-login w-100 text-white">
                <i class="fas fa-sign-in-alt"></i> Se Connecter
            </button>
        </form>
        
        <div class="login-footer">
            <p>Pas encore de compte? <a href="register.php">S'inscrire</a></p>
            <small>
                <strong>Compte de test:</strong><br>
                Utilisateur: <code>admin</code><br>
                Mot de passe: <code>password</code>
            </small>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
