<?php

require_once "db.php";

$titre = "Nouvel emprunt";

$adherents = $pdo->query("
    SELECT *
    FROM adherent
    ORDER BY nom
")->fetchAll(PDO::FETCH_ASSOC);

$livres = $pdo->query("
    SELECT *
    FROM livre
    ORDER BY titre
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idAdherent = $_POST['id_adherent'];
    $idLivre = $_POST['id_livre'];
    $dateEmprunt = $_POST['date_emprunt'];
    $dateRetour = $_POST['date_retour_prevue'];

    $sql = "
        INSERT INTO emprunt
        (
            id_adherent,
            id_livre,
            date_emprunt,
            date_retour_prevue
        )
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $idAdherent,
        $idLivre,
        $dateEmprunt,
        $dateRetour
    ]);

    header("Location: emprunts.php");
    exit;
}

require "header.php";

?>

<h1>Nouvel emprunt</h1>

<div class="form-container">
<form method="POST">
    <div class="form-group">
        <label>Adhérent</label>
        <select name="id_adherent" required>

            <?php foreach ($adherents as $adherent): ?>
                <option value="<?= $adherent['id_adherent'] ?>">

                    <?= htmlspecialchars(
                        $adherent['prenom'] . " " . $adherent['nom']
                    ) ?>

                </option>
            <?php endforeach; ?>
        </select>

    </div>


    <div class="form-group">
        <label>Livre</label>
        <select name="id_livre" required>

            <?php foreach ($livres as $livre): ?>
                <option value="<?= $livre['id_livre'] ?>">

                    <?= htmlspecialchars($livre['titre']) ?>

                </option>
            <?php endforeach; ?>
        </select>

    </div>


    <div class="form-group">
        <label>Date d'emprunt</label>

        <input
            type="date"
            name="date_emprunt"
            value="<?= date('Y-m-d') ?>"
            required
        >

    </div>


    <div class="form-group">
        <label>Date de retour prévue</label>

        <input
            type="date"
            name="date_retour_prevue"
            required
        >

    </div>


    <button class="btn" type="submit">
        Enregistrer l'emprunt
    </button>

</form>

</div>

<?php require "footer.php"; ?>