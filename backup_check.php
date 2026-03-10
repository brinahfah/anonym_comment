<?php
// Test simple : vérifier si la connexion fonctionne
require_once "../BDD/db_connexion.php";

try {
    $stmt = $pdo->query("SELECT NOW()");
    echo "Connexion OK - Base restaurable.";
} catch (Exception $e) {
    echo "ERREUR : Impossible de vérifier la base. " . $e->getMessage();
}
?>