<?php
require_once '../includes/header.php';
require_once '../controllers/EmpruntController.php';

$emprunt_ctrl = new EmpruntController();

$code_emprunt = (int)($_GET['id'] ?? 0);

if ($code_emprunt === 0) {
    header('Location: list.php');
    exit;
}

$emprunt = $emprunt_ctrl->getEmpruntById($code_emprunt);

if (!$emprunt) {
    header('Location: list.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $emprunt_ctrl->enregistrerRetour($code_emprunt);
    if ($result['success']) {
        $success = 'Retour enregistré avec succès!';
        if ($result['amendes'] > 0) {
            $success .= ' Amendes: ' . number_format($result['amendes'], 2) . '€';
        }
        header('refresh:2;url=list.php');
    } else {
        $error = $result['message'];
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-undo"></i> Enregistrer un Retour
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
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">Détails de l'Emprunt</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Étudiant</h6>
                        <p class="fw-bold">
                            <?php echo $emprunt['nom'] . ' ' . $emprunt['prenom']; ?>
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-envelope"></i> <?php echo $emprunt['email']; ?><br>
                            <i class="fas fa-phone"></i> <?php echo $emprunt['telephone']; ?>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Livre</h6>
                        <p class="fw-bold"><?php echo $emprunt['titre']; ?></p>
                        <small class="text-muted">
                            Auteur: <?php echo $emprunt['auteur']; ?><br>
                            ISBN: <?php echo $emprunt['isbn']; ?>
                        </small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Date d'Emprunt</h6>
                        <p class="fw-bold">
                            <?php echo date('d/m/Y', strtotime($emprunt['date_emprunt'])); ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Retour Prévu</h6>
                        <p class="fw-bold">
                            <?php echo date('d/m/Y', strtotime($emprunt['date_retour_prevue'])); ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Statut</h6>
                        <p class="fw-bold">
                            <?php if ($emprunt['statut'] === 'en_cours'): ?>
                            <span class="badge bg-success">En cours</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Retourné</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <?php
                $date_retour_prevue = new DateTime($emprunt['date_retour_prevue']);
                $date_aujourd_hui = new DateTime();
                $jours_retard = $date_aujourd_hui->diff($date_retour_prevue)->days;
                
                if ($jours_retard > 0):
                ?>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention:</strong> Cet emprunt est en retard de <strong><?php echo $jours_retard; ?></strong> jour(s).
                    <br>
                    <strong>Amendes à appliquer:</strong> <strong class="text-danger"><?php echo number_format($jours_retard * 0.50, 2); ?>€</strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <form method="POST" action="">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle"></i> 
                        Cliquez sur le bouton ci-dessous pour confirmer le retour de ce livre.
                    </p>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Confirmer le Retour
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
