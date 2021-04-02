<?php

class RoleDAO{

    public static function all(){
        $request = 'SELECT *
                FROM role
                order by name
    ';
        return Connection::safeQuery($request,[]);

    }
}
