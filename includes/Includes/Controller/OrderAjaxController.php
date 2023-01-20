<?php 

namespace Propeller\Includes\Controller;

use Propeller\Propeller;
use stdClass;

class OrderAjaxController extends BaseAjaxController {
    protected $order;
    protected $object_name = 'Order';

    public function __construct() {
        $this->order = new OrderController();
    }

    public function change_order_status() {
        $_REQUEST = $this->sanitize($_REQUEST);

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
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_REQUEST = $this->sanitize($_REQUEST);
        
            $response = $this->order->return_request($_REQUEST);
        }
        else {
            $postprocess->status = false;
            $postprocess->reload = false;
            $postprocess->error = true;
            $postprocess->message = __("Security check failed", "propeller-ecommerce");

            $response->postprocess = $postprocess;
        }
        
        $response->object = $this->object_name;

        die(json_encode($response));
    }
}