<?php

namespace Propeller\Includes\Model;

class EmailModel extends BaseModel {
    public function __construct() {
        
    }

    public function send_propeller_email($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                publishEmailEvent($str_args) {
                    success
                    messageId
                }
            }
        QUERY;

        return $gql;
    }
}
