<?php
namespace Propeller\Includes\Controller;

interface PaymentInterface {
    public function create($args);

    public function get($args);
}