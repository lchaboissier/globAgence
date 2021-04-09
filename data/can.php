<?php

function can (string $permission): bool {
    if (!array_key_exists("user", $GLOBALS)) {
        return false;
    } else {
        return $GLOBALS["user"]->can($permission);
    }
}