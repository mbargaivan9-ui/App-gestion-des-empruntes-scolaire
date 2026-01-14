<?php
/**
 * Routeur pour les pages des Étudiants
 */

$page = isset($_GET['page']) ? $_GET['page'] : 'list';

switch($page) {
    case 'list':
        require_once 'etudiants_list.php';
        break;
    case 'add':
        require_once 'etudiants_add.php';
        break;
    case 'edit':
        require_once 'etudiants_edit.php';
        break;
    case 'delete':
        require_once 'etudiants_delete.php';
        break;
    case 'view':
        require_once 'etudiants_view.php';
        break;
    default:
        header('Location: list.php');
}
