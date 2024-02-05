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

$idTypeVideo="";
if (!empty($_GET['idType'])){
    $idTypeVideo=$_GET['idType'];
}else{
    header("Location: ../index.php");
    die();
}
//Récupération des informations sur le type de vidéo.
$nomType="";
$commentaireType="";
$requeteTypeVideo = "select * from ca_type_video where id = ?";
$resultatTypeVideo = $db->prepare($requeteTypeVideo);
$resultatTypeVideo->execute([$idTypeVideo]);
if ($typeVideo=$resultatTypeVideo->fetch(PDO::FETCH_OBJ)){
    $nomType=$typeVideo->nom;
    $commentaireType=$typeVideo->commentaire;
}

//Récupération de l'ensemble des vidéos du type de la page.
$listeVideos = array();
$requeteVideo = "select * from ca_video where type = ? and ca_video.visibilite = 1 order by date_creation desc"; //todo : faire un order by ordre quand l ordre sera définit en admin
$resultatVideo = $db->prepare($requeteVideo);
$resultatVideo->execute([$idTypeVideo]);
while ($video = $resultatVideo->fetch(PDO::FETCH_OBJ)) {
    $listeVideos[] = $video;
}

include_once "../include/menu.php";
?>

<section>
    <h1><?=$nomType?></h1>
    <span><?=$commentaireType?></span>
    <ul>
    <?php foreach ($listeVideos as $video){ ?>
        <li><a href="detailVideo.php?id=<?=$video->id?>"><?=$video->nom?></a></li>
    <?php } ?>
    </ul>
</section>



<?php
include_once "../include/footer.php";
?>
