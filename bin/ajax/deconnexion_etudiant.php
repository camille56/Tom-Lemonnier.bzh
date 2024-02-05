<?php


// Démarrer la session
session_start();

// Détruire la session si l'utilisateur est connecté
if (isset($_SESSION['statut_etudiant'])) {

    session_destroy();
    echo json_encode(['success' => true]);

} else {

    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);

}
