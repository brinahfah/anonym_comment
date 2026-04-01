<?php

// Charge l'autoload de Composer
require_once __DIR__ . '../../Modele/db_connexion.php';

// Charge la connexion PDO
require_once __DIR__ . '../../Modele/db_connexion.php';

// Vérifie que la connexion fonctionne
if (!isset($pdo) || !$pdo instanceof PDO) {
    die('Erreur : $pdo non défini ou non valide dans bootstrap.php');
}