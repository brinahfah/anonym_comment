<?php
require 'BDD/db_connexion.php';
session_start();

if (isset($_GET['id_cour'])) {
    $id_cour = (int) $_GET['id_cour'];

    $stmt = $pdo->prepare("DELETE FROM cours WHERE id_cour = ?");
    $stmt->execute([$id_cour]);

    header('Location: cours.php');
    exit;
} else {
    header('Location: cours.php');
    exit;
}
