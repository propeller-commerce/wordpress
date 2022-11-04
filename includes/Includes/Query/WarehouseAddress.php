<?php

namespace Propeller\Includes\Query;

class WarehouseAddress {
    public static $query;

    static function setDefaultQueryData() {
        self::$query = [
            "id",
            "code",
            "name",
            "url",
            "firstName",
            "middleName",
            "lastName",
            "email",
            "gender",
            "country",
            "city",
            "street",
            "number",
            "numberExtension",
            "region",
            "postalCode",
            "company",
            "phone",
            "notes"
        ];
    }
}