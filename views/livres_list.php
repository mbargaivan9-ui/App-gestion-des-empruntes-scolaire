<?php
require_once '../includes/header.php';
require_once '../controllers/LivreController.php';

$livre_ctrl = new LivreController();
$livres = $livre_ctrl->getAllLivres();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-book"></i> Liste des Livres
        </h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="add.php" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Ajouter un Livre
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
                       data-search="true" data-target="bookTable"
                       placeholder="Rechercher un livre...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="bookTable">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>ISBN</th>
                        <th>Éditeur</th>
                        <th>Copies</th>
                        <th>Disponibles</th>
                        <th>Édition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td>
                            <strong><?php echo $livre['titre']; ?></strong>
                        </td>
                        <td><?php echo $livre['auteur']; ?></td>
                        <td>
                            <code><?php echo $livre['isbn'] ?? '-'; ?></code>
                        </td>
                        <td><?php echo $livre['editeur']; ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $livre['nombre_copies']; ?></span>
                        </td>
                        <td>
                            <?php if ($livre['nombre_disponibles'] > 0): ?>
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> <?php echo $livre['nombre_disponibles']; ?>
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger">
                                <i class="fas fa-times"></i> Indisponible
                            </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($livre['date_edition'])); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="view.php?id=<?php echo $livre['code_livre']; ?>" 
                                   class="btn btn-info" title="Voir" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit.php?id=<?php echo $livre['code_livre']; ?>" 
                                   class="btn btn-warning" title="Modifier" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $livre['code_livre']; ?>" 
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
            Total: <strong><?php echo count($livres); ?></strong> livre(s)
        </small>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
