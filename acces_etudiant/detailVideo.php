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

    <!-- Titre de la vidéo -->
    <h1 id="videoTitle">Titre de la vidéo</h1>

    <!-- Commentaire de la vidéo -->
    <p id="videoComment">Commentaire de la vidéo (s'il existe)</p>

    <!-- Lecteur vidéo -->
    <video id="videoPlayer" width="640" height="360" controls>
        <source src="lien_de_la_video.mp4" type="video/mp4">
        Votre navigateur ne prend pas en charge la lecture de la vidéo.
    </video>

<?php
include_once "../include/footer.php";
?>