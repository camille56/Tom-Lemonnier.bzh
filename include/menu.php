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
    <?php if (!empty($_SESSION['statut_etudiant'])){
        ?>
        <button id="Bouton_deconnexion">Déconnexion</button>
        <?php
    } ?>
</nav>

<script>
    $(document).ready(function () {
        // Attacher un gestionnaire de clic au bouton de déconnexion
        $("#Bouton_deconnexion").on("click", function () {
            if (confirm("Êtes-vous sûr de vouloir vous déconnecter?")) {
                // Appeler le fichier PHP via AJAX
                $.ajax({
                    type: "POST",
                    url: "/bin/ajax/deconnexion_etudiant.php",
                    success: function (data) {
                        // Rediriger ou effectuer d'autres actions après la déconnexion
                        window.location.href = "/index.php";
                    },
                    error: function (error) {
                        // Gérer les erreurs si nécessaire
                        console.error("Erreur lors de la déconnexion:", error);
                    }
                });
            }
        });
    });
</script>