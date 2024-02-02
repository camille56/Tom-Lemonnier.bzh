<?php
include_once "../include/configuration.php";
include_once "../include/header.php";

if (!isset($db)) {
    die();
}
session_start();
if ($_SESSION['statut_etudiant'] !== 5) {
    header("Location: ../index.php");
    die();
}
include_once "../include/menu.php";
?>

<?php
//Récupération de l'id de la vidéo et de ses informations.
$idVideo = "";
if (isset($_GET['id'])) {
    $idVideo = $_GET['id'];
}
$requeteVideo = "select * from ca_video where id = ?";
$resultatVideo = $db->prepare($requeteVideo);
$resultatVideo->execute([$idVideo]);
if ($video = $resultatVideo->fetch(PDO::FETCH_OBJ)) {
    $fichiervideo="/fichiers/videos/".$video->nom_fichier_video;
    ?>

    <section>
        <h1 id="videoTitle"><?= $video->nom ?></h1>

        <p id="videoComment"><?= $video->commentaire ?></p>

        <!-- Lecteur vidéo -->
        <video id="videoPlayer" width="640" height="360" controls>
            <source src="<?= $fichiervideo ?>"
                    type="video/mp4">
            <p>Votre navigateur ne prend pas en charge la lecture de la vidéo.</p>
        </video>

    </section>

    <?php
}

?>


<?php
include_once "../include/footer.php";
?>