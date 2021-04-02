<?php

class RoleDAO{

    public static function all(){
        $request = 'SELECT *
                FROM role
                order by name
    ';
        $results = Connection::safeQuery($request,[]);
        /*
         * Créer autant d'objets instance de la class Example qu'il y a de lignes (tuples)
         * au retour de la requête SQL dans la variable $results
         */
        $roles = []; // Tableau vide
        foreach ($results as $result) {
            $role = new Role($result['id'],$result['name']);
            $roles=$role; // On ajoute l'objet au tableau
        }
        return $roles;
    }
}
