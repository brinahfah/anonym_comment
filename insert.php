<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/design_succes.css">
    <title>Document</title>
</head>
<body>

    <div class="container">
    
            <?php
        require 'BDD/db_connexion.php';
        session_start();

        $id_study = $_SESSION['id_study'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_study) {
            $id_cour = $_POST['id_cour'];
            $note = $_POST['note'];
            $commentaire = $_POST['commentaire'];
           

           $stmt = $pdo->prepare("INSERT INTO commentaires (id_cour, note, commentaire) VALUES (?, ?, ?)");
           $stmt->execute([$id_cour, $note, $commentaire]);


            echo "<p>✅ Commentaire envoyé avec succès.</p>";
            echo '<br><a href="comment.php">Retour</a>';
        } else {
            echo "Erreur lors de l'envoi du commentaire.";
        }

        ?>

    </div>
</body>
</html>


