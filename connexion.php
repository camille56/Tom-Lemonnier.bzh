<?php

include_once "include/configuration.php";
include_once "include/header.php";

session_start();
if (!isset($db)){
    die();
}

//récupération des inputs de connexion
$connexion=false;
$messageConnexion="";
if (!empty($_POST['username']) && !empty($_POST['password'])) {

    //todo: sécurisation des input avec peut etre filter_input()
    //todo: utilisation d'un identifiant de session securisé?

    $inputNom = $_POST['username'];
    $inputPassword = $_POST['password'];

    $requeteStatut = "select * from ca_eleve where username = ?";
    $resultatStatut = $db->prepare($requeteStatut);
    $resultatStatut->execute([$inputNom]);

    if ($statut = $resultatStatut->fetch(PDO::FETCH_OBJ)){
        $statutUtilisateur = $statut->statut;
        $nomUtilisateur = $statut->username;
        $passwordUtilisateur = $statut->password;

        if ($inputPassword === $passwordUtilisateur) {
            $connexion=true;
            $_SESSION['statut_etudiant'] = $statutUtilisateur;
            header("Location: /acces_etudiant/accueil.php");
            exit();
        }
    }else{
        $messageConnexion = "Il doit y avoir une erreur d'identifiant ou de mot de passe.";
    }

}

?>

    <div class="login-container">
        <h2>Connexion</h2>
        <form id="loginForm" action="connexion.php" method="post">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <section>
        <div>
            <?php
            if (!$connexion) {
                ?><span><?php
                echo $messageConnexion;
                ?></span><?php
            }
            ?>
        </div>
    </section>


<?php
include_once "include/footer.php";
