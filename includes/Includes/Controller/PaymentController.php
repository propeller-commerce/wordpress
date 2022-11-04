<?php

namespace Propeller\Includes\Controller;

use stdClass;

class PaymentController extends BaseController {
    public $providers = [];

    public function __construct() {
        parent::__construct();

        $this->providers = $this->get_active_payment_provider();
    }

    private function get_active_payment_provider() {
        $payment_providers = [];

        $plugins = array_filter(glob(ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'propeller-*-provider'), 'is_dir');

        if (is_array($plugins) && count($plugins)) {
            foreach ($plugins as $plg) {
                $name = basename($plg);

                if (in_array("$name/$name.php", (array) get_option('active_plugins', array()))) {
                    require_once $plg . DIRECTORY_SEPARATOR . $name . '.php';
                    
                    $provider = new stdClass();  
                    $provider->provider = get_provider();
                    $provider->name = $name;

                    $payment_providers[] = $provider;
                }
            }
        }
        
        return $payment_providers;
    }

    public function has_providers() {
        return sizeof($this->providers) > 0;
    }

    public function create($data) {    
        if ($this->has_providers()) {
            $payment = $this->providers[0]->provider->create($data);

            return $payment;
        }    
        
        return null;
    }
}