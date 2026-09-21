<?php
if (!isset($titre)) {
    $titre = "Médiathèque";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($titre) ?></title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>

    <div class="logo">
         Médiathèque
    </div>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="livres.php">Livres</a>
        <a href="adherents.php">Adhérents</a>
        <a href="emprunts.php">Emprunts</a>
        <a href="emprunter.php">Nouvel emprunt</a>
        <a href="retour.php">Retours</a>
    </nav>

</header>

<main></main>