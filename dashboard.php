<?php
require_once 'includes/header.php';
require_once 'controllers/EmpruntController.php';

$emprunt_ctrl = new EmpruntController();
$stats = $emprunt_ctrl->getStatistiques();
$emprunts_actifs = $emprunt_ctrl->getEmpruntActifs();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold">
            <i class="fas fa-chart-line"></i> Tableau de Bord
        </h1>
        <p class="text-muted">Bienvenue <?php echo ucfirst($_SESSION['username']); ?></p>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Emprunts Actifs</p>
                        <h3 class="text-primary fw-bold"><?php echo $stats['emprunts_actifs']; ?></h3>
                    </div>
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">En Retard</p>
                        <h3 class="text-danger fw-bold"><?php echo $stats['emprunts_en_retard']; ?></h3>
                    </div>
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Amendes Totales</p>
                        <h3 class="text-success fw-bold"><?php echo number_format($stats['amendes_totales'], 2); ?>€</h3>
                    </div>
                    <div class="stat-icon bg-success">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Livres Disponibles</p>
                        <h3 class="text-info fw-bold"><?php echo $stats['total_livres']; ?></h3>
                    </div>
                    <div class="stat-icon bg-info">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emprunts à retourner prochainement -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Emprunts à retourner prochainement
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Livre</th>
                                <th>Date d'Emprunt</th>
                                <th>Retour Prévu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($emprunts_actifs, 0, 10) as $emprunt): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $emprunt['nom'] . ' ' . $emprunt['prenom']; ?></strong><br>
                                    <small class="text-muted"><?php echo $emprunt['classe']; ?></small>
                                </td>
                                <td><?php echo $emprunt['titre']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($emprunt['date_emprunt'])); ?></td>
                                <td>
                                    <strong><?php echo date('d/m/Y', strtotime($emprunt['date_retour_prevue'])); ?></strong>
                                </td>
                                <td>
                                    <?php if ($emprunt['jours_retard'] > 0): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation"></i> <?php echo $emprunt['jours_retard']; ?> jours de retard
                                    </span>
                                    <?php elseif ($emprunt['jours_retard'] <= 2 && $emprunt['jours_retard'] > -3): ?>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-end"></i> Bientôt à rendre
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Normal
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="emprunts/return.php?id=<?php echo $emprunt['code_emprunt']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-undo"></i> Retour
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
