<?php

include_once "../include/configuration.php";
include_once "../include/fonctions.php";

session_start();
if ($_SESSION['statut'] !== 2 && $_SESSION['statut'] !== 3) {
    header("Location: /");
    die();
}

include_once "../include/header.php";
include_once "include/menu.php";
include_once "include/menu_videos.php";


if (!isset($db)) {
    die();
}


$nom = "";
$nomFichier = "";
$extensionFichier = "";
$idTypeVideo = "";
$dateCreation = "";
$visibilite = "";
$nombreVisionnage = "";
$ordre = "";


$listeTypesVideo = array();

$idVideo = !empty($_POST['idVideo']) ? $_POST['idVideo'] : "";
$formulaireComplet = false;

//Récupération des différents types de vidéo en BDD.
$requeteTypeVideo = "select id, nom from ca_type_video";
$resultatTypeVideo = $db->prepare($requeteTypeVideo);
$resultatTypeVideo->execute();
while ($typeVideo = $resultatTypeVideo->fetch(PDO::FETCH_OBJ)) {
    $listeTypesVideo[] = $typeVideo;
}

//Si un id est disponible dans les paramètres Get alors une vidéo a été selectionnée dans le menu.
if (!empty($_GET['idVideo'])) {

    $idVideo = $_GET['idVideo'];

    $requeteEleve = "select * from ca_Video where id = ?";
    $resultatEleve = $db->prepare($requeteEleve);
    $resultatEleve->execute([$idVideo]);

    if ($video = $resultatEleve->fetch(PDO::FETCH_OBJ)) {
        $nom = $video->nom;
        $idTypeVideo = $video->type;
        $dateCreation = $video->date_creation;
        $visibilite = $video->visibilite;
        $nombreVisionnage = $video->nombre_visionnage;
        $ordre = $video->ordre;

    }

}

if (!empty($_POST['nom']) && !empty($_POST['type'])) {

    $formulaireComplet = true;

    $nom = htmlspecialchars($_POST['nom']);
    $idTypeVideo = $_POST['type'];
    $visibilite = !empty($_POST['visibilite']) ? 1 : 0;

}

//Suppression d'une vidéo
if (!empty($_POST['suppression']) && !empty($idVideo)) {

    supprimeVideoDuServer($idVideo);

    $requeteSupressionEleve = "delete from ca_video where id = ?";
    $resultatSupressionEleve = $db->prepare($requeteSupressionEleve);
    $resultatSupressionEleve->execute([$idVideo]);

    header("Location: /administration/gestion_videos.php");
    die();
}

if ($formulaireComplet && !empty($idVideo)) {
    //Update d'une vidéo.

    $requeteUpdateEleve = "update ca_video set nom = ?, type = ?, visibilite= ?, nombre_visionnage = ?, ordre = ? where id = ?";
    $resultatUpdateEleve = $db->prepare($requeteUpdateEleve);
    $resultatUpdateEleve->execute([$nom, $idTypeVideo, $visibilite, $nombreVisionnage, $ordre, $idVideo]);

    //gestion de la soumission d'une vidéo dans le formulaire.
    if (!empty($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {

        if (preg_match('/\.(.+)$/', $_FILES['fichier']['name'], $matches)) {
            $extensionFichier = $matches[1];
        }

        $etatSauvegarde = sauvegardeVideo($_FILES['fichier'], $idVideo);

        if ($etatSauvegarde["saveOk"]) {
            $nomFichier = $etatSauvegarde["nomDuFichier"];

            $requeteUpdateNomVideo = "update ca_video set nom_fichier_video = ?, extension_fichier = ?  where id = ?";
            $resultatUpdateNomVideo = $db->prepare($requeteUpdateNomVideo);
            $resultatUpdateNomVideo->execute([$nomFichier, $extensionFichier, $idVideo]);

        }
        $_SESSION['messageConfirmation'] = $etatSauvegarde['message'];

    }

    header("Location: /administration/gestion_videos.php?idVideo=" . $idVideo);
    die();

} elseif ($formulaireComplet && empty($idVideo)) {
    //create d'une vidéo.

    $dateCreation = date("Y-m-d");
    $idAcces = 2; //todo à revoir, ça vient d ou?

    //gestion de la soumission d'une vidéo dans le formulaire.
    if (!empty($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {

        if (preg_match('/\.(.+)$/', $_FILES['fichier']['name'], $matches)) {
            $extensionFichier = $matches[1];
        }

        $etatSauvegarde = sauvegardeVideo($_FILES['fichier']);

        if ($etatSauvegarde["saveOk"]) {
            $nomFichier = $etatSauvegarde["nomDuFichier"];
        }
        $_SESSION['messageConfirmation'] = $etatSauvegarde['message'];

    }

    $requeteCreateVideo = "insert into ca_video (
                        nom,
                      nom_fichier_video,
                      extension_fichier,
                        type,
                        date_creation,
                        visibilite,
                        nombre_visionnage,
                        ordre
                      ) values (
                        ?,?,?,?,?,?,?,?
                                )";
    $resultatCreateEleve = $db->prepare($requeteCreateVideo);
    $resultatCreateEleve->execute([
        $nom,
        $nomFichier,
        $extensionFichier,
        $idTypeVideo,
        $dateCreation,
        $visibilite,
        $nombreVisionnage,
        $ordre
    ]);

    $idVideo = $db->lastInsertId();

    header("Location: /administration/gestion_videos.php?idVideo=" . $idVideo);
    die();
}

?>
<?php //todo faire un message d'erreur et un message de confirmation pour afficher en vert ou rouge ?>
<div id="messageConfirmation" <?php if (!isset($_SESSION['messageConfirmation'])) {
    echo "style='display: none;'";
} ?>>
    <?php
    if (isset($_SESSION['messageConfirmation'])) {
        echo $_SESSION['messageConfirmation'];
        unset($_SESSION['messageConfirmation']);
    }
    ?>
</div>


<div class="container">
    <form action="gestion_videos.php" method="post" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= !empty($nom) ? $nom : "" ?>" required>

        <label for="type">Type :</label>
        <select id="type" name="type" required>
            <option value="">Choisissez un type de vidéo</option>
            <?php
            foreach ($listeTypesVideo as $typeVideo) {
                ?>
                <option value="<?= $typeVideo->id ?>" <?php if ($typeVideo->id == $idTypeVideo) {
                    echo "selected";
                } ?> ><?= $typeVideo->nom ?></option>
                <?php
            }
            ?>
        </select>

        <label for="fichier">Sélectionnez un fichier :</label>
        <input type="file" name="fichier" id="fichier">


        <label for="visibilite">La Vidéo est visible par les élèves : :</label>
        <input type="checkbox" id="visibilite" name="visibilite" <?php if ($visibilite == 1) {
            echo "checked";
        } ?> value="<?= !empty($nom) ? $nom : "" ?>">


        <input type="hidden" name="idVideo" value="<?= $idVideo ?>">
        <br>
        <button type="submit"><?= !empty($idVideo) ? "Modifier une Vidéo" : "Ajouter une Vidéo" ?></button>
    </form>
    <form action="gestion_videos.php" method="post" onsubmit="return confirmSuppression();">
        <label for="suppression">Suppression de la vidéo:</label>
        <input type="hidden" name="idVideo" value="<?= $idVideo ?>">
        <button type="submit" id="suppression" name="suppression" value="1">Suppression</button>
    </form>
    <br>
</div>

<?php
include_once "../include/footer.php";
?>

<script>
    function confirmSuppression() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette vidéo?");
    }
</script>

