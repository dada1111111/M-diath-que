<?php

require_once "db.php";

$titre = "Accueil";

$nbLivres = $pdo->query(
    "SELECT COUNT(*) FROM livre"
)->fetchColumn();

$nbAdherents = $pdo->query(
    "SELECT COUNT(*) FROM adherent"
)->fetchColumn();

$nbEmprunts = $pdo->query(
    "SELECT COUNT(*) FROM emprunt WHERE date_retour IS NULL"
)->fetchColumn();

require "header.php";

?>

<h1>Bienvenue sur la Médiathèque</h1>

<p>
    Consultez les livres, gérez les emprunts et suivez les retours.
</p>

<div class="card-container">

    <div class="card">
        <h2><?= $nbLivres ?></h2>
        <p>Livres</p>
    </div>

    <div class="card">
        <h2><?= $nbAdherents ?></h2>
        <p>Adhérents</p>
    </div>

    <div class="card">
        <h2><?= $nbEmprunts ?></h2>
        <p>Emprunts en cours</p>
    </div>

</div>

<?php require "footer.php"; ?>