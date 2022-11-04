<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;

class Warehouse {
    public static $query;

    static function setDefaultQueryData($args = []) {
        WarehouseAddress::setDefaultQueryData();
        
        self::$query = [
            "id",
            (new Query('address'))
                ->setSelectionSet(
                    WarehouseAddress::$query
                ),
            "name",
            "description",
            "notes",
            "isActive",
            "isStore",
            "isPickupLocation",
            (new Query('businessHours'))
                ->setSelectionSet([
                    "dayOfWeek",
                    "openingTime",
                    "closingTime",
                    "lunchBeakStartTime",
                    "lunchBeakEndTime"
                ])
        ];
    }
}