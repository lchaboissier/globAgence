<?php

/*
 * Deux fonctions sont crées pour lancer une requéte SQL. Un exmple d'appel de ces fonctions dans du code :
 *  - $tableauDeResultats = Connexion::query("SELECT * FROM nom_table");
 *    Le résultat de la requète est enregistré dans la variable $tableauDeResultats 
 *  - $succes = Connexion::exec("INSERT..."); marche aussi pour UPDATE et DELETE
 *    Le résultat de la requete est placé dans la variable $succes : si 0 alors la requète n'a pas
 *    fonctionnée, sinon $succes contiendra le nombre d'enregistrement affectés
 */


class Connection {
    /** @var PDO */
    private static ?PDO $_pdo=null;
    private static $lastRequest=null;


    private function __construct() {
        
    }

    private  static function _get() {
        $dsn = 'mysql:dbname='.ENV['DB_DATABASE'].';host='.ENV['DB_HOST'];
        $user = ENV['DB_USERNAME'];
        $password = ENV['DB_PASSWORD'];

        try {
            self::$_pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        self::$_pdo->exec('SET NAMES \'utf8\'');
    }


    public static function safeQuery($query,$array){
        if (is_null(self::$_pdo)) {
            self::_get();
        }
        $sth=self::$_pdo->prepare($query);
        $sth->execute($array);

        self::$lastRequest=$sth;
        if($sth->errorCode()!='00000'){
            echo($sth->errorInfo()[2]);
            throw new Exception('Erreur de requête : '.$query);
        }
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result; 
    }


    public static function query($query) {
        if (is_null(self::$_pdo)) {
            self::_get();
        }
        
        $result = self::$_pdo->query($query);
        if(!$result){
            throw new Exception('Erreur de requête : '.$query);
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function exec($query) {
        if (is_null(self::$_pdo)) {
            self::_get();
        }
        return self::$_pdo->exec($query);
    }

    public static function insert($table,$values)
    {
        $keys = array_keys($values);
        $points = array_fill(0,count($keys),'?');
        $query='INSERT INTO `'.$table.'`('.implode(',',$keys).') VALUES('.implode(',',$points).');';
        return Connection::safeQuery($query,array_values($values));
    }
}
