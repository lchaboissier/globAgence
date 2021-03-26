<?php 

class AuthenticateController{

    public function __construct(){}

    public function authenticateControl($userAction){
        switch ($userAction){
            case "login":
                $email=$_POST['email'];
                $password=$_POST['password'];
                $this->loginAction($email,$password);
                break;
            case "logout":
                $this->logoutAction();
                break;
            case "unauthorized":
                $this->unauthorizedAction();
                break;
            default:
                $this->defaultAction();
                break;
        }
    }


    private function defaultAction()
    {
        $tabTitle="Connexion";
        $message='';
        include('../page/authenticate/index.php');
    }

    private function loginAction($email,$password)
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

    private function logoutAction()
    {
        // Code pour la déconnexion
        unset($_SESSION);
        session_destroy();
        header('location:?route=authenticate');
    }

    private function unauthorizedAction(){
        $tabTitle='Erreur';
        include('../page/authenticate/unauthorized.php');
    }

}
