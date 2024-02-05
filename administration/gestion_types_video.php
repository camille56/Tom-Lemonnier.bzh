<?php

include_once "../include/configuration.php";

session_start();
if ($_SESSION['statut'] !== 2 && $_SESSION['statut'] !== 3) {
    header("Location: /");
    die();
}

include_once "../include/header.php";
include_once "include/menu.php";
include_once "include/menu_types_video.php";


if (!isset($db)) {
    die();
}

$nom = "";
$commentaire="";

$idTypeVideo = !empty($_POST['idTypeVideo']) ? $_POST['idTypeVideo'] : "";
$formulaireComplet = false;

if (!empty($_GET['idTypeVideo'])) {
    //Si un id est disponible dans les paramètres Get alors une vidéo a été selectionnée dans le menu.
    $idTypeVideo = $_GET['idTypeVideo'];

    $requeteEleve = "select * from ca_type_video where id = ?";
    $resultatEleve = $db->prepare($requeteEleve);
    $resultatEleve->execute([$idTypeVideo]);

    if ($typeVideo = $resultatEleve->fetch(PDO::FETCH_OBJ)) {
        $nom = $typeVideo->nom;
        $commentaire= $typeVideo->commentaire;
    }

}

if (isset($_POST['nom']) && isset($_POST['commentaire'])) {

    $formulaireComplet = true;
    $nom = htmlspecialchars($_POST['nom']);
    $commentaire = htmlspecialchars($_POST['commentaire']);

}

//Suppression d'une vidéo
if (!empty($_POST['suppression']) && !empty($idTypeVideo)) {
    $requeteSupressionTypeVideo = "delete from ca_type_video where id = ?";
    $resultatSupressionTypeVideo = $db->prepare($requeteSupressionTypeVideo);
    $resultatSupressionTypeVideo->execute([$idTypeVideo]);

    header("Location: /administration/gestion_types_video.php");
    die();
}

if ($formulaireComplet && !empty($idTypeVideo)) {
    //Update d'un type de vidéo.

    $requeteUpdateEleve = "update ca_type_video set nom = ?, commentaire = ? where id = ?";
    $resultatUpdateEleve = $db->prepare($requeteUpdateEleve);
    $resultatUpdateEleve->execute([$nom,$commentaire,$idTypeVideo]);

    header("Location: /administration/gestion_types_video.php?idTypeVideo=" . $idTypeVideo);
    die();

} elseif ($formulaireComplet && empty($idTypeVideo)) {
    //create d'un type de vidéo.

    $dateInscription = date("Y-m-d");
    $idAcces = 2;

    $requeteCreateEleve = "insert into ca_type_video (
                        nom,
                        commentaire
                      ) values (?,?
                                )";
    $resultatCreateEleve = $db->prepare($requeteCreateEleve);
    $resultatCreateEleve->execute([
        $nom,
        $commentaire,
    ]);

    $idTypeVideo = $db->lastInsertId();

    header("Location: /administration/gestion_types_video.php?idTypeVideo=" . $idTypeVideo);
    die();
}

?>


<div class="container">
    <form action="gestion_types_video.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= !empty($nom) ? $nom : "" ?>" required>

        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire"
                  name="commentaire"><?= !empty($commentaire) ? $commentaire : "" ?></textarea>

        <input type="hidden" name="idTypeVideo" value="<?= $idTypeVideo ?>">
        <br>
        <button type="submit"><?= !empty($idTypeVideo) ? "Modifier un type de Vidéo" : "Ajouter un type de Vidéo" ?></button>

    </form>
    <form action="gestion_types_video.php" method="post" onsubmit="return confirmSuppression();">
        <label for="suppression">Suppression du type de la vidéo:</label>
        <input type="hidden" name="idTypeVideo" value="<?= $idTypeVideo ?>">
        <button type="submit" id="suppression" name="suppression" value="1">Suppression</button>
    </form>
    <br>
</div>

<?php
include_once "../include/footer.php";
?>

<script>
    function confirmSuppression() {
        return confirm("Êtes-vous sûr de vouloir supprimer ce type de vidéo?");
    }
</script>