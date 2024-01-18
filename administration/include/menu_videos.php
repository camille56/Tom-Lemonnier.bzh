<?php
include_once "../include/configuration.php";

if (!isset($db)) {
    die();
}

$requeteEleves = "SELECT id, nom FROM ca_video";
$resultatEleves = $db->query($requeteEleves);

echo '<div class="menu">';
echo '<h2>Liste des Vidéos</h2>';
echo '<ul>';
while ($video = $resultatEleves->fetch(PDO::FETCH_ASSOC)) {
    echo '<li><a href="gestion_videos.php?idVideo=' . $video['id'] . '">' . $video['nom'] . '</a></li>';
}
echo '</ul>';
echo '</div>';

?>

<div class="menu">
    <a href="/administration/gestion_videos.php"><h2>Ajouter une nouvelle vidéo</h2></a>
</div>