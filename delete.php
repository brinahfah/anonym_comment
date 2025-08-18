<?php
require 'BDD/db_connexion.php';
session_start();

// Vérifie que l'utilisateur a le droit (exemple avec id_filiere)
$filiere_id = $_SESSION['id_filiere'] ?? null;
if (!$filiere_id) {
    die("Accès refusé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cour'])) {
    $id_cour = (int) $_POST['id_cour'];

    // Suppression uniquement si le cours appartient à la filière
    $stmt = $pdo->prepare("DELETE FROM cours WHERE id_cour = ? AND id_filiere = ?");
    $stmt->execute([$id_cour, $filiere_id]);

    // Redirection après suppression
    header('Location: ton_fichier_avec_la_liste.php');
    exit;
} else {
    die("Requête invalide.");
}
