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
        echo "TODO";
        
    }
}