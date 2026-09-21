<?php

require_once "db.php";

$titre = "Liste des livres";

$recherche = $_GET['recherche'] ?? '';

$sql = "
    SELECT
        livre.id_livre,
        livre.titre,
        livre.isbn,
        livre.annee_publication,
        livre.disponible,

        GROUP_CONCAT(
            CONCAT(auteur.prenom, ' ', auteur.nom)
            SEPARATOR ', '
        ) AS auteurs

    FROM livre

    LEFT JOIN livre_auteur
        ON livre.id_livre = livre_auteur.id_livre

    LEFT JOIN auteur
        ON livre_auteur.id_auteur = auteur.id_auteur
";

$params = [];

if ($recherche != '') {

    $sql .= "
        WHERE livre.titre LIKE ?
        OR livre.isbn LIKE ?
        OR auteur.nom LIKE ?
        OR auteur.prenom LIKE ?
    ";

    $rechercheSQL = "%$recherche%";

    $params[] = $rechercheSQL;
    $params[] = $rechercheSQL;
    $params[] = $rechercheSQL;
    $params[] = $rechercheSQL;
}

$sql .= "

    GROUP BY
        livre.id_livre,
        livre.titre,
        livre.isbn,
        livre.annee_publication,
        livre.disponible

    ORDER BY livre.titre
";


$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);


require "header.php";

?>


<h1>Liste des livres</h1>


<form method="GET">

    <input
        type="text"
        name="recherche"
        placeholder="Rechercher un livre, auteur..."
        value="<?= htmlspecialchars($recherche) ?>"
    >
    <br><br>
    <button
        class="btn"
        type="submit"
    >
         Rechercher
    </button>

</form>
<br>

<a
    href="ajouter_livre.php"
    class="btn btn-success"
>
  Ajouter un livre
</a>


<table>
    <tr>
        <th>Titre</th>
        <th>Auteur(s)</th>
        <th>Année</th>
        <th>ISBN</th>
        <th>Disponibilité</th>
    </tr>

    <?php foreach ($livres as $livre): ?>

    <tr>
        <td>

            <?= htmlspecialchars(
                $livre['titre']
            ) ?>

        </td>

        <td>

            <?= htmlspecialchars(
                $livre['auteurs'] ?? 'Aucun auteur'
            ) ?>

        </td>
        <td>
            <?= htmlspecialchars(
                $livre['annee_publication']
            ) ?>

        </td>
        <td>
            <?= htmlspecialchars(
                $livre['isbn']
            ) ?>
        </td>
        <td>
            <?php if ($livre['disponible'] == 1): ?>

                <span class="btn-success">
                    Disponible
                </span>

            <?php else: ?>

                <span class="btn-danger">
                    Emprunté
                </span>

            <?php endif; ?>

        </td>

    </tr>

    <?php endforeach; ?>

</table>


<?php require "footer.php"; ?>