<?php

//Attention: le fichier incluant menu.php doit avoir posseder les lignes suivantes:
//session_start();
//include_once "include/configuration.php";
//include_once "include/header.php";

if (!isset($db)) {
    die();
}
?>
<header>
    <h1>Mon Site Web</h1>
</header>

<nav>
    <a href="../index.php">Accueil</a>
    <a href="#">À Propos</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
    <?php if (isset($_SESSION['statut_etudiant']) && $_SESSION['statut_etudiant'] == 5) { ?>
        <a href="../acces_etudiant/accueil.php">Accès étudiant</a>
    <?php } else { ?>
        <a href="../connexion.php">Accès étudiant</a>
    <?php } ?>
</nav>
