<?php

namespace Propeller\Includes\Mutation;

use GraphQL\Query;
use Propeller\Includes\Query\GCIPUser;

class Login {
    public static $login;
    
    static function setDefaultQueryData() {
        self::login();
    }

    static function login() {
        self::$login = [
            (new Query('session'))
                ->setSelectionSet(
                    GCIPUser::$query
            )
        ];
    }
}