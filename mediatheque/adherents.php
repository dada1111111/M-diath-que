<?php

require_once "db.php";

$titre = "Liste des adhérents";

$stmt = $pdo->query("
    SELECT *
    FROM adherent
    ORDER BY nom
");

$adherents = $stmt->fetchAll(PDO::FETCH_ASSOC);

require "header.php";

?>

<h1>Liste des adhérents</h1>

<a href="ajouter_adherent.php" class="btn btn-success">
     Ajouter un adhérent
</a>

<table>

<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Date d'inscription</th>
</tr>

<?php foreach ($adherents as $adherent): ?>

<tr>

    <td>
        <?= htmlspecialchars($adherent['nom']) ?>
    </td>

    <td>
        <?= htmlspecialchars($adherent['prenom']) ?>
    </td>

    <td>
        <?= htmlspecialchars($adherent['email']) ?>
    </td>

    <td>
        <?= htmlspecialchars($adherent['date_inscription']) ?>
    </td>

</tr>

<?php endforeach; ?>

</table>

<?php require "footer.php"; ?>