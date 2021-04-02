<?php

class DataObject {
    public static function getTable() {
        $class = get_called_class();
        if(isset($class::$_tableName)) {
            return $class::$_tableName;
        } else {
            return strtolower($class);
        }
    }

    public static function dict_to_self($dict) {
        $caller = get_called_class();
        $result = new $caller;
        foreach ($caller::_attributes as $attributeName => $attributeData) {
            $kind = $attributeData["kind"];
            if ($kind == "int") {
                $result->$attributeName = (int) $dict[$attributeName];
            } elseif ($kind == "string") {
                $result->$attributeName = $dict[$attributeName];
            } else {
                throw new Exception("Unknown table type : ".$kind);                
            }
        }
        return $result;
    }

    public static function query($query, $params = []) {
        $all = Connection::safeQuery(
            $query,
            $params
        );
        $result = [];
        foreach ($all as $entry) {
            $result[] = get_called_class()::dict_to_self($entry);
        }
        return $result;
    }

    public static function find($id) {
        return get_called_class()::query(
            "SELECT * FROM ".get_called_class()::getTable()." WHERE id=?",
            [$id]
        )[0];
    }
}