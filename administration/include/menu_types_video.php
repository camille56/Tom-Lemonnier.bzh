<?php

include_once "../include/configuration.php";

if (!isset($db)) {
    die();
}

$requeteTypeVideo = "SELECT id, nom FROM ca_type_video";
$resultatTypeVideo = $db->query($requeteTypeVideo);

echo '<div class="menu">';
echo '<h2>Liste des types de vidéos</h2>';
echo '<ul>';
while ($type_Video = $resultatTypeVideo->fetch(PDO::FETCH_ASSOC)) {
    echo '<li><a href="gestion_types_video.php?idTypeVideo=' . $type_Video['id'] . '">' . $type_Video['nom'] . '</a></li>';
}
echo '</ul>';
echo '</div>';

?>
<div class="menu">
    <a href="/administration/gestion_types_video.php"><h2>Ajouter un nouveau type de vidéo</h2></a>
</div>