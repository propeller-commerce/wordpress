<?php

namespace Propeller\Includes\Mutation;

use GraphQL\Query;
use Propeller\Includes\Query\GCIPUser;

class Session {
    public static $session_start;
    
    static function setDefaultQueryData() {
        self::start();
    }

    static function start() {
        GCIPUser::setDefaultQueryData();
        
        self::$session_start = [
            (new Query('session'))
                ->setSelectionSet(
                    GCIPUser::$query
            )
        ];
    }
}