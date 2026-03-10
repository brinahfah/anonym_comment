<?php
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private $pdo;
    private $id_cour_test;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../BDD/db_connexion.php';
        // Crée un cours pour le commentaire
        $stmt = $this->pdo->prepare("INSERT INTO cours (nom_du_cour, id_filiere) VALUES (?, ?)");
        $stmt->execute(["Cours Commentaire BTS", 1]);
        $this->id_cour_test = $this->pdo->lastInsertId();
    }

    public function testAjouterCommentaire()
    {
        $note = 5;
        $commentaire = "Commentaire test BTS";

        $stmt = $this->pdo->prepare("
            INSERT INTO commentaires (id_cour, note, commentaire)
            VALUES (?, ?, ?)
        ");
        $result = $stmt->execute([$this->id_cour_test, $note, $commentaire]);
        $this->assertTrue($result);

        // Supprime le commentaire
        $this->pdo->prepare("DELETE FROM commentaires WHERE id_cour = ? AND commentaire = ?")
                  ->execute([$this->id_cour_test, $commentaire]);
    }

    protected function tearDown(): void
    {
        // Supprime le cours test après le test
        $this->pdo->prepare("DELETE FROM cours WHERE id_cour = ?")->execute([$this->id_cour_test]);
    }
}