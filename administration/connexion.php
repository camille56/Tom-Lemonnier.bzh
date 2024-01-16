<?php
include_once "../include/configuration.php";

if (!isset($db)) {
    die();
}

session_start();

//définition des variables.
$inputNom = "";
$inputPassword = "";

$statutUtilisateur = "";
$nomUtilisateur = "";
$passwordUtilisateur = "";

$messageConnexion = "";

//récupération des inputs de connexion
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $inputNom = $_POST['username'];
    $inputPassword = $_POST['password'];

    $requeteStatut = "select * from ca_utilisateur where nom = ?";
    $resultatStatut = $db->prepare($requeteStatut);
    $resultatStatut->execute([$inputNom]);
    while ($statut = $resultatStatut->fetch(PDO::FETCH_OBJ)) {
        $statutUtilisateur = $statut->statut;
        $nomUtilisateur = $statut->nom;
        $passwordUtilisateur = $statut->password;

        if ($inputPassword === $passwordUtilisateur) {
            $_SESSION['statut'] = $statutUtilisateur;
            // Redirection en cas de connexion réussie
            header("Location: accueil.php");
            exit();
        } else {
            $messageConnexion = "Il doit y avoir une erreur d'identifiant ou de mot de passe.";
        }

    }

}


include_once "../include/header.php";
?>
    <section>
        <div class="form_content">
            <form action="connexion.php" method="post">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required><br>

                <input type="submit" value="Se connecter">
            </form>
        </div>
    </section>
    <section>
        <div>
            <?php
            if (!empty($messageConnexion)) {
                ?><span><?php
                echo $messageConnexion;
                ?></span><?php
            }
            ?>
        </div>
    </section>
<?php
include_once "../include/footer.php";
?>