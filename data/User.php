<?php

class User extends DataObject {
    const _attributes = [
        "id" => [ "kind" => "int" ],
        "firstName" => [ "kind" => "string" ],
        "lastName" => [ "kind" => "string" ],
        "email" => [ "kind" => "string" ],
        "password" => [ "kind" => "string" ],
        "isAdmin" => [ "kind" => "int" ],
        "role_id" => [ "kind" => "int" ],
    ];
}