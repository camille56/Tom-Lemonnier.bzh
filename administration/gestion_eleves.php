<?php
include_once "../include/configuration.php";

session_start();
if ($_SESSION['statut'] !== 2 && $_SESSION['statut'] !== 3) {
    header("Location: /");
    die();
}

include_once "../include/header.php";
include_once "include/menu.php";
include_once "include/menu_eleves.php";


if (!isset($db)) {
    die();
}

$nom = "";
$prenom = "";
$username = "";
$password = "";
$dateDeNaissance = "";
$dateInscription = "";
$commentaire = "";
$statut = 5; //par défault le statut est 5, celui d'un élève.
$idAcces = "";
$dateDebutAcces = "";
$dateFinAcces = "";

$idEleve = !empty($_POST['idEleve']) ? $_POST['idEleve'] : "";
$formulaireComplet = false;

if (!empty($_GET['idEleve'])) {
    //Si un id est disponible dans les paramètres Get alors un élève a été selectionné dans le menu.
    $idEleve = $_GET['idEleve'];

    $requeteEleve = "select * from ca_eleve where id = ?";
    $resultatEleve = $db->prepare($requeteEleve);
    $resultatEleve->execute([$idEleve]);

    if ($eleve = $resultatEleve->fetch(PDO::FETCH_OBJ)) {
        $nom = $eleve->nom;
        $prenom = $eleve->prenom;
        $username = $eleve->username;
        $password = $eleve->password;
        if ($eleve->date_naissance) {
            $dateDeNaissance = $eleve->date_naissance;
        }
        $commentaire = $eleve->commentaire;
        $statut = $eleve->statut;
        $idAcces = $eleve->acces;
        $dateDebutAcces = $eleve->date_debut_acces;
        $dateFinAcces = $eleve->date_fin_acces;

    }

}

if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['username']) && !empty($_POST['password'])) {

    $formulaireComplet = true;

    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $commentaire = htmlspecialchars($_POST['commentaireEleve']);
    if (!empty($_POST['date_de_naissance'])) {
        $dateDeNaissance = htmlspecialchars($_POST['date_de_naissance']);
    }
}

//Suppression d'un élève
if (!empty($_POST['suppression']) && !empty($idEleve)) {
    $requeteSupressionEleve = "delete from ca_eleve where id = ?";
    $resultatSupressionEleve = $db->prepare($requeteSupressionEleve);
    $resultatSupressionEleve->execute([$idEleve]);

    header("Location: /administration/gestion_eleves.php");
    die();
}

if ($formulaireComplet && !empty($idEleve)) {
    //Update d'un élève.

    $requeteUpdateEleve = "update ca_eleve set nom = ?, prenom = ?, username = ?, password= ?, date_naissance = ?, commentaire = ?,
                            statut = ?, acces = ?, date_debut_acces= ?, date_fin_acces= ? where id = ?";
    $resultatUpdateEleve = $db->prepare($requeteUpdateEleve);
    $resultatUpdateEleve->execute([$nom, $prenom, $username, $password, $dateDeNaissance, $commentaire, $statut, $idAcces, $dateDebutAcces, $dateFinAcces, $idEleve]);

    header("Location: /administration/gestion_eleves.php?idEleve=" . $idEleve);
    die();

} elseif ($formulaireComplet && empty($idEleve)) {
    //create d'un élève.

    $dateInscription = date("Y-m-d");
    $idAcces = 2;

    $requeteCreateEleve = "insert into ca_eleve (
                        nom,
                        prenom,
                        username,
                        password,
                        date_naissance,
                        date_inscription,
                        commentaire,
                        statut,
                        acces,
                        date_debut_acces,
                        date_fin_acces
                      ) values (
                        ?,?,?,?,?,?,?,?,?,?,?
                                )";
    $resultatCreateEleve = $db->prepare($requeteCreateEleve);
    $resultatCreateEleve->execute([
        $nom,
        $prenom,
        $username,
        $password,
        $dateDeNaissance,
        $dateInscription,
        $commentaire,
        $statut,
        $idAcces,
        $dateDebutAcces,
        $dateFinAcces
    ]);

    $idEleve = $db->lastInsertId();

    header("Location: /administration/gestion_eleves.php?idEleve=" . $idEleve);
    die();
}

?>


<div class="container">
    <form action="gestion_eleves.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= !empty($nom) ? $nom : "" ?>" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= !empty($prenom) ? $prenom : "" ?>" required>

        <label for="username">Username :</label>
        <input type="text" id="username" name="username" value="<?= !empty($username) ? $username : "" ?>" required>

        <label for="password">Password :</label>
        <input type="text" id="password" name="password" value="<?= !empty($password) ? $password : "" ?>" required>

        <label for="commentaireEleve">Commentaire :</label>
        <textarea id="commentaireEleve" name="commentaireEleve"><?= !empty($commentaire) ? $commentaire : "" ?></textarea>

        <label for="date_de_naissance">Date de naissance :</label>
        <input type="date" id="date_de_naissance" name="date_de_naissance"
               value="<?= !empty($dateDeNaissance) ? $dateDeNaissance : "" ?>">

        <input type="hidden" name="idEleve" value="<?= $idEleve ?>">
        <br>
        <button type="submit"><?= !empty($idEleve) ? "Modifier un Élève" : "Ajouter un Élève" ?></button>
    </form>
    <form action="gestion_eleves.php" method="post" onsubmit="return confirmSuppression();">
        <label for="suppression">Suppression de l'élève:</label>
        <input type="hidden" name="idEleve" value="<?= $idEleve ?>">
        <button type="submit" id="suppression" name="suppression" value="1">Suppression</button>
    </form>
    <br>
</div>

<?php
include_once "../include/footer.php";
?>

<script>
    function confirmSuppression() {
        return confirm("Êtes-vous sûr de vouloir supprimer cet élève?");
    }
</script>