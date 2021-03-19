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

function exampleData_all(){
    $request='SELECT * 
            FROM example';
    return Connection::safeQuery($request,[]);
}

function exampleData_find($userId){
    $request='SELECT * 
            FROM example
            WHERE id=?';
    return Connection::safeQuery($request,[$userId])[0];
}

function exampleData_save($name)
{
    $request = 'INSERT INTO example(name)
                VALUES(?)';
    return Connection::safeQuery($request,[$name]);
}



