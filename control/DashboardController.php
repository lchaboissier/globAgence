<?php

function dashboardControl($userAction){
    switch ($userAction){
        // case à ajouter pour chaque nouvelle action souhaitée
        default:
            dashboardControl_defaultAction();
        break;
    }
}


function dashboardControl_defaultAction()
{
    $tabTitle="Tableau de bord";
    //$warehouses = userData_warehouses($_SESSION['user']['id']);
    include('../page/dashboard/index.php');
}


