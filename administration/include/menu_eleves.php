<?php
include_once "../include/configuration.php";

if (!isset($db)) {
    die();
}

$requeteEleves = "SELECT id, nom, prenom FROM ca_eleve";
$resultatEleves = $db->query($requeteEleves);

echo '<div class="menu">';
echo '<h2>Liste des Élèves</h2>';
echo '<ul>';
while ($eleve = $resultatEleves->fetch(PDO::FETCH_ASSOC)) {
    echo '<li><a href="gestion_eleves.php?idEleve=' . $eleve['id'] . '">' . $eleve['nom'] . ' ' . $eleve['prenom'] . '</a></li>';
}
echo '</ul>';
echo '</div>';

?>

<div class="menu">
    <a href="/administration/gestion_eleves.php"><h2>Ajouter un nouvel élève</h2></a>
</div>
