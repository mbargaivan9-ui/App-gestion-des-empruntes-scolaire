<?php
require_once '../includes/header.php';
require_once '../controllers/LivreController.php';

$livre_ctrl = new LivreController();
$error = '';
$success = '';

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$code_livre = $_GET['id'];
$livre = $livre_ctrl->getLivreById($code_livre);

if (!$livre) {
    header('Location: list.php');
    exit;
}

// Traiter le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre' => trim($_POST['titre'] ?? ''),
        'auteur' => trim($_POST['auteur'] ?? ''),
        'date_edition' => $_POST['date_edition'] ?? null,
        'editeur' => trim($_POST['editeur'] ?? ''),
        'isbn' => trim($_POST['isbn'] ?? ''),
        'nombre_copies' => (int)($_POST['nombre_copies'] ?? 1),
        'description' => trim($_POST['description'] ?? '')
    ];
    
    // Validation
    if (empty($data['titre']) || empty($data['auteur'])) {
        $error = 'Veuillez remplir les champs obligatoires';
    } else {
        $file = $_FILES['couverture'] ?? null;
        $result = $livre_ctrl->updateLivre($code_livre, $data, $file);
        
        if ($result['success']) {
            $success = $result['message'];
            $livre = $livre_ctrl->getLivreById($code_livre);
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
            <i class="fas fa-edit"></i> Modifier un Livre
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
                <form method="POST" action="" enctype="multipart/form-data" data-validate="true">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titre" name="titre" 
                                   value="<?php echo htmlspecialchars($livre['titre']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="auteur" class="form-label">Auteur <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="auteur" name="auteur" 
                                   value="<?php echo htmlspecialchars($livre['auteur']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?php echo htmlspecialchars($livre['isbn'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editeur" class="form-label">Éditeur</label>
                            <input type="text" class="form-control" id="editeur" name="editeur" 
                                   value="<?php echo htmlspecialchars($livre['editeur'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_edition" class="form-label">Date d'édition</label>
                            <input type="date" class="form-control" id="date_edition" name="date_edition" 
                                   value="<?php echo $livre['date_edition']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre_copies" class="form-label">Nombre de copies</label>
                            <input type="number" class="form-control" id="nombre_copies" name="nombre_copies" 
                                   min="1" value="<?php echo $livre['nombre_copies']; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4"><?php echo htmlspecialchars($livre['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="couverture" class="form-label">Couverture (Image)</label>
                        <?php if ($livre['couverture']): ?>
                        <div class="mb-2">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($livre['couverture']); ?>" 
                                 alt="Couverture" style="max-width: 150px; border-radius: 8px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="couverture" name="couverture" 
                               accept="image/*">
                        <small class="text-muted">Format accepté: JPG, PNG, GIF (Max 5 MB)</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
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
