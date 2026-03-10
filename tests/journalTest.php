<?php

use PHPUnit\Framework\TestCase;

class JournalTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../BDD/db_connexion.php';
    }

    public function testJournalInsertion()
    {
        // Prépare l'insertion avec toutes les colonnes nécessaires
        $stmt = $this->pdo->prepare("
            INSERT INTO log_connexions (email, ip_adresse, statut, date_connexion)
            VALUES (?, ?, ?, ?)
        ");

        // Fournir les 4 valeurs
        $result = $stmt->execute([
            "test_" . rand(1,1000) . "@mail.com",  
            "127.0.0.1",                           
            "SUCCES",                               
            date("Y-m-d H:i:s")                     
        ]);

        $this->assertTrue($result);
    }
}