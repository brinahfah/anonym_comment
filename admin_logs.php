<?php
require_once "../BDD/db_connexion.php";

// Récupérer les logs
$stmt = $pdo->query("SELECT * FROM log_connexions ORDER BY date_connexion DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Journal des connexions</h2>

<table border="1">
    <tr>
        <th>Email</th>
        <th>IP</th>
        <th>Statut</th>
        <th>Date</th>
    </tr>

    <?php foreach($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['email']) ?></td>
            <td><?= $log['ip_adresse'] ?></td>
            <td><?= $log['statut'] ?></td>
            <td><?= $log['date_connexion'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>