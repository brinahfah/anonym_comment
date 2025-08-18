<?php

session_start();

require 'BDD/db_connexion.php'; 

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_saisi = $_POST['email']; 
    $password_saisi = $_POST['mot_de_passe_hache'] ?? ''; 

    // 1. Préparer la requête pour récupérer l'enregistrement de l'administrateur par son e-mail
    // Nous avons seulement besoin de sélectionner l'enregistrement par e-mail pour obtenir le hachage stocké.
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email");
    $stmt->bindParam(':email', $email_saisi);
    
    
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC); // Récupérer les données de l'utilisateur sous forme de tableau associatif

    // 2. Vérifier si un administrateur avec cet e-mail a été trouvé ET si le mot de passe correspond
    if ($admin) {
        
        // Utiliser password_verify() pour comparer le mot de passe en texte clair du formulaire
        // avec le mot de passe haché stocké dans la base de données ($admin['mot_de_passe_hache']).
        
        if (password_verify($password_saisi, $admin['mot_de_passe_hache'])) {
            $_SESSION['admin'] = $admin['email'];
            header('Location: admin.php'); 
            exit();
        } else {
            // Le mot de passe ne correspond pas
            $erreur = 'Identifiant ou mot de passe incorrect.';
           header('Location: erreur.php');
           exit(); 
        }
    } else {
        // Aucun administrateur trouvé avec l'e-mail fourni
        $erreur = 'Identifiant ou mot de passe incorrect.';
        header('Location: erreur.php ');
        exit();
    }   
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/design_login.css">
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