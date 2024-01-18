<?php
include_once "../include/configuration.php";
?>


<ul class="tabs">
    <li class="tab" onclick="openTab('tab1')">Accueil</li>
    <li class="tab" onclick="openTab('tab2')">Videos</li>
    <li class="tab" onclick="openTab('tab3')">Elèves</li>
    <li class="tab" id="Bouton_deconnexion" >Déconnexion</li>
</ul>

<div id="tab1" class="content">
    <h2>Accueil</h2>
    <div><a href="/administration/accueil.php">Accueil</a></div>
    <p>Bienvenue dans l'administration</p>
</div>

<div id="tab2" class="content">
    <h2>Videos</h2>
    <div>
        <a href="/administration/gestion_videos.php">Création de vidéo</a>
    </div>
    <div>
        <a href="#">Création de catégories de vidéo</a>
    </div>

</div>

<div id="tab3" class="content">
    <h2>Elèves</h2>
    <div>
        <a href="/administration/gestion_eleves.php">Liste des élèves</a>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Afficher le premier onglet par défaut
        openTab('tab1');
    });

    function openTab(tabName) {
        // Masquer tous les contenus d'onglets
        let tabs = document.getElementsByClassName('content');
        for (let i = 0; i < tabs.length; i++) {
            tabs[i].style.display = 'none';
        }

        // Désélectionner tous les onglets
        let tabButtons = document.getElementsByClassName('tab');
        for (let i = 0; i < tabButtons.length; i++) {
            tabButtons[i].classList.remove('active');
        }

        // Afficher le contenu de l'onglet sélectionné
        document.getElementById(tabName).style.display = 'block';

        // Sélectionner l'onglet actuel
        let currentTabButton = document.querySelector('.tab[onclick="openTab(\'' + tabName + '\')"]');
        if (currentTabButton) {
            currentTabButton.classList.add('active');
        }
    }

    $(document).ready(function () {
        // Attacher un gestionnaire de clic au bouton de déconnexion
        $("#Bouton_deconnexion").on("click", function () {
            // Appeler le fichier PHP via AJAX
            $.ajax({
                type: "POST",
                url: "/administration/bin/ajax/deconnexion_admin.php",
                success: function (data) {
                    // Rediriger ou effectuer d'autres actions après la déconnexion
                    window.location.href = "/index.php";
                },
                error: function (error) {
                    // Gérer les erreurs si nécessaire
                    console.error("Erreur lors de la déconnexion:", error);
                }
            });
        });
    });


</script>