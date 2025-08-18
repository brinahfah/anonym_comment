<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="CSS/design_erreur.css">
    <title>Document</title>
</head>
<body>
    
    <div class="container">
        <?php
            require 'BDD/db_connexion.php';
            session_start();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // Vérifie si les champs attendus existent
                if (!empty($_POST['code_identifiant']) && !empty($_POST['id_filiere'])) {

                $code = isset($_POST['code_identifiant']) ? trim($_POST['code_identifiant']) : '';

                    $filiere_id = $_POST['id_filiere'];
                
                    if ($code && $filiere_id) {
                    $stmt = $pdo->prepare("SELECT id_study FROM etudiants WHERE code_identifiant = :code AND id_filiere = :id_f");
                    $stmt->execute([
                        ':code' => $code,
                        ':id_f' => $filiere_id
                    ]);

                    $etudiant = $stmt->fetch();

                    if ($etudiant) {
                        // Stocker les infos dans la session
                        $_SESSION['id_study'] = $etudiant['id_study']; // corrige ici si le champ est bien 'id_study'
                        $_SESSION['id_filiere'] = $filiere_id;

                    // ✅ Redirection vers le formulaire de commentaire
                        header('Location: comment.php');
                        exit;
                    } else {
                        echo "<p>❌ Code incorrect ou filière incorrecte.</p>";
                    }
                } else {
                    echo "<p>Veuillez remplir tous les champs.</p>";
                }
            } else {
                echo "<p>Accès non autorisé.</p>";
            }

            }

        ?>
    </div>    
</body>
</html>




    