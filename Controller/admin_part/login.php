<?php

session_start();

require ("../../Modele/db_connexion.php"); 

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_saisi = $_POST['email']; 
    $password_saisi = $_POST['mot_de_passe_hache'] ?? ''; 

    // Préparer la requête pour récupérer l'administrateur
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->bindParam(':email', $email_saisi);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    $ip = $_SERVER['REMOTE_ADDR'];

    // Vérifier si un administrateur existe
    if ($admin) {
        
        // Vérification du mot de passe
        if (password_verify($password_saisi, $admin['mot_de_passe_hache'])) {

            // Journalisation succès
            $stmt = $pdo->prepare("INSERT INTO log_connexions(email, ip_adresse, statut) VALUES (?, ?, 'success')");
            $stmt->execute([$email_saisi, $ip]);

            $_SESSION['admin'] = $admin['email'];
            header('Location: ../admin_part/vue_comment.php'); 
            exit();

        } else {

            // Journalisation échec
            $stmt = $pdo->prepare("INSERT INTO log_connexions(email, ip_adresse, statut) VALUES (?, ?, 'failed')");
            $stmt->execute([$email_saisi, $ip]);

            $erreur = 'Identifiant ou mot de passe incorrect.';
            header('Location: ../erreur.php');
            exit(); 
        }

    } else {

        // Journalisation échec (email inconnu)
        $stmt = $pdo->prepare("INSERT INTO log_connexions(email, ip_adresse, statut) VALUES (?, ?, 'failed')");
        $stmt->execute([$email_saisi, $ip]);

        $erreur = 'Identifiant ou mot de passe incorrect.';
        header('Location: ../erreur.php');
        exit();
    }   
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Vue/CSS/admin_design/design_login.css">
    <title>Connexion admin</title>
</head>
<body>

<div class="container">
      
       <h2>Connexion Admin</h2>
    <?php if ($erreur): ?>
        <p style="color: red;"><?php echo $erreur; ?></p>
    <?php endif; ?>

    <div class="formulaire">
        <form method="post">
            <input type="text" id="email" name="email" placeholder="Email" required>
            <br><br>
            <input type="password" id="mot_de_passe_hache" name="mot_de_passe_hache" placeholder="Mot de passe" required>
            <br><br>
            <button type="submit">Se connecter</button>  
        </form>
    </div>
    
</div>
   
</body>
</html>