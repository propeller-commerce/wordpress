<?php

namespace Propeller\Includes\Controller;

use GraphQL\Query;
use GraphQL\RawObject;
use Propeller\Includes\Query\Warehouse;
use Propeller\Includes\Query\Warehouses;

class WarehouseController extends BaseController {
    protected $type = 'warehouse';

    protected $booleans = ['isActive', 'isStore', 'isPickupLocation'];
    
    public function __construct() {
        parent::__construct();
    }

    public function get_warehouses($args = []) {
        $type = 'warehouses';

        Warehouses::setDefaultQueryData();
        Warehouse::setDefaultQueryData();

        $warehousesGql = Warehouses::$query;

        $warehousesGql[] = (new Query('items'))
            ->setSelectionSet(
                Warehouse::$query
            );

        $params = [];
        if (count($args)) {
            foreach ($args as $key => $val) {
                $temp_val = $val;

                if (in_array($key, $this->booleans))
                    $temp_val = $val == 1 ? 'true' : 'false';

                $params[] = "$key: $temp_val";
            }
                
        }

        $gql = (new Query($type))
            ->setArguments(['input' => new RawObject('{' . implode(', ', $params) . '}')])
            ->setSelectionSet(
                $warehousesGql
            );

        return $this->query($gql, $type);
    }
}