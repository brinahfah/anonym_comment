<?php
require ("../../Modele/db_connexion.php");
session_start();

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['admin'])) {
    die("Accès refusé.");
}

// Vérifier qu'on a bien un POST avec id_comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comment'])) {
    $id_comment = (int) $_POST['id_comment'];

    // Vérifier que le commentaire existe
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS cnt
        FROM commentaires
        WHERE id_comment = ?
    ");
    $stmt->execute([$id_comment]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($row) || $row['cnt'] == 0) {
        // Le commentaire n'existe pas
        http_response_code(403);
        die("Requête invalide ou accès refusé.");
    }

    // Suppression du commentaire
    $del = $pdo->prepare("DELETE FROM commentaires WHERE id_comment = ?");
    $del->execute([$id_comment]);

    // Redirection vers la page admin
    header('Location: ../admin_part/vue_comment.php');
    exit;
} else {
    http_response_code(400);
    die("Requête invalide.");
}