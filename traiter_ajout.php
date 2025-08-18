<?php
require 'BDD/db_connexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(trim($_POST['nom_du_cour'])) && !empty($_POST['id_filiere'])) {
        $nom_du_cour = trim($_POST['nom_du_cour']);
        $id_filiere = (int) $_POST['id_filiere'];

        $stmt = $pdo->prepare("INSERT INTO cours (nom_du_cour, id_filiere) VALUES (:nom, :id_filiere)");
        $stmt->bindParam(':nom', $nom_du_cour);
        $stmt->bindParam(':id_filiere', $id_filiere, PDO::PARAM_INT);

        $_SESSION['message'] = $stmt->execute() ? "Cours ajouté avec succès." : "Erreur lors de l'ajout du cours.";
    } else {
        $_SESSION['message'] = "Veuillez remplir tous les champs.";
    }
} else {
    $_SESSION['message'] = "Requête invalide.";
}

header("Location: cours.php");
exit;
