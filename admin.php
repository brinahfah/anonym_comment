<?php
session_start();
require 'BDD/db_connexion.php';

// Vérifier si l'utilisateur est connecté 
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Traitement du filtre
$note = isset($_GET['note']) ? (int) $_GET['note'] : null;
$filiere = isset($_GET['nom']) ? trim($_GET['nom']) : null;

// Base de la requête avec jointure sur filière
$sql = "SELECT cm.id_comment, cm.note, cm.commentaire, c.nom_du_cour, f.nom AS nom, cm.posted_on
        FROM commentaires cm
        JOIN cours c ON cm.id_cour = c.id_cour
        JOIN filieres f ON c.id_filiere = f.id_filiere
        WHERE 1=1";

if ($note) {
    $sql .= " AND cm.note = $note";
}
if ($filiere) {
    $sql .= " AND f.nom LIKE " . $pdo->quote("%$filiere%");
}

$sql .= " ORDER BY cm.id_cour DESC";

$commentaire = $pdo->query($sql)->fetchAll();

// Requête top 5 cours les plus commentés
$sqlTop = "SELECT c.nom_du_cour, f.nom AS nom, COUNT(*) as nb_commentaires, ROUND(AVG(cm.note), 2) as moyenne_note
           FROM commentaires cm
           JOIN cours c ON cm.id_cour = c.id_cour
           JOIN filieres f ON c.id_filiere = f.id_filiere
           GROUP BY c.id_cour, f.nom, c.nom_du_cour
           ORDER BY nb_commentaires DESC
           LIMIT 5";

$topCours = $pdo->query($sqlTop)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/design_admin.css">
    <title>Espace admin</title>
</head>
<body>

<div class="reflet">
    <h2>Commentaires reçus</h2>

    <a href="cours.php">Gérer les cours</a>
    <a href="logout.php">Déconnexion</a>
    <hr>

    <!-- Formulaire de filtre -->
    <form method="get" action="" style="margin-bottom: 20px;">
        <label>Note : </label>
        <input type="number" name="note" min="1" max="5" value="<?= htmlspecialchars($note ?? '') ?>">
        
        <label>Filière : </label>
        <input type="text" name="nom" placeholder="Ex: SIO" value="<?= htmlspecialchars($filiere ?? '') ?>">
        <br><br>
        <button type="submit">Filtrer</button>
        <br><br>
        <a href="admin.php" style="margin-left:10px;">Réinitialiser</a>
    </form>

    <?php if (count($commentaire)): ?>
        <div class="table_responsive">
            <table border="1" cellpadding="8">
                <tr>
                    <th>ID</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Cours</th>
                    <th>Filière</th>
                    <th>Date de publication</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($commentaire as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['id_comment']); ?></td>
                        <td><?= $c['note']; ?>/5</td>
                        <td><?= nl2br(htmlspecialchars($c['commentaire'])); ?></td>
                        <td><?= htmlspecialchars($c['nom_du_cour']); ?></td>
                        <td><?= htmlspecialchars($c['nom']); ?></td>
                        <td><?= $c['posted_on']; ?></td>
                        <td>
                            <form action="delete.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_comment" value="<?= $c['id_comment']; ?>">
                                <button type="submit" onclick="return confirm('Supprimer ce commentaire ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else: ?>
        <p>Aucun commentaire trouvé pour les critères sélectionnés.</p>
    <?php endif; ?>

    <hr>
    
    <div class="table_responsive">
        <h3>Top 5 des cours les plus commentés</h3>
        <table border="1" cellpadding="8">
            <tr>
                <th>Cours</th>
                <th>Filière</th>
                <th>Nombre de commentaires</th>
                <th>Note moyenne</th>
            </tr>
            <?php foreach ($topCours as $top): ?>
                <tr>
                    <td><?= htmlspecialchars($top['nom_du_cour']); ?></td>
                    <td><?= htmlspecialchars($top['nom']); ?></td>
                    <td><?= $top['nb_commentaires']; ?></td>
                    <td><?= $top['moyenne_note']; ?>/5</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
