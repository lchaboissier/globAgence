<?php

class User extends DataObject {
    const _attributes = [
        "id" => [ "kind" => "int" ],
        "firstName" => [ "kind" => "string" ],
        "lastName" => [ "kind" => "string" ],
        "email" => [ "kind" => "string" ],
        "password" => [ "kind" => "string" ],
        "isAdmin" => [ "kind" => "int" ],
        "role" => [
            "kind" => "reference",
            "reference" => "Role"
        ],
    ];

    static function findOneWithCredentials($mail, $pass) {
        $got = self::query(
            "SELECT * FROM ".self::getTable()." WHERE password = ? AND email = ?",
            [sha1($pass), $mail]
        );
        if (count($got) == 0) {
            return [];
        } else {
            return $got[0];
        }
    }

    public function can(string $permission) {
        $got = Connection::safeQuery(
            "SELECT * FROM can WHERE role_id=? AND permission_id=(
                SELECT id FROM permission WHERE action=?
            )",
            [$this->role->id, $permission]
        );
        return count($got) >= 1;
    }
}