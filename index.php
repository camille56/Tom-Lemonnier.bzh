<?php

include_once "include/configuration.php";
include_once "include/header.php";
if (!isset($db)) {
    die();
}
session_start();
?>


    <body>
<?php
include_once "include/menu.php";
?>

    <section>
        <h2>Bienvenue sur notre site web !</h2>
        <p>C'est une version plus évoluée de la page d'accueil. Ajoutez ici le contenu que vous souhaitez afficher.</p>
    </section>

<?php
include_once "include/footer.php";
?>