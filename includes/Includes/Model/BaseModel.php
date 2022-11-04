<?php

namespace Propeller\Includes\Model;

class BaseModel {
    public function __construct() {
        
    }

    public function parse_arguments($arguments) {
        $args = [];

        if (is_array($arguments)) {
            foreach ($arguments as $key => $vals) {
                if (gettype($vals) == 'object')
                    $args[] = "$key: " . $vals->__toString();
                else if (gettype($vals) == 'string')
                    $args[] = "$key: \"$vals\"";
                else 
                    $args[] = "$key: $vals";
            }
            
            return implode(', ', $args);
        }
        else {
            return $arguments->__toString();
        }
    }

    public function extract_query($query) {
        preg_match('/^query {(.*)}/ms', $query, $matches);

        if (count($matches) > 1)
            return $matches[1];
        
        return '';
    }

    public function product_track_attributes() {
        $gql = "";

        if (defined('PROPELLER_PRODUCT_TRACK_ATTR') && !empty(PROPELLER_PRODUCT_TRACK_ATTR)) {
            $attr_track = explode(',', PROPELLER_PRODUCT_TRACK_ATTR);
            $raw_params_array = 'filter: { name: ["' . implode('", "', $attr_track) . '"] }';

            $gql = "
                trackAttributes: attributes($raw_params_array) {
                    id
                    name
                    group
                    searchId
                    description {
                        value
                        language
                    }
                    type
                    typeParam
                    isSearchable
                    isPublic
                    isHidden
                    enumValue
                    intValue
                    decimalValue
                    dateValue
                    textValue {
                        values
                        language
                    }
                }
            ";
        }

        return $gql;
    }
}
