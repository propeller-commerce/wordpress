<?php

namespace Propeller\Includes\Model;

class WarehouseModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_warehouses($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                warehouses($str_args) {
                    itemsFound
                    offset
                    page
                    pages
                    start
                    end
                    items {
                        id
                        address {
                            id
                            code
                            name
                            url
                            firstName
                            middleName
                            lastName
                            email
                            gender
                            country
                            city
                            street
                            number
                            numberExtension
                            region
                            postalCode
                            company
                            phone
                            notes
                        }
                        name
                        description
                        notes
                        isActive
                        isStore
                        isPickupLocation
                        businessHours {
                            dayOfWeek
                            openingTime
                            closingTime
                            lunchBeakStartTime
                            lunchBeakEndTime
                        }
                    }
                }
            }
        QUERY;

        return $gql;
    }
}
