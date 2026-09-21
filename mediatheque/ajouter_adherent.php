<?php

require_once "db.php";

$titre = "Ajouter un adhérent";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $dateInscription = $_POST['date_inscription'];

    $verification = $pdo->prepare("
        SELECT id_adherent
        FROM adherent
        WHERE email = ?
    ");

    $verification->execute([$email]);

    if ($verification->fetch()) {

        $message = "Cette adresse email est déjà utilisée.";

    } else {

        $sql = "
            INSERT INTO adherent
            (
                nom,
                prenom,
                email,
                date_inscription
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $nom,
            $prenom,
            $email,
            $dateInscription
        ]);

        header("Location: adherents.php");
        exit;
    }
}

require "header.php";
?>

<h1>Ajouter un adhérent</h1>

<?php if ($message != ""): ?>

    <div class="alert-danger">
        <?= htmlspecialchars($message) ?>
    </div>
    <br>

<?php endif; ?>

<div class="form-container">

<form method="POST">
    <div class="form-group">
        <label>Nom</label>

        <input>
            type="text"
            name="nom"
            required
            placeholder="Ex : Fervil"
        

    </div>

    <div class="form-group">
        <label>Prénom</label>

        <input
            type="text"
            name="prenom"
            required
            placeholder="Ex : Darren"
        >

    </div>

    <div class="form-group">
        <label>Email</label>

        <input
            type="email"
            name="email"
            required
            placeholder="Ex : darrenfervil@gmail.fr"
        >

    </div>

    <div class="form-group">
        <label>Date d'inscription</label>

        <input
            type="date"
            name="date_inscription"
            value="<?= date('Y-m-d') ?>"
            required
        >

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Ajouter l'adhérent
    </button>

<a
        href="adherents.php"
        class="btn"
    >
        Annuler
    </a>

</form>

</div>

<?php require "footer.php"; ?>
