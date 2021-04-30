<?php

class GoodController {
    public function __construct(){}

    public static function switchAction($userAction) {
        switch ($userAction) {
            case 'addProperty':
                self::addProperty();
                break;
            default:
                DashboardController::switchAction("");
                break;
        }
    }

    public static function addProperty() {
        $form_html = PropertyForm::generate_html_form([]);
        $tabTitle = "ajouter une nouvelle propriété";
        $pageTitle = "ajouter une nouvelle propriété";
        include("../page/good/changeProperty.php");
    }
}