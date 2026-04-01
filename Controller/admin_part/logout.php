<?php

session_start();
session_destroy(); // Détruire la session
header('Location: ../admin_part/login.php'); // Rediriger vers la page de connexion
exit();

?>