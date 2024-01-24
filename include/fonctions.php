<?php

include_once __DIR__ . "/../include/configuration.php";


/** Créer une chaine de characters aléatoire qui peut être de chiffre et/opu de lettre en fonction des paramètres.
 * 1er paramètre ets le nombre de lettres attendu.
 * 2ᵉ paramètre est le nombre de chiffres attendu.
 * @param $nbLettres
 * @param $nbChiffres
 * @return string
 */
function genererStringAleatoire($nbLettres, $nbChiffres): string
{

    $lettres = 'abcdefghijklmnopqrstuvwxyz';

    // Générer la suite de lettres aléatoires
    $suiteLettres = '';
    for ($i = 0; $i < $nbLettres; $i++) {
        $suiteLettres .= $lettres[rand(0, strlen($lettres) - 1)];
    }

    // Générer la suite de chiffres aléatoires
    $suiteChiffres = '';
    for ($i = 0; $i < $nbChiffres; $i++) {
        $suiteChiffres .= rand(0, 9);
    }

    // Concaténer les deux suites
    $suiteAleatoire = $suiteLettres . $suiteChiffres;

    return $suiteAleatoire;
}

/**
 * Fonction de sauvegarde de fichier vidéo.
 *
 *  Cette fonction permet d'uploader un fichier vidéo sur le serveur, en remplaçant éventuellement
 *  une vidéo existante associée à un identifiant spécifique. Elle effectue des vérifications sur
 *  la taille, le format du fichier, gère la suppression de l'ancienne vidéo le cas échéant,
 *  et renvoie un tableau indiquant le résultat de l'opération, comprenant le nom du fichier,
 *  l'état de sauvegarde (réussie ou échouée), ainsi que des messages de validation ou d'erreur.
 *
 * @param $fichierVideo
 * @param string $idVideo
 * @return array
 */
function sauvegardeVideo($fichierVideo, string $idVideo = ""): array
{
    global $db;
    $messageErreur = "";
    $messageValidation = "";
    $uploadOk = true;
    $nomFichier = genererStringAleatoire(12, 2);

    //Si un paramètre $idVideo est présent, on doit effacer l'ancienne vidéo présente sur le serveur.
    if (!empty($idVideo)) {
        $nomVideoAEffacer = "";
        $requete = "select nom_fichier_video from ca_video where id = ?";
        $resultat = $db->prepare($requete);
        $resultat->execute([$idVideo]);

        if ($video = $resultat->fetch(PDO::FETCH_OBJ)) {
            $nomVideoAEffacer = $video->nom_fichier_video;
        }
        if (!empty($nomVideoAEffacer)) {

            $chemin_fichier = __DIR__ . "/../fichiers/videos/" . $nomVideoAEffacer;

            //Unlink pour supprimer le fichier
            unlink($chemin_fichier);

        }
    }

    $cheminDossier = __DIR__ . "/../fichiers/videos";

    // Vérifier si le dossier existe et création s'il n'existe pas.
    if (!file_exists($cheminDossier)) {
        mkdir($cheminDossier, 0777, true);
    }

    $typeFichier = $fichierVideo['type'];
    $tailleFichier = $fichierVideo["size"];
    $nomTemporaireFichier = $fichierVideo["tmp_name"];

    $cheminFichier = $cheminDossier . '/' . $nomFichier;

    // Vérifier la taille du fichier (ici, limite à 100 Mo)
    if ($tailleFichier > 100000000) {
        $messageErreur = "Désolé, votre fichier est trop volumineux.";
        $uploadOk = false;
    }

    // Autoriser certains formats de fichier vidéo.
    $formatAutorises = ["mp4", "avi", "mov", "video/mp4"];
    if (!in_array($typeFichier, $formatAutorises)) {
        $messageErreur = "Seuls les fichiers de type " . implode(", ", $formatAutorises) . " sont autorisés.";
        $uploadOk = false;
    }

    if ($uploadOk) {
        if (move_uploaded_file($nomTemporaireFichier, $cheminFichier)) {
            $messageValidation = "Le fichier " . basename($nomFichier) . " a été uploadé avec succès.";

        } else {
            $messageErreur = "Une erreur s'est produite lors de l'upload du fichier.";
        }

    } else {
        $messageErreur .= " Votre fichier n'a pas été uploadé.";
    }

    if ($uploadOk) {
        $enregistrement = ["nomDuFichier" => $nomFichier, "saveOk" => true, "message" => $messageValidation];
    } else {
        $enregistrement = ["nomDuFichier" => $nomFichier, "saveOk" => false, "message" => $messageErreur];
    }

    return $enregistrement;

}

