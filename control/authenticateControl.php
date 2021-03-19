<?php 

function authenticateControl($userAction){
    switch ($userAction){
        case "login":
            $email=$_POST['email'];
            $password=$_POST['password'];
            authenticateControl_loginAction($email,$password);
            break;
        case "logout":
            authenticateControl_logoutAction();
            break;

        default:
            authenticateControl_defaultAction();
            break;
    }
}


function authenticateControl_defaultAction()
{
    $tabTitle="Connexion";
    $message='';
    include('../page/authenticate/index.php');
}

function authenticateControl_loginAction($email,$password)
{
    // Appel du modèle pour chercher le mail et le mdp crypté dans la bdd
    $user=userData_findOneWithCredentials($email,$password);

    if (!$user){
         // Pas d'utilisateur avec ce mail et ce mot de passe. On prépare un message pour la vue
         $message="Vos identifiants sont incorrects.";
         // On appelle la vue par défaut
         $tabTitle="Connexion";
         include('../page/authenticate/index.php');
    }
    else{
        // L'utilisateur a le droit d'accès
        $_SESSION['user']=$user;
        header('location:./?route=dashboard');
    }
}

function authenticateControl_logoutAction()
{
   // Code pour la déconnexion
   unset($_SESSION);
   session_destroy();
   header('location:?route=authenticate');
}

function authenticateControl_unauthorized(){
    $tabTitle='Erreur';
    include('../page/authenticate/unauthorized.php');
}