<?php

include_once "../include/configuration.php";

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

$nom="";
$type="";
$dateCreation	="";
$visibilite="";
$nombreVisionnage="";
$ordre="";

$idVideo = !empty($_POST['idVideo']) ? $_POST['idVideo'] : "";
$formulaireComplet = false;

if (!empty($_GET['idVideo'])) {
    //Si un id est disponible dans les paramètres Get alors une vidéo a été selectionnée dans le menu.
    $idVideo = $_GET['idVideo'];

    $requeteEleve = "select * from ca_Video where id = ?";
    $resultatEleve = $db->prepare($requeteEleve);
    $resultatEleve->execute([$idVideo]);

    if ($video = $resultatEleve->fetch(PDO::FETCH_OBJ)) {
        $nom = $video->nom;
        $prenom = $video->prenom;
        $username = $video->username;
        $password = $video->password;
        if ($video->date_naissance) {
            $dateDeNaissance = $video->date_naissance;
        }
        $commentaire = $video->commentaire;
        $statut = $video->statut;
        $idAcces = $video->acces;
        $dateDebutAcces = $video->date_debut_acces;
        $dateFinAcces = $video->date_fin_acces;

    }

}

if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['username']) && !empty($_POST['password'])) {

    $formulaireComplet = true;

    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    if (!empty($_POST['date_de_naissance'])) {
        $dateDeNaissance = htmlspecialchars($_POST['date_de_naissance']);
    }
}

//Suppression d'une vidéo
if (!empty($_POST['suppression']) && !empty($idVideo)) {
    $requeteSupressionEleve = "delete from ca_eleve where id = ?";
    $resultatSupressionEleve = $db->prepare($requeteSupressionEleve);
    $resultatSupressionEleve->execute([$idVideo]);

    header("Location: /administration/gestion_eleves.php");
    die();
}

if ($formulaireComplet && !empty($idVideo)) {
    //Update d'une vidéo.

    $requeteUpdateEleve = "update ca_eleve set nom = ?, prenom = ?, username = ?, password= ?, date_naissance = ?, commentaire = ?,
                            statut = ?, acces = ?, date_debut_acces= ?, date_fin_acces= ? where id = ?";
    $resultatUpdateEleve = $db->prepare($requeteUpdateEleve);
    $resultatUpdateEleve->execute([$nom, $prenom, $username, $password, $dateDeNaissance, $commentaire, $statut, $idAcces, $dateDebutAcces, $dateFinAcces, $idVideo]);

    header("Location: /administration/gestion_eleves.php?idVideo=" . $idVideo);
    die();

} elseif ($formulaireComplet && empty($idVideo)) {
    //create d'un vidéo.

    $dateInscription = date("Y-m-d");
    $idAcces = 2;

    $requeteCreateEleve = "insert into ca_eleve (
                        nom,
                        prenom,
                        username,
                        password,
                        date_naissance,
                        date_inscription,
                        commentaire,
                        statut,
                        acces,
                        date_debut_acces,
                        date_fin_acces
                      ) values (
                        ?,?,?,?,?,?,?,?,?,?,?
                                )";
    $resultatCreateEleve = $db->prepare($requeteCreateEleve);
    $resultatCreateEleve->execute([
        $nom,
        $prenom,
        $username,
        $password,
        $dateDeNaissance,
        $dateInscription,
        $commentaire,
        $statut,
        $idAcces,
        $dateDebutAcces,
        $dateFinAcces
    ]);

    $idVideo = $db->lastInsertId();

    header("Location: /administration/gestion_eleves.php?idVideo=" . $idVideo);
    die();
}

?>


<div class="container">
    <form action="gestion_eleves.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= !empty($nom) ? $nom : "" ?>" required>

        <label for="type">Type :</label>
        <select id="type" name="type" required>
            <option value="">Choisissez un type de vidéo</option>
        </select>

        <label for="fichier">Sélectionnez un fichier :</label>
        <input type="file" name="fichier" id="fichier">


        <label for="visibilite">La Vidéo est visible par les élèves : :</label>
        <input type="checkbox" id="visibilite" name="visibilite" value="<?= !empty($nom) ? $nom : "" ?>" required>


        <input type="hidden" name="idVideo" value="<?= $idVideo ?>">
        <br>
        <button type="submit"><?= !empty($idVideo) ? "Modifier une Vidéo" : "Ajouter une Vidéo" ?></button>
    </form>
    <form action="gestion_videos.php" method="post">
        <label for="suppression">Suppression de la vidéo:</label>
        <input type="hidden" name="idVideo" value="<?= $idVideo ?>">
        <button type="submit" id="suppression" name="suppression" value="1">Suppression</button>
    </form>
    <br>
</div>

<?php
include_once "../include/footer.php";
?>

<?php

/**Sauvegarde un fichier vidéo sur le serveur pour un id de vidéo.
 * Efface et remplace un fichier vidéo s'il est déjà existant pour cet id.
 *
 * @return void
 */
function sauvegardeVideo()
{
    
}
?>