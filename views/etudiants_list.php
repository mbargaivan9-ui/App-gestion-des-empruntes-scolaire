<?php
require_once '../includes/header.php';
require_once '../controllers/EtudiantController.php';

$etudiant_ctrl = new EtudiantController();
$etudiants = $etudiant_ctrl->getAllEtudiants();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-users"></i> Liste des Étudiants
        </h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Ajouter un Étudiant
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtrer</h5>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" 
                       data-search="true" data-target="studentTable"
                       placeholder="Rechercher un étudiant...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentTable">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom & Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Téléphone</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td>
                            <span class="badge bg-info">#<?php echo $etudiant['code_etudiant']; ?></span>
                        </td>
                        <td>
                            <strong><?php echo ucfirst($etudiant['nom']) . ' ' . ucfirst($etudiant['prenom']); ?></strong>
                        </td>
                        <td>
                            <a href="mailto:<?php echo $etudiant['email']; ?>">
                                <i class="fas fa-envelope"></i> <?php echo $etudiant['email']; ?>
                            </a>
                        </td>
                        <td><?php echo $etudiant['classe']; ?></td>
                        <td>
                            <a href="tel:<?php echo $etudiant['telephone']; ?>">
                                <i class="fas fa-phone"></i> <?php echo $etudiant['telephone']; ?>
                            </a>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($etudiant['date_inscription'])); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="view.php?id=<?php echo $etudiant['code_etudiant']; ?>" 
                                   class="btn btn-info" title="Voir" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit.php?id=<?php echo $etudiant['code_etudiant']; ?>" 
                                   class="btn btn-warning" title="Modifier" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $etudiant['code_etudiant']; ?>" 
                                   class="btn btn-danger" title="Supprimer" data-bs-toggle="tooltip"
                                   onclick="return confirm('Êtes-vous sûr?')">
                                    <i class="fas fa-trash"></i>
                                </a>
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
            Total: <strong><?php echo count($etudiants); ?></strong> étudiant(s)
        </small>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
