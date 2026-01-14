<?php
require_once 'includes/header.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold">
            <i class="fas fa-user-circle"></i> Mon Profil
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient">
                <h5 class="mb-0">Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Nom d'utilisateur</label>
                    <p class="fs-5"><?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <p class="fs-5"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Rôle</label>
                    <p class="fs-5">
                        <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient">
                <h5 class="mb-0">Sécurité</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Gérez vos paramètres de sécurité</p>
                <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-lock"></i> Changer le mot de passe
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer le mot de passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer votre mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ancien_motdepasse" class="form-label">Ancien mot de passe</label>
                        <input type="password" class="form-control" id="ancien_motdepasse" name="ancien_motdepasse" required>
                    </div>
                    <div class="mb-3">
                        <label for="nouveau_motdepasse" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="nouveau_motdepasse" name="nouveau_motdepasse" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmer_motdepasse" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirmer_motdepasse" name="confirmer_motdepasse" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
