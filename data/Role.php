<?php

class Role extends DataObject {
    const _attributes = [
        "id" => ["kind" => "int"],
        "name" => [ "kind" => "string" ]
    ];
}