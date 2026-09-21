<?php

require_once "db.php";

$titre = "Retour d'un livre";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idEmprunt = $_POST['id_emprunt'];
    $dateRetour = $_POST['date_retour'];

    $stmt = $pdo->prepare("
        UPDATE emprunt
        SET date_retour = ?
        WHERE id_emprunt = ?
    ");

    $stmt->execute([
        $dateRetour,
        $idEmprunt
    ]);

    header("Location: emprunts.php");
    exit;
}

$emprunts = $pdo->query("
    SELECT
        emprunt.id_emprunt,
        livre.titre,
        adherent.nom,
        adherent.prenom
    FROM emprunt

    JOIN livre
        ON emprunt.id_livre = livre.id_livre

    JOIN adherent
        ON emprunt.id_adherent = adherent.id_adherent

    WHERE emprunt.date_retour IS NULL
")->fetchAll(PDO::FETCH_ASSOC);

require "header.php";

?>

<h1>Retour d'un livre</h1>

<div class="form-container">

<form method="POST">

    <div class="form-group">

        <label>Emprunt</label>

        <select name="id_emprunt" required>

            <?php foreach ($emprunts as $emprunt): ?>

                <option value="<?= $emprunt['id_emprunt'] ?>">

                    <?= htmlspecialchars(
                        $emprunt['prenom'] . " " .
                        $emprunt['nom'] . " - " .
                        $emprunt['titre']
                    ) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <div class="form-group">

        <label>Date de retour</label>

        <input
            type="date"
            name="date_retour"
            value="<?= date('Y-m-d') ?>"
            required
        >

    </div>


    <button class="btn btn-success">
        Enregistrer le retour
    </button>

</form>

</div>

<?php require "footer.php"; ?>