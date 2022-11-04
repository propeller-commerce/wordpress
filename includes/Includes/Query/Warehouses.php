<?php

namespace Propeller\Includes\Query;

class Warehouses {
    public static $query;

    static function setDefaultQueryData() {
        self::$query = [
            "itemsFound",
            "offset",
            "page",
            "pages",
            "start",
            "end"
        ];
    }
}