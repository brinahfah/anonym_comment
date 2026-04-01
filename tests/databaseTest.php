<?php
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testDatabaseConnection()
    {
        $pdo = require __DIR__ . '../../Modele/db_connexion.php';

        $this->assertInstanceOf(PDO::class, $pdo);

        $stmt = $pdo->query("SELECT NOW()");
        $result = $stmt->fetch();

        $this->assertNotEmpty($result);
    }
}