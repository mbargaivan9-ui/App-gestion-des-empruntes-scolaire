<?php
require_once '../includes/header.php';
require_once '../controllers/EtudiantController.php';

$etudiant_ctrl = new EtudiantController();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => trim($_POST['nom'] ?? ''),
        'prenom' => trim($_POST['prenom'] ?? ''),
        'adresse' => trim($_POST['adresse'] ?? ''),
        'classe' => trim($_POST['classe'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'telephone' => trim($_POST['telephone'] ?? '')
    ];
    
    // Validation
    if (empty($data['nom']) || empty($data['prenom']) || empty($data['classe'])) {
        $error = 'Veuillez remplir les champs obligatoires';
    } else {
        $result = $etudiant_ctrl->addEtudiant($data);
        if ($result['success']) {
            $success = $result['message'];
            ob_end_clean();
            header('Location: list.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-6 fw-bold">
            <i class="fas fa-user-plus"></i> Ajouter un Étudiant
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" 
                                   required placeholder="Ex: Dupont">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   required placeholder="Ex: Jean">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Ex: jean.dupont@univ.fr">
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" 
                               placeholder="Ex: 0601020304">
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse" 
                                  rows="3" placeholder="Adresse complète"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="classe" class="form-label">Classe <span class="text-danger">*</span></label>
                        <select class="form-control" id="classe" name="classe" required>
                            <option value="">-- Sélectionner une classe --</option>
                            <option value="L1 Informatique">L1 Informatique</option>
                            <option value="L2 Informatique">L2 Informatique</option>
                            <option value="L3 Informatique">L3 Informatique</option>
                            <option value="M1 Informatique">M1 Informatique</option>
                            <option value="M2 Informatique">M2 Informatique</option>
                        </select>
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
