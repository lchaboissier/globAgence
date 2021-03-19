<?php

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

function userData_all(){
    $request='SELECT * 
            FROM user';
    return Connection::safeQuery($request,[]);
}

function userData_details($userId){
    $request='SELECT * 
            FROM user
            WHERE id=?';
    return Connection::safeQuery($request,[$userId])[0];
}

function userData_findOneWithCredentials($userEmail, $userPwd){
     $request="SELECT id,firstName,lastName,email,isAdmin,role_id FROM user WHERE email=? AND password=?";
     $requestParams=array($userEmail,sha1($userPwd));
     $result=Connection::safeQuery($request,$requestParams);
     if(isset($result[0])) {
         return $result[0];
     }else{
         return false;
     }
}

function userData_getRole($warehouseId,$userId=null){
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




