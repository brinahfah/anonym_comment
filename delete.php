<?php
require 'BDD/db_connexion.php';
session_start();

// Vérifie que l'utilisateur a le droit (exemple avec id_filiere)
$filiere_id = $_SESSION['id_filiere'] ?? null;
if (!$filiere_id) {
    die("Accès refusé.");
}

// Vérifier qu'on a bien un POST avec id_comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comment'])) {
    $id_comment = (int) $_POST['id_comment'];

    // 1) Vérifier que le commentaire existe et appartient à un cours de la filière.
    // On utilise une requête SELECT simple qui fonctionne sur MySQL/Postgres.
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS cnt
        FROM commentaires c
        JOIN cours co ON c.id_cour = co.id_cour
        WHERE c.id_comment = ? AND co.id_filiere = ?
    ");
    $stmt->execute([$id_comment, $filiere_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($row) || $row['cnt'] == 0) {
        // L'utilisateur n'a pas le droit de supprimer ce commentaire (ou il n'existe pas)
        http_response_code(403);
        die("Requête invalide ou accès refusé.");
    }

    // 2) Suppression
    $del = $pdo->prepare("DELETE FROM commentaires WHERE id_comment = ?");
    $del->execute([$id_comment]);

    // Redirection (changer la cible si besoin)
    header('Location:admin.php ');
    exit;
} else {
    http_response_code(400);
    die("Requête invalide.");
}
