<?php

class UserDAO{

    /*
    Récupérer des enregistrements
    - get()
    - all()
    - first()
    - find()
    Insérer ou mettre à jour des enregistrements
    - save()
    Supprimer des enregistrements
    - delete()
    */

    public static function all(){
        $request='SELECT * 
            FROM user';
        return Connection::safeQuery($request,[]);
    }

    public static function details($userId){
        $request='SELECT * 
            FROM user
            WHERE id=?';
        return Connection::safeQuery($request,[$userId])[0];
    }

    public static function findOneWithCredentials($userEmail, $userPwd){
        $request="SELECT id,firstName,lastName,email,password,isAdmin,role_id FROM user WHERE email=? AND password=?";
        $requestParams=array($userEmail,sha1($userPwd));
        $result=Connection::safeQuery($request,$requestParams);
        if(isset($result[0])) {
            $user = new User($result[0]['id'],
                            $result[0]['firstName'],
                            $result[0]['lastName'],
                            $result[0]['email'],
                            $result[0]['password'],
                            $result[0]['isAdmin'],
                            $result[0]['role_id'],
                            );
            //var_dump($user);
            //exit();
            return $user;
        }else{
            return false;
        }
    }

    public static function getRole($warehouseId,$userId=null){
        $request='SELECT role.* 
            FROM role,works
            WHERE role.id=works.role_id
            AND works.warehouse_id=? 
            AND works.user_id = ?';
        $roles = Connection::safeQuery($request,[$warehouseId,null==$userId ? $_SESSION['user']['id']:$userId]);
        if(isset($roles[0])){
            return $roles[0];
        }else{
            return false;
        }
    }
}