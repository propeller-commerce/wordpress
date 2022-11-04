<?php

namespace Propeller\Includes\Model;

class CompanyModel extends BaseModel {
    public function __construct() {
        
    }

    public function create($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                companyCreate($str_args) {
                    id
                    companyId
                    addresses {
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
            }
        QUERY;

        return $gql;
    }

    public function get($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                company($str_args) {
                    id
                    companyId
                    addresses {
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
            }
        QUERY;

        return $gql;
    }

}
