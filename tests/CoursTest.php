<?php
use PHPUnit\Framework\TestCase;

class CoursTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Connexion à la base
        $this->pdo = require __DIR__ . '../../Modele/db_connexion.php';
    }

    public function testAjouterCours()
    {
        $nom_du_cour = "Cours Test BTS";
        $id_filiere = 1; // filière déjà existante

        $stmt = $this->pdo->prepare("
            INSERT INTO cours (nom_du_cour, id_filiere)
            VALUES (?, ?)
        ");
        $result = $stmt->execute([$nom_du_cour, $id_filiere]);

        // Vérifie que l'ajout a fonctionné
        $this->assertTrue($result);

        // Supprime le cours pour ne pas polluer la base
        $this->pdo->prepare("DELETE FROM cours WHERE nom_du_cour = ?")->execute([$nom_du_cour]);
    }
}