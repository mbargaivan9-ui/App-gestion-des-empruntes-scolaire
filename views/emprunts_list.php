<?php
require_once '../includes/header.php';
require_once '../controllers/EmpruntController.php';

$emprunt_ctrl = new EmpruntController();
$emprunts = $emprunt_ctrl->getAllEmprunts();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-exchange-alt"></i> Gestion des Emprunts
        </h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="new.php" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Nouvel Emprunt
        </a>
    </div>
</div>

<!-- Filtres -->
<div class="row mb-3">
    <div class="col-md-4">
        <input type="text" class="form-control" id="searchInput" 
               data-search="true" data-target="loanTable"
               placeholder="Rechercher...">
    </div>
    <div class="col-md-3">
        <select class="form-control" id="filterStatus">
            <option value="">Tous les statuts</option>
            <option value="en_cours">En cours</option>
            <option value="retourne">Retourné</option>
        </select>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0"><i class="fas fa-list"></i> Tous les Emprunts</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="loanTable">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Emprunt</th>
                        <th>Retour Prévu</th>
                        <th>Retour Effectif</th>
                        <th>Statut</th>
                        <th>Amendes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                    <tr data-status="<?php echo $emprunt['statut']; ?>">
                        <td>
                            <span class="badge bg-info">#<?php echo $emprunt['code_emprunt']; ?></span>
                        </td>
                        <td>
                            <strong><?php echo $emprunt['nom'] . ' ' . $emprunt['prenom']; ?></strong><br>
                            <small class="text-muted"><?php echo $emprunt['classe']; ?></small>
                        </td>
                        <td><?php echo $emprunt['titre']; ?></td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($emprunt['date_emprunt'])); ?>
                        </td>
                        <td>
                            <strong><?php echo date('d/m/Y', strtotime($emprunt['date_retour_prevue'])); ?></strong>
                        </td>
                        <td>
                            <?php echo $emprunt['date_retour_reelle'] ? date('d/m/Y', strtotime($emprunt['date_retour_reelle'])) : '-'; ?>
                        </td>
                        <td>
                            <?php if ($emprunt['statut'] === 'en_cours'): ?>
                                <?php if ($emprunt['jours_retard'] > 0): ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation"></i> <?php echo $emprunt['jours_retard']; ?> jours retard
                                </span>
                                <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> En cours
                                </span>
                                <?php endif; ?>
                            <?php else: ?>
                            <span class="badge bg-secondary">Retourné</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($emprunt['amendes'] > 0): ?>
                            <span class="badge bg-warning text-dark">
                                <?php echo number_format($emprunt['amendes'], 2); ?>€
                            </span>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="view.php?id=<?php echo $emprunt['code_emprunt']; ?>" 
                                   class="btn btn-info" title="Voir" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($emprunt['statut'] === 'en_cours'): ?>
                                <a href="return.php?id=<?php echo $emprunt['code_emprunt']; ?>" 
                                   class="btn btn-success" title="Enregistrer retour" data-bs-toggle="tooltip">
                                    <i class="fas fa-undo"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted">
            Total: <strong><?php echo count($emprunts); ?></strong> emprunt(s)
        </small>
    </div>
</div>

<script>
document.getElementById('filterStatus').addEventListener('change', function() {
    const status = this.value;
    document.querySelectorAll('#loanTable tbody tr').forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
