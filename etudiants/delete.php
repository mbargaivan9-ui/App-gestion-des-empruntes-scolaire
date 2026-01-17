<?php
require_once '../includes/header.php';
require_once '../controllers/EtudiantController.php';

$etudiant_ctrl = new EtudiantController();
$code_etudiant = (int)($_GET['id'] ?? 0);

if ($code_etudiant === 0) {
    header('Location: list.php');
    exit;
}

$result = $etudiant_ctrl->deleteEtudiant($code_etudiant);

if ($result['success']) {
    header('Location: list.php');
} else {
    header('Location: list.php?error=' . urlencode($result['message']));
}
