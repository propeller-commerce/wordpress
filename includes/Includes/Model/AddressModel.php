<?php

namespace Propeller\Includes\Model;

class AddressModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_addresses($addr_type, $arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                $addr_type($str_args) {
                    id
                    code
                    firstName
                    middleName
                    lastName
                    gender
                    email
                    country
                    city
                    street
                    number
                    numberExtension
                    postalCode
                    company
                    phone
                    notes
                    icp
                    type
                    isDefault
                }
            }        
        QUERY;

        return $gql;
    }

    public function add_address($type, $arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                $type($str_args) {
                    id
                }
            }        
        QUERY;

        return $gql;
    }

    public function update_address($type, $arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                $type($str_args) {
                    id
                }
            }        
        QUERY;

        return $gql;
    }

    public function delete_address($type, $arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                $type($str_args)
            }        
        QUERY;

        return $gql;
    }

}
