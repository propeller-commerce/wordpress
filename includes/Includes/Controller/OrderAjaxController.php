<?php 

namespace Propeller\Includes\Controller;

use Propeller\Propeller;

class OrderAjaxController {
    protected $order;
    protected $object_name = 'Order';

    public function __construct() {
        $this->order = new OrderController();
    }

    public function change_order_status() {
        $response = $this->order->change_status($_REQUEST);

        $response->object = $this->object_name;

        die(json_encode($response));
    }

    public function get_orders() {
        $prop = new Propeller();
        $prop->reinit_filters();

        $response = $this->order->orders(true);

        die(json_encode($response));
    }

    public function get_quotes() {
        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = $this->order->quotations(true);

        die(json_encode($response));
    }

    public function return_request() {
        $response = $this->order->return_request($_REQUEST);

        die(json_encode($response));
    }
}