<?php

// entries are under the form :
// [
//   [
//     "kind": //kind,
//     "id": //id/name
//     "label": //label/display text
//   ]
// ]
//
// kinds : text, number, select, hidden
// spectial option:
// select :
//   options : [
//     [
//       id :
//       label :
//     ]
//   ]

class Form {
    //TODO: document the default
    public static function generate_html_form($defaults) {
        $caller = get_called_class();
        $result = "<form>\n";
        foreach ($caller::_entries as $entry) {
            if (array_key_exists($entry["id"], $defaults)) {
                $default = $default[$entry["id"]];
            } else {
                switch ($entry["id"]) {
                    case "number":
                        $default = "0";
                        break;
                    default:
                        $default = "";
                };
            };
            // place the label
            if ($entry["kind"] != "hidden") {
                $result .= "<label for=\"".$entry["id"]."\">".$entry["label"]." : </label>";
            };
            // place the form itself
            if ($entry["kind"] == "hidden") {
                $result .= "<input type=\"hidden\" id=\"".$entry["id"]."\" name=\"".$entry["id"]."\">".$default."</input>\n";
            } else if ($entry["kind"] == "text") {
                $result .= "<input type=\"text\" id=\"".$entry["id"]."\" name=\"".$entry["id"]."\">".$default."</input>\n";
                $result .= "<br />\n";
            } else if ($entry["kind"] == "number") {
                $result .= "<input type=\"number\" id=\"".$entry["id"]."\" name=\"".$entry["id"]."\">".$default."</input>\n";
                $result .= "<br />\n";
            } else if ($entry["kind"] == "option") {
                $result .= "<select name=\"".$entry["id"]."\" id=\"".$entry["id"]."\">\n";
                foreach ($entry["options"] as $option) {
                    if ($entry["id"] == $default) {
                        $option_selected = "selected";
                    } else {
                        $option_selected = "";
                    };
                    $result .= "<option value=\"".$option["id"]."\" ".$option_selected.">".$option["label"]."</option>\n";
                };
                $result .= "</select><br />\n";
            };
        }

        $result .= "<input type=\"submit\" value=\"Envoyer\" /></form>";

        return $result;
    }

}