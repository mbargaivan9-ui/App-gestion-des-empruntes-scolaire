<?php
require_once '../includes/header.php';
require_once '../controllers/LivreController.php';

$livre_ctrl = new LivreController();
$code_livre = (int)($_GET['id'] ?? 0);

if ($code_livre === 0) {
    header('Location: list.php');
    exit;
}

$result = $livre_ctrl->deleteLivre($code_livre);

if ($result['success']) {
    header('Location: list.php');
} else {
    header('Location: list.php?error=' . urlencode($result['message']));
}
?>
