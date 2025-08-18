<?php
require 'BDD/db_connexion.php';
session_start();

if (isset($_POST['nom_du_cour'], $_POST['id_filiere'])) {
    $nom = $_POST['nom_du_cour'];
    $id_filiere = $_POST['id_filiere'];

    $stmt = $pdo->prepare("INSERT INTO cours (nom_du_cour, id_filiere) VALUES (?, ?)");
    $stmt->execute([$nom, $id_filiere]);
}

header("Location: cours.php"); // Redirection vers la page principale
exit;
?>
