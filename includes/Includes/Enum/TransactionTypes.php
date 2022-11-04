<?php
namespace Propeller\Includes\Enum;

class TransactionTypes {
    const AUTHORIZATION = 'AUTHORIZATION';
    const CANCEL_AUTHORIZATION = 'CANCEL_AUTHORIZATION';
    const CHARGEBACK = 'CHARGEBACK';
    const PAY = 'PAY';
    const REFUND = 'REFUND';
}