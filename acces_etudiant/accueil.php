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
//Récupération des different types de vidéos.
$listeTypesVideo = array();
$requeteTypeVideo = "select * from ca_type_video";
$resultatTypeVideo = $db->prepare($requeteTypeVideo);
$resultatTypeVideo->execute();
while ($typeVideo = $resultatTypeVideo->fetch(PDO::FETCH_OBJ)) {
    $listeTypesVideo[] = $typeVideo;
}

//Récupération de l'ensemble des vidéos.
$listeVideos = array();
$requeteVideo = "select * from ca_video";
$resultatVideo = $db->prepare($requeteVideo);
$resultatVideo->execute();
while ($video = $resultatVideo->fetch(PDO::FETCH_OBJ)) {
    $listeVideos[] = $video;
}
?>

    <section>

        <div class="info-message">
            <p>Ceci est un message d'information important.</p>
        </div>

        <?php
        foreach ($listeTypesVideo as $typeVideo) {
            ?>
            <div class="category-<?= $typeVideo->nom ?>">
                <h2 class="category-title"><?= $typeVideo->nom ?></h2>
                <ul class="video-list">
                    <?php
                    foreach ($listeVideos as $video) {
                        if ($video->type === $typeVideo->id) {
                            ?>
                            <li class="video-item"><a href="detailVideo.php"><?= $video->nom ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>

    </section>

<?php
include_once "../include/footer.php";
?>