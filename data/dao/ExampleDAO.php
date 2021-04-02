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
        /*
         * Créer autant d'objets instance de la class Example qu'il y a de lignes (tuples)
         * au retour de la requête SQL dans la variable $results
         */
        $examples = []; // Tableau vide
        foreach ($results as $result) {
            $example = new Example($result['id'],$result['name']); // On crée un nouvel objet
            $examples[]=$example; // On ajoute l'objet au tableau
        }
    }

    public static function find($userId){
        $request='SELECT * 
            FROM example
            WHERE id=?';
        $result=Connection::safeQuery($request,[$userId])[0];

        // On teste s'il y a un résultat, si oui, on renvoie un objet sinon on renvoie null
        if (isset($result)){
            return new Example($result['id'],$result['name']);
        }
        else {
            return null;
        }
    }

    public static function save($name)
    {
        $request = 'INSERT INTO example(name)
                VALUES(?)';
        return Connection::safeQuery($request,[$name]);
    }
}
