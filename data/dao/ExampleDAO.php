<?php

class ExampleDAO{
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
            FROM example';
        return Connection::safeQuery($request,[]);
    }

    public static function find($userId){
        $request='SELECT * 
            FROM example
            WHERE id=?';
        return Connection::safeQuery($request,[$userId])[0];
    }

    public static function save($name)
    {
        $request = 'INSERT INTO example(name)
                VALUES(?)';
        return Connection::safeQuery($request,[$name]);
    }
}
