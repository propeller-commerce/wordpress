<?php

namespace Propeller\Includes\Controller;

use GraphQL\RawObject;

class WarehouseController extends BaseController {
    protected $type = 'warehouse';
    protected $model;

    protected $booleans = ['isActive', 'isStore', 'isPickupLocation'];
    
    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('warehouse');
    }

    public function get_warehouses($args = []) {
        $type = 'warehouses';

        $params = [];
        if (count($args)) {
            foreach ($args as $key => $val) {
                $temp_val = $val;

                if (in_array($key, $this->booleans))
                    $temp_val = $val == 1 ? 'true' : 'false';

                $params[] = "$key: $temp_val";
            }
                
        }

        $gql = $this->model->get_warehouses(['input' => new RawObject('{' . implode(', ', $params) . '}')]);

        return $this->query($gql, $type);
    }
}