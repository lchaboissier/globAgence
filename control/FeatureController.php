<?php 

function featureControl($userAction){

    if(!$_SESSION['user']['isAdmin']){
        header('Location:?route=dashboard');
    }

    switch ($userAction){
        // case à ajouter pour chaque nouvelle action souhaitée
        case 'addExample':
            featureControl_addExampleAction();
            break;
        default:
            featureControl_defaultAction();
        break;
    }
}


function featureControl_defaultAction()
{
    $tabTitle="Fonctionnalité";
    $examples=exampleData_all();
    include('../page/feature/index.php');
}

function featureControl_addExampleAction()
{
    exampleData_save($_POST['name']);
    header('Location:.?route=feature');
}




