<?php

function roleData_all(){
    $request = 'SELECT *
                FROM role
                order by name
    ';
    return Connection::safeQuery($request,[]);

}