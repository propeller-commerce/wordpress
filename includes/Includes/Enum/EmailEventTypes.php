<?php
namespace Propeller\Includes\Enum;

class EmailEventTypes {
    const ORDERCONFIRM = 'orderconfirm';
    const REGISTRATION = 'registration';
    const CAMPAIGN = 'campaign';
    const TRANSACTIONAL = 'transactional';
    const CUSTOM = 'custom';
    const SYSTEM = 'system';
    const ERROR = 'error';
}