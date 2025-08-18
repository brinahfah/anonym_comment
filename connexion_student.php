<?php
require 'BDD/db_connexion.php';

session_start();

$filiere_stmt = $pdo->query ("SELECT * from filieres");
$filiere_id = $filiere_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/design_connex_stud.css">
    <title>Connexion etudiant</title>
</head>
<body>

    <main>

        <h2>Connexion Etudiant</h2>

        <form method="post" action="verifier_code.php">

            <div class="bloc_milieu">
                <div class="cote_gauche">
                    <label for="filiere"><strong>Choisissez votre filiere :</strong></label>
                    <select name="id_filiere" required>
                        <?php foreach ($filiere_id as $f_id):?>
                            <option value="<?= $f_id['id_filiere']?>"><?=htmlspecialchars($f_id['nom'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="cote_droit">
                    <label for="code"><strong> Votre code d'identification <div class="point">:</div></strong></label>
                    <input type="text" name="code_identifiant" required>
                </div>

            </div>

            <button type="submit">Entrer</button>  
        </form>

    </main>

    <hr>
 
    <footer>
         <img src="image/ChatGPT Image 23 juil. 2025, 15_22_27.png" alt="Image de pied de page">
    </footer>

</body>
</html>