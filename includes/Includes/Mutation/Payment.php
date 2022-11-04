<?php

namespace Propeller\Includes\Mutation;

use GraphQL\Query;

class Payment {
    public static $payment;

    static function setDefaultQueryData() {
        self::payment();
    }

    static function payment() {
        self::$payment = [
            "amount",
            "anonymousId",
            "createdAt",
            "createdBy",
            "currency",
            "id",
            "lastModifiedAt",
            "lastModifiedBy",
            "method",
            "orderId",
            "paymentId",
            "status",
            (new Query('transactions'))
                ->setSelectionSet([
                    "amount",
                    "currency",
                    "description",
                    "id",
                    "orderId",
                    "paymentId",
                    "provider",
                    "status",
                    "timestamp",
                    "transactionId",
                    "type"
            ]),
            "userId"
        ];
    }
}