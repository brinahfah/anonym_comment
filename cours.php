<?php
require 'BDD/db_connexion.php';
session_start();

// Récupérer toutes les filières
$stmt_filiere = $pdo->query("SELECT * FROM filieres ORDER BY nom");
$filieres = $stmt_filiere->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les cours avec leur filière
$stmt_cours = $pdo->query("
    SELECT c.id_cour, c.nom_du_cour, f.nom AS filiere_nom, c.id_filiere
    FROM cours c
    LEFT JOIN filieres f ON c.id_filiere = f.id_filiere
    ORDER BY f.nom, c.nom_du_cour
");
$cours = $stmt_cours->fetchAll(PDO::FETCH_ASSOC);

// Initialiser toutes les filières avec un tableau vide
$cours_par_filiere = [];
foreach ($filieres as $f) {
    $cours_par_filiere[$f['nom']] = [];
}

// Ajouter les cours dans leur filière correspondante
foreach ($cours as $c) {
    $filiere = $c['filiere_nom'] ?? 'Non attribuée';
    $cours_par_filiere[$filiere][] = $c;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gérer les cours</title>
<link rel="stylesheet" href="CSS/design_cours.css">
</head>
<body>

<main class="main-container">
    <h2>Ajouter ou Supprimer des cours</h2>

    <!-- Formulaire d'ajout -->
    <div class="form-section">
        <form method="post" action="ajouter_cours.php" class="add-course-form">
            <select name="id_filiere" required>
                <option value="" disabled selected>Choisir une filière</option>
                <?php foreach ($filieres as $f): ?>
                    <option value="<?= $f['id_filiere'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="nom_du_cour" id="nom_du_cour" placeholder="Nom du cours" required>
            <button type="submit">Ajouter</button>
        </form>
    </div>    

    <!-- Liste des cours -->
    <div class="courses-list-section">
        <?php foreach ($cours_par_filiere as $filiere => $cours_liste): ?>
            <div class="filiere-section">
                <button class="filiere-btn">
                    <?= htmlspecialchars($filiere) ?>
                    <span class="arrow">&#9662;</span>
                </button>

                <div class="cours-list">
                    <?php if (count($cours_liste) > 0): ?>
                        <?php foreach ($cours_liste as $c): ?>
                            <div class="cours-item">
                                <span class="course-name"><?= htmlspecialchars($c['nom_du_cour']) ?></span>
                                <a href="supprimer_cours.php?id_cour=<?= $c['id_cour'] ?>" 
                                   class="delete-link" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce cours ?');">
                                   Supprimer
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="cours-item">Aucun cours dans cette filière</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <br>
        <a href="admin.php" class="back-link">Voir les avis</a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle affichage des cours par filière
    const filiereButtons = document.querySelectorAll('.filiere-btn');
    filiereButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const list = btn.nextElementSibling;
            list.classList.toggle('show');
            btn.classList.toggle('active');
        });
    });
});
</script>

</body>
</html>
