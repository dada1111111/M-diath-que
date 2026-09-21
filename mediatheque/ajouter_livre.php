<?php

require_once "db.php";

$titre = "Ajouter un livre";

$message = "";

/* Récupérer les auteurs */
$auteurs = $pdo->query("
    SELECT id_auteur, nom, prenom
    FROM auteur
    ORDER BY nom, prenom
")->fetchAll(PDO::FETCH_ASSOC);


/* Traitement du formulaire */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titreLivre = $_POST['titre'];
    $isbn = $_POST['isbn'];
    $annee = $_POST['annee_publication'];
    $idCategorie = $_POST['id_categorie'];

    // Récupération des auteurs sélectionnés
    $auteursSelectionnes = $_POST['auteurs'] ?? [];


    /* Vérifier l'ISBN */

    $verification = $pdo->prepare("
        SELECT id_livre
        FROM livre
        WHERE isbn = ?
    ");

    $verification->execute([$isbn]);


    if ($verification->fetch()) {

        $message = "Cet ISBN existe déjà.";

    } elseif (count($auteursSelectionnes) == 0) {

        $message = "Veuillez sélectionner au moins un auteur.";

    } else {

        try {

            // Commencer une transaction
            $pdo->beginTransaction();


            /* Ajouter le livre */

            $sql = "
                INSERT INTO livre
                (
                    titre,
                    isbn,
                    annee_publication,
                    disponible,
                    id_categorie
                )
                VALUES (?, ?, ?, 1, ?)
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $titreLivre,
                $isbn,
                $annee,
                $idCategorie
            ]);


            /* Récupérer l'id du livre créé */

            $idLivre = $pdo->lastInsertId();


            /* Ajouter les auteurs */

            $sqlAuteur = "
                INSERT INTO livre_auteur
                (
                    id_livre,
                    id_auteur
                )
                VALUES (?, ?)
            ";

            $stmtAuteur = $pdo->prepare($sqlAuteur);


            foreach ($auteursSelectionnes as $idAuteur) {

                $stmtAuteur->execute([
                    $idLivre,
                    $idAuteur
                ]);
            }


            // Valider toutes les opérations
            $pdo->commit();


            // Retourner vers la liste
            header("Location: livres.php");
            exit;


        } catch (Exception $e) {

            // Annuler les modifications en cas d'erreur
            $pdo->rollBack();

            $message = "Erreur lors de l'ajout du livre : "
                     . $e->getMessage();
        }
    }
}


require "header.php";

?>

<h1>Ajouter un livre</h1>


<?php if ($message != ""): ?>

    <div class="alert-danger">
        <?= htmlspecialchars($message) ?>
    </div>

    <br>

<?php endif; ?>


<div class="form-container">

<form method="POST">


    <!-- TITRE -->

    <div class="form-group">

        <label>Titre du livre</label>

        <input
            type="text"
            name="titre"
            required
            placeholder="Ex : Clean Code"
        >

    </div>


    <!-- ISBN -->

    <div class="form-group">

        <label>ISBN</label>

        <input
            type="text"
            name="isbn"
            required
            placeholder="Ex : 9780132350884"
        >

    </div>


    <!-- ANNÉE -->

    <div class="form-group">

        <label>Année de publication</label>

        <input
            type="number"
            name="annee_publication"
            min="0"
            placeholder="Ex : 2008"
        >

    </div>


    <!-- CATÉGORIE -->

    <div class="form-group">

        <label>ID de la catégorie</label>

        <input
            type="number"
            name="id_categorie"
            required
            placeholder="Ex : 1"
        >

    </div>


    <!-- AUTEURS -->

    <div class="form-group">

        <label>Auteur(s)</label>

        <select
            name="auteurs[]"
            multiple
            required
        >

            <?php foreach ($auteurs as $auteur): ?>

                <option value="<?= $auteur['id_auteur'] ?>">

                    <?= htmlspecialchars(
                        $auteur['prenom'] . " " . $auteur['nom']
                    ) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <small>
            Maintenez Ctrl (Windows) pour sélectionner plusieurs auteurs.
        </small>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Ajouter le livre
    </button>

    <a
        href="livres.php"
        class="btn"
    >
        Annuler
    </a>

</form>

</div>


<?php require "footer.php"; ?>