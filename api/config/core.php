<?php
// Afficher les rapports d'erreur
ini_set('display_errors', 1);
error_reporting(E_ALL);
 
// Url de la page d'accueil
$home_url="http://localhost/api/";
 
// Page donnée en paramètre d'url, la page par défaut est "1"
$page = isset($_GET['page']) ? $_GET['page'] : 1;
 
// Définir le nombre d'enregistrements par page.
$records_per_page = 5;
 
// Calculer la clause limite pour la requête.
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>