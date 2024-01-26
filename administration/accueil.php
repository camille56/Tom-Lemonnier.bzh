<?php

include_once "../include/configuration.php";
include_once "../include/header.php";

if (!isset($db)) {
    die();
}
session_start();
if ($_SESSION['statut'] !== 2 && $_SESSION['statut'] !== 3) {
    header("Location: /");
    die();
}

//récupération de l'utilisateur
$nomStatut="";
$idStatut=$_SESSION['statut'];
$requeteStatut="select * from ca_statut where id = ?";
$resultatStatut=$db->prepare($requeteStatut);
$resultatStatut->execute([$idStatut]);
if ($statut=$resultatStatut->fetch(PDO::FETCH_OBJ)){
    $nomStatut=$statut->nom;
}

include_once "include/menu.php";
?>

    <header>
        <h1>Bonjour <?php echo $nomStatut; ?></h1>
    </header>

    <section>
        <p>Bienvenue sur l'administration</p>
        <!-- Vous pouvez ajouter d'autres éléments de contenu ici -->
    </section>

<?php
include_once "../include/footer.php";
?>