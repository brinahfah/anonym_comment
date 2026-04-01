<?php
use PHPUnit\Framework\TestCase;

class CoursDeleteTest extends TestCase
{
    private $pdo;
    private $id_cour_test;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '../../Modele/db_connexion.php';
        // Crée un cours test pour pouvoir le supprimer
        $stmt = $this->pdo->prepare("INSERT INTO cours (nom_du_cour, id_filiere) VALUES (?, ?)");
        $stmt->execute(["Cours à supprimer BTS", 1]);
        $this->id_cour_test = $this->pdo->lastInsertId();
    }

    public function testSupprimerCours()
    {
        $stmt = $this->pdo->prepare("DELETE FROM cours WHERE id_cour = ?");
        $result = $stmt->execute([$this->id_cour_test]);
        $this->assertTrue($result);

        // Vérifie qu'il a bien été supprimé
        $stmt2 = $this->pdo->prepare("SELECT COUNT(*) as cnt FROM cours WHERE id_cour = ?");
        $stmt2->execute([$this->id_cour_test]);
        $row = $stmt2->fetch();
        $this->assertEquals(0, $row['cnt']);
    }
}