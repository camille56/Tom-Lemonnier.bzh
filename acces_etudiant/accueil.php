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


    <section>
        <h1>Accès étudiant</h1>
    </section>

    <section>
        <h2>Message important</h2>
        <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis ipsum provident quae! Aliquam consequatur cumque delectus, dolore ea eius hic id illo iusto minima numquam perspiciatis placeat quidem sunt tenetur.</span>
    </section>


    <section>

        <?php
        //Récupération des different types de vidéos.
        $listeTypesVideo = array();
        $requeteTypeVideo = "select * from ca_type_video";
        $resultatTypeVideo = $db->prepare($requeteTypeVideo);
        $resultatTypeVideo->execute();
        while ($typeVideo = $resultatTypeVideo->fetch(PDO::FETCH_OBJ)) {
            $listeTypesVideo[] = $typeVideo;
        }
        ?>
        <h2>Vous avez accès à ces séries de vidéos :</h2>
        <ul>


        <?php
        foreach ($listeTypesVideo as $typeVideo) {
            $nombreDeVideo="";
            $requeteVideo = "select count(*) as count from ca_video where type = ? and ca_video.visibilite = 1";
            $resultatVideo = $db->prepare($requeteVideo);
            $resultatVideo->execute([$typeVideo->id]);
            if ($nombreResultat=$resultatVideo->fetch(PDO::FETCH_OBJ)){
                $nombreDeVideo=$nombreResultat->count;
            }
            ?>
            <li><a href="listeTypeVideo.php?idType=<?=$typeVideo->id?>"><?=$typeVideo->nom?></a></li>
            <span><?= !empty($nombreDeVideo)? $nombreDeVideo." Vidéo(s) disponible(s)" : " Aucunes vidéos disponibles"?></span>

        <?php } ?>
        </ul>

    </section>


    <section>


        <?php
        //Récupération des 5 dernières vidéos.
        $listeVideos = array();
        $requeteVideo = "select * from ca_video where ca_video.visibilite = 1 order by date_creation desc limit 5";
        $resultatVideo = $db->prepare($requeteVideo);
        $resultatVideo->execute();
        while ($video = $resultatVideo->fetch(PDO::FETCH_OBJ)) {
        $listeVideos[] = $video;
        }
        ?>
        <h2>Les dernières vidéos :</h2>
        <ul>
            <?php foreach ($listeVideos as $video){ ?>
        <li><a href="detailVideo.php?id=<?=$video->id?>"><?=$video->nom?></a></li>
        <?php } ?>
        </ul>

<!--        --><?php
//        //Récupération des different types de vidéos.
//        $listeTypesVideo = array();
//        $requeteTypeVideo = "select * from ca_type_video";
//        $resultatTypeVideo = $db->prepare($requeteTypeVideo);
//        $resultatTypeVideo->execute();
//        while ($typeVideo = $resultatTypeVideo->fetch(PDO::FETCH_OBJ)) {
//            $listeTypesVideo[] = $typeVideo;
//        }
//
//        //Récupération de l'ensemble des vidéos.
//        $listeVideos = array();
//        $requeteVideo = "select * from ca_video";
//        $resultatVideo = $db->prepare($requeteVideo);
//        $resultatVideo->execute();
//        while ($video = $resultatVideo->fetch(PDO::FETCH_OBJ)) {
//            $listeVideos[] = $video;
//        }
//        ?>
<!---->
<!--        <div class="info-message">-->
<!--            <p>Ceci est un message d'information important.</p>-->
<!--        </div>-->
<!---->
<!--        --><?php
//        foreach ($listeTypesVideo as $typeVideo) {
//            ?>
<!--            <div class="category---><?php //= $typeVideo->nom ?><!--">-->
<!--                <h2 class="category-title">--><?php //= $typeVideo->nom ?><!--</h2>-->
<!--                <ul class="video-list">-->
<!--                    --><?php
//                    foreach ($listeVideos as $video) {
//                        if ($video->type === $typeVideo->id) {
//                            ?>
<!--                            <li class="video-item"><a href="detailVideo.php?id=--><?php //= $video->id ?><!--">--><?php //= $video->nom ?><!--</a>-->
<!--                            </li>-->
<!--                            --><?php
//                        }
//                    }
//                    ?>
<!--                </ul>-->
<!--            </div>-->
<!--            --><?php
//        }
//        ?>

    </section>

<?php
include_once "../include/footer.php";
?>