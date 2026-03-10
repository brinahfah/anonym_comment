<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Erreur de connexion</title>
<link rel="stylesheet" href="CSS/design_erreur_log.css">
</head>

<body>

<div class="container">

    <div class="error-box">

        <h1>⚠️ Connexion refusée</h1>

        <p>
        L'adresse email ou le mot de passe est incorrect.
        </p>

        <p class="info">
        Veuillez vérifier vos identifiants et réessayer.
        </p>

        <a href="login.php" class="btn-retour">
        Retour à la connexion
        </a>

    </div>

</div>

</body>
</html>