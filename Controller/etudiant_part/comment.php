<?php
require ("../../Modele/db_connexion.php");
session_start();

$filiere_id = $_SESSION['id_filiere'] ?? null;
if (!$filiere_id) {
    die("Accès refusé. Aucune filière trouvée.");
}

// Récupère les cours liés à cette filière
$stmt = $pdo->prepare("SELECT * FROM cours WHERE id_filiere = ?");
$stmt->execute([$filiere_id]);
$cours = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Vue/CSS/etudiant_design/design_comment.css">
    <title>Laisser un commentaire</title>
</head>
<body>

    <main class="main-container">
        <h2>Laisser un commentaire</h2>

        <form method="post" action="../../Controller/etudiant_part/insert.php" class="comment-form">
            <div class="form-group">
                <label for="id_cour">Cours :</label>
                <select name="id_cour" id="id_cour" required>
                    <?php foreach ($cours as $c): ?>
                        <option value="<?= $c['id_cour'] ?>"><?= htmlspecialchars($c['nom_du_cour']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr class="divider">
            <h3 class="section-title">Note :</h3>
            <div class="rating-container">
                <div class="rating">
                    <input type="radio" id="star5" name="note" value="5" required><label for="star5" title="5 étoiles">★</label>
                    <input type="radio" id="star4" name="note" value="4"><label for="star4" title="4 étoiles">★</label>
                    <input type="radio" id="star3" name="note" value="3"><label for="star3" title="3 étoiles">★</label>
                    <input type="radio" id="star2" name="note" value="2"><label for="star2" title="2 étoiles">★</label>
                    <input type="radio" id="star1" name="note" value="1"><label for="star1" title="1 étoile">★</label>
                </div>
                <div class="no-rating">
                    <input type="radio" id="star0" name="note" value="0" checked><label for="star0" title="Aucune note">✖</label>
                    <span class="no-rating-text">Aucune note</span>
                </div>
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire :</label>
                <textarea name="commentaire" id="commentaire" rows="7" maxlength="500" required></textarea>
                <div id="charCount" class="char-count">0 / 500 caractères</div>
            </div>

            <button type="submit" class="submit-button">Envoyer</button>
        </form>
    </main>

    <div class="floating-image">
        <img src="../../Vue/assets/image/ChatGPT Image 23 juil. 2025, 16_18_24.png" alt="Commentaires / Avis">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Confirmation avant envoi
            document.querySelector('.comment-form').addEventListener('submit', function(event) {
                const cours = document.querySelector('select[name="id_cour"]');
                const note = document.querySelector('input[name="note"]:checked');
                const commentaire = document.querySelector('textarea[name="commentaire"]');

                if (!note || note.value === "0") {
                    alert("Veuillez sélectionner une note valide.");
                    event.preventDefault();
                    return;
                }

                // Date en heure française
                const now = new Date();
                const options = { timeZone: 'Europe/Paris', year: 'numeric', month: '2-digit', day: '2-digit', 
                                  hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const dateLocale = new Intl.DateTimeFormat('fr-FR', options).format(now);

                const coursText = cours.options[cours.selectedIndex].text;
                const confirmation = confirm(
                    "Voulez-vous envoyer ce commentaire ?\n\n" +
                    "📚 Cours : " + coursText + "\n" +
                    "⭐ Note : " + note.value + "/5\n" +
                    "🗓️ Date : " + dateLocale + "\n\n" +
                    "💬 Commentaire :\n" + commentaire.value
                );

                if (!confirmation) {
                    event.preventDefault();
                }
            });

            // Compteur de caractères
            const textarea = document.getElementById("commentaire");
            const counter = document.getElementById("charCount");

            textarea.addEventListener("input", function () {
                const length = textarea.value.length;
                counter.textContent = `${length} / 500 caractères`;
            });

            // Gérer "Aucune note"
            const ratingInputs = document.querySelectorAll('.rating input[name="note"]');
            const noRatingRadio = document.getElementById('star0');

            if (!document.querySelector('.rating input[name="note"]:checked') || document.querySelector('.rating input[name="note"]:checked').value === '0') {
                noRatingRadio.checked = true;
            }

            function updateNoRatingDisplay() {
                const checkedStar = document.querySelector('.rating input[name="note"]:checked');
                const noRatingText = document.querySelector('.no-rating-text');
                if (checkedStar && checkedStar.value === '0') {
                    noRatingText.style.display = 'inline-block';
                } else {
                    noRatingText.style.display = 'none';
                }
            }

            ratingInputs.forEach(input => {
                input.addEventListener('change', updateNoRatingDisplay);
            });

            noRatingRadio.addEventListener('change', updateNoRatingDisplay);
            updateNoRatingDisplay();

            // Effet parallax
            const floatingImageDiv = document.querySelector('.floating-image');
            window.addEventListener('mousemove', (e) => {
                if (window.innerWidth > 768) {
                    const windowHeight = window.innerHeight;
                    const maxTranslate = 30;
                    const ratio = (e.clientY / windowHeight);
                    const translateY = (ratio - 0.5) * 2 * maxTranslate;
                    floatingImageDiv.style.transform = `translateY(${translateY}px)`;
                } else {
                    floatingImageDiv.style.transform = `translateY(0)`;
                }
            });
        });
    </script>
</body> 
</html>
