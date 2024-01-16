<?php

include_once __DIR__."/../class/Database.php";
include_once __DIR__."/fonctions.php";

$localhost="";
$dbname="";
$userName="";
$password="";

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
    $localhost="localhost";
    $dbname="tom-lemonnier";
    $userName="root";
    $password="aMqV9UIA*MRkpPKi";
} else {
    $localhost="";
    $dbname="tom.lemonnier";
    $userName="Tom8484";
    $password="EwenEtGael";
}

// Utilisation de la classe Database
$database = new Database($localhost, $dbname, $userName, $password);
$db = $database->getConnection();

?>
<link rel="stylesheet" href="../style/style.css">

<!--import Font Awesome Kit-->
<script src="https://kit.fontawesome.com/102153a728.js" crossorigin="anonymous"></script>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

