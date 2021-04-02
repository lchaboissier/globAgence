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
            } elseif ($kind == "reference") {
                // this is a reference to a foreign table
                $reference = $attributeData["reference"];
                $attributeNameId = $attributeName."_id";
                $id = $dict[$attributeNameId];
                $result->$attributeName = $reference::find($id);
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
        };
        return $result;
    }

    public static function find($id) {
        return get_called_class()::query(
            "SELECT * FROM ".get_called_class()::getTable()." WHERE id=?",
            [$id]
        )[0];
    }

    public static function where($attribute, $second, $third = null) {
        // handle the input renaming
        if ($third == null) {
            $comparator = "=";
            $value = $second;
        } else {
            $comparator = $second;
            $value = $third;
        };
        // perform the query
        return get_called_class()::query(
            "SELECT * FROM ".get_called_class()::getTable()." WHERE "
                .$attribute." ".$comparator." ?",
            [ $value ],
        );
    }
}