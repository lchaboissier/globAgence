<?php
// 
// POINT D'ENTREE DE L'APPLICATION
//

session_start();
// Debug : forcer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors',true);
ini_set('display_startup_errors',true);

// Inclure tous les fichiers du framework de manière automatique
include ('../include.php');

// Récupérer les paramètres "route" et "action" de l'url
$route='';
if (isset($_GET['route'])) {
    $route=$_GET['route'];
}

$action='';
if (isset($_GET['action'])) {
    $action=$_GET['action'];
}

// Forcer la "route" à "authenticate" si l'utilisateur n'a pas de session active donc n'est pas connecté
if (!isset($_SESSION['user'])){
    $route='authenticate';
}

// Sélectionner le controleur en fonction de la "route" et lui passer son "action"
switch ($route){
    case 'dashboard':
        DashboardController::switchAction($action);
    break;
    case 'feature':
        FeatureController::switchAction($action);
    break;
    case 'authenticate':
        AuthenticateController::switchAction($action);
    break;
    default :
        echo '<p>La route spécifiée ('.$route.') n\'existe pas !</p>';
    break;
}



