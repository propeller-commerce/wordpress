<?php

namespace Propeller\Includes\Model;

class AddressModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_addresses($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                addressesByCompanyId($str_args) {
                    id
                    code
                    firstName
                    middleName
                    lastName
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
