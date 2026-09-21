<?php

require_once "db.php";

$titre = "Emprunts en cours";

$sql = "
SELECT
    emprunt.id_emprunt,
    adherent.nom,
    adherent.prenom,
    livre.titre,
    emprunt.date_emprunt,
    emprunt.date_retour_prevue
FROM emprunt

JOIN adherent
    ON emprunt.id_adherent = adherent.id_adherent

JOIN livre
    ON emprunt.id_livre = livre.id_livre

WHERE emprunt.date_retour IS NULL

ORDER BY emprunt.date_retour_prevue
";

$stmt = $pdo->query($sql);

$emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);

require "header.php";

?>

<h1>Emprunts en cours</h1>

<table>

<tr>
    <th>Adhérent</th>
    <th>Livre</th>
    <th>Date emprunt</th>
    <th>Retour prévu</th>
    <th>État</th>
    <th>Action</th>
</tr>

<?php foreach ($emprunts as $emprunt): ?>

<?php

$datePrevue = new DateTime($emprunt['date_retour_prevue']);
$aujourdHui = new DateTime();

$enRetard = $datePrevue < $aujourdHui;

?>

<tr>

    <td>
        <?= htmlspecialchars(
            $emprunt['prenom'] . " " . $emprunt['nom']
        ) ?>
    </td>

    <td>
        <?= htmlspecialchars($emprunt['titre']) ?>
    </td>

    <td>
        <?= htmlspecialchars($emprunt['date_emprunt']) ?>
    </td>

    <td>
        <?= htmlspecialchars($emprunt['date_retour_prevue']) ?>
    </td>

    <td>

        <?php if ($enRetard): ?>

            <span style="color:red;">
                En retard
            </span>

        <?php else: ?>

            <span style="color:green;">
                En cours
            </span>

        <?php endif; ?>

    </td>

    <td>

        <a
            href="retour.php?id=<?= $emprunt['id_emprunt'] ?>"
            class="btn btn-danger"
        >
            Retour
        </a>

    </td>

</tr>

<?php endforeach; ?>

</table>

<?php require "footer.php"; ?>