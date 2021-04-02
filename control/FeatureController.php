<?php 

class FeatureController{

    public function __construct(){}

    public static function switchAction($userAction){

        if(!$_SESSION['user']['isAdmin']){
            header('Location:?route=dashboard');
        }

        switch ($userAction){
            // case à ajouter pour chaque nouvelle action souhaitée
            case 'addExample':
                self::addExampleAction();
                break;
            default:
                self::defaultAction();
                break;
        }
    }

    private static function defaultAction()
    {
        $tabTitle="Fonctionnalité";
        $examples=exampleData_all();
        include('../page/feature/index.php');
    }

    private static function addExampleAction()
    {
        exampleData_save($_POST['name']);
        header('Location:.?route=feature');
    }





}