<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = require __DIR__ . '/../BDD/db_connexion.php';
    }

    public function testAdminInsertion()
    {
        $email = "test_" . rand(1,10000) . "@mail.com";
        $password = password_hash("1234", PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO admins (email, mot_de_passe_hache)
            VALUES (?, ?)
        ");

        $result = $stmt->execute([$email, $password]);

        $this->assertTrue($result);
    }
}