<?php
require_once '../includes/header.php';
require_once '../controllers/EtudiantController.php';

$etudiant_ctrl = new EtudiantController();
$error = '';
$success = '';

// Vérifier si l'ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$code_etudiant = $_GET['id'];
$etudiant = $etudiant_ctrl->getEtudiantById($code_etudiant);

if (!$etudiant) {
    header('Location: list.php');
    exit;
}

// Traiter le formulaire
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
        $result = $etudiant_ctrl->updateEtudiant($code_etudiant, $data);
        if ($result['success']) {
            $success = $result['message'];
            $etudiant = $etudiant_ctrl->getEtudiantById($code_etudiant);
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="row mb-4">
    <div class="col">
        <a href="list.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient">
        <h3 class="mb-0">
            <i class="fas fa-user-edit"></i> Modifier l'Étudiant
        </h3>
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
        
        <form method="POST" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="nom">
                        <i class="fas fa-user"></i> Nom *
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" required
                           value="<?php echo htmlspecialchars($etudiant['nom']); ?>"
                           placeholder="Nom de l'étudiant">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="prenom">
                        <i class="fas fa-user"></i> Prénom *
                    </label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required
                           value="<?php echo htmlspecialchars($etudiant['prenom']); ?>"
                           placeholder="Prénom de l'étudiant">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo htmlspecialchars($etudiant['email']); ?>"
                           placeholder="email@example.com">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="telephone">
                        <i class="fas fa-phone"></i> Téléphone
                    </label>
                    <input type="tel" class="form-control" id="telephone" name="telephone"
                           value="<?php echo htmlspecialchars($etudiant['telephone']); ?>"
                           placeholder="+33 6 XX XX XX XX">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="classe">
                        <i class="fas fa-graduation-cap"></i> Classe *
                    </label>
                    <select class="form-select" id="classe" name="classe" required>
                        <option value="">-- Sélectionnez une classe --</option>
                        <option value="L1 Informatique" <?php echo $etudiant['classe'] === 'L1 Informatique' ? 'selected' : ''; ?>>L1 Informatique</option>
                        <option value="L2 Informatique" <?php echo $etudiant['classe'] === 'L2 Informatique' ? 'selected' : ''; ?>>L2 Informatique</option>
                        <option value="L3 Informatique" <?php echo $etudiant['classe'] === 'L3 Informatique' ? 'selected' : ''; ?>>L3 Informatique</option>
                        <option value="M1 Informatique" <?php echo $etudiant['classe'] === 'M1 Informatique' ? 'selected' : ''; ?>>M1 Informatique</option>
                        <option value="M2 Informatique" <?php echo $etudiant['classe'] === 'M2 Informatique' ? 'selected' : ''; ?>>M2 Informatique</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="adresse">
                        <i class="fas fa-map-marker-alt"></i> Adresse
                    </label>
                    <input type="text" class="form-control" id="adresse" name="adresse"
                           value="<?php echo htmlspecialchars($etudiant['adresse']); ?>"
                           placeholder="123 rue de Paris">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
                <a href="list.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
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
                                   value="<?php echo $etudiant['nom']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="prenom" name="prenom" 
                                   value="<?php echo $etudiant['prenom']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $etudiant['email']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" 
                               value="<?php echo $etudiant['telephone']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse" rows="3"><?php echo $etudiant['adresse']; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="classe" class="form-label">Classe <span class="text-danger">*</span></label>
                        <select class="form-control" id="classe" name="classe" required>
                            <option value="<?php echo $etudiant['classe']; ?>"><?php echo $etudiant['classe']; ?></option>
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
