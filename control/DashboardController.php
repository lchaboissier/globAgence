<?php

class DashboardController{

    public function __construct(){}

    public static function switchAction($userAction){
        switch ($userAction){
            // case à ajouter pour chaque nouvelle action souhaitée
            default:
                self::defaultAction();
                break;
        }
    }


    private static function defaultAction()
    {
        $tabTitle="Tableau de bord";
        //$warehouses = userData_warehouses($_SESSION['user']['id']);
        include('../page/dashboard/index.php');
    }



}