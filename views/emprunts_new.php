<?php
require_once '../includes/header.php';
require_once '../controllers/EmpruntController.php';
require_once '../controllers/EtudiantController.php';
require_once '../controllers/LivreController.php';

$emprunt_ctrl = new EmpruntController();
$etudiant_ctrl = new EtudiantController();
$livre_ctrl = new LivreController();

$etudiants = $etudiant_ctrl->getAllEtudiants();
$livres = $livre_ctrl->getLivresDisponibles();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_etudiant = (int)($_POST['code_etudiant'] ?? 0);
    $code_livre = (int)($_POST['code_livre'] ?? 0);
    $jours_emprunt = (int)($_POST['jours_emprunt'] ?? 14);
    
    if ($code_etudiant === 0 || $code_livre === 0) {
        $error = 'Veuillez sélectionner un étudiant et un livre';
    } else {
        $result = $emprunt_ctrl->createEmprunt($code_etudiant, $code_livre, $jours_emprunt);
        if ($result['success']) {
            $success = $result['message'];
            header('refresh:2;url=list.php');
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-plus-circle"></i> Nouvel Emprunt
        </h1>
    </div>
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

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="" data-validate="true">
                    <div class="mb-3">
                        <label for="code_etudiant" class="form-label">
                            <i class="fas fa-user"></i> Étudiant <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="code_etudiant" name="code_etudiant" required>
                            <option value="">-- Sélectionner un étudiant --</option>
                            <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?php echo $etudiant['code_etudiant']; ?>">
                                <?php echo $etudiant['nom'] . ' ' . $etudiant['prenom']; ?> 
                                (<?php echo $etudiant['classe']; ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="code_livre" class="form-label">
                            <i class="fas fa-book"></i> Livre <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="code_livre" name="code_livre" required>
                            <option value="">-- Sélectionner un livre --</option>
                            <?php foreach ($livres as $livre): ?>
                            <option value="<?php echo $livre['code_livre']; ?>">
                                <?php echo $livre['titre']; ?> - <?php echo $livre['auteur']; ?> 
                                (<?php echo $livre['nombre_disponibles']; ?> disponible(s))
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jours_emprunt" class="form-label">
                            <i class="fas fa-calendar"></i> Durée de l'Emprunt (jours)
                        </label>
                        <input type="number" class="form-control" id="jours_emprunt" name="jours_emprunt" 
                               value="14" min="1" max="60" required>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Durée par défaut: 14 jours
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Information:</strong> Une amende de 0.50€ par jour de retard sera appliquée.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Confirmer l'Emprunt
                        </button>
                        <a href="list.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
