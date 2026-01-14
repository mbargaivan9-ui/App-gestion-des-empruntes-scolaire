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
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $mot_de_passe_confirm = $_POST['mot_de_passe_confirm'] ?? '';
    
    // Validation
    if (empty($nom_utilisateur) || empty($email) || empty($mot_de_passe) || empty($mot_de_passe_confirm)) {
        $error = 'Veuillez remplir tous les champs';
    } elseif (strlen($mot_de_passe) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } elseif ($mot_de_passe !== $mot_de_passe_confirm) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($nom_utilisateur) < 3) {
        $error = 'Le nom d\'utilisateur doit contenir au moins 3 caractères';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Veuillez entrer une adresse email valide';
    } else {
        $result = $auth->register($nom_utilisateur, $email, $mot_de_passe);
        if ($result['success']) {
            $success = 'Inscription réussie! Veuillez vous connecter.';
            // Rediriger vers la connexion après 3 secondes
            header('Refresh: 3; URL=login.php');
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Biblio App</title>
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
        
        .container-auth {
            width: 100%;
            max-width: 450px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border: none;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 24px;
        }
        
        .card-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #fff5f5;
            color: #c53030;
            border-left: 4px solid #c53030;
        }
        
        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-left: 4px solid #16a34a;
        }
        
        .form-text {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .login-link p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .password-info {
            background-color: #f8f9fa;
            border-left: 3px solid #667eea;
            padding: 12px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }
        
        .icon-input {
            position: relative;
        }
        
        .icon-input i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container-auth">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-plus fa-2x"></i>
                <h3>Inscription</h3>
                <p>Créez votre compte Biblio App</p>
            </div>
            
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" novalidate id="registerForm">
                    <div class="form-group">
                        <label class="form-label" for="nom_utilisateur">
                            <i class="fas fa-user"></i> Nom d'utilisateur
                        </label>
                        <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" 
                               required value="<?php echo htmlspecialchars($_POST['nom_utilisateur'] ?? ''); ?>"
                               placeholder="Choisissez un nom d'utilisateur">
                        <small class="form-text">Minimum 3 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i> Adresse email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               placeholder="votre.email@exemple.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="mot_de_passe">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" 
                               required placeholder="••••••••">
                        <small class="form-text">Minimum 6 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="mot_de_passe_confirm">
                            <i class="fas fa-lock"></i> Confirmer le mot de passe
                        </label>
                        <input type="password" class="form-control" id="mot_de_passe_confirm" name="mot_de_passe_confirm" 
                               required placeholder="••••••••">
                    </div>
                    
                    <div class="password-info">
                        <strong>Exigences du mot de passe:</strong>
                        <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                            <li>Minimum 6 caractères</li>
                            <li>Peut contenir lettres, chiffres et caractères spéciaux</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-check"></i> S'inscrire
                    </button>
                </form>
                
                <div class="login-link">
                    <p>Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation côté client
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nom_utilisateur = document.getElementById('nom_utilisateur').value.trim();
            const email = document.getElementById('email').value.trim();
            const mot_de_passe = document.getElementById('mot_de_passe').value;
            const mot_de_passe_confirm = document.getElementById('mot_de_passe_confirm').value;
            
            if (nom_utilisateur.length < 3) {
                alert('Le nom d\'utilisateur doit contenir au moins 3 caractères');
                e.preventDefault();
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse email valide');
                e.preventDefault();
                return false;
            }
            
            if (mot_de_passe.length < 6) {
                alert('Le mot de passe doit contenir au moins 6 caractères');
                e.preventDefault();
                return false;
            }
            
            if (mot_de_passe !== mot_de_passe_confirm) {
                alert('Les mots de passe ne correspondent pas');
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
