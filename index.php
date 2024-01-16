<?php

include_once "include/configuration.php";
include_once "include/header.php";

if (!isset($db)){
    die();
}


?>


<body>
<header>
    <h1>Mon Site Web</h1>
</header>

<nav>
    <a href="#">Accueil</a>
    <a href="#">À Propos</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
    <a href="page_connexion.html">Connexion</a>
</nav>

<section>
    <h2>Bienvenue sur notre site web !</h2>
    <p>C'est une version plus évoluée de la page d'accueil. Ajoutez ici le contenu que vous souhaitez afficher.</p>
</section>

<footer>

</footer>
</body>
<?php
include_once "include/footer.php";
?>