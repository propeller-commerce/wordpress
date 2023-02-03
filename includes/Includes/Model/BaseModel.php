<?php

namespace Propeller\Includes\Model;

use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Offset;

class BaseModel {
    public function __construct() {
        
    }

    public function parse_arguments($arguments) {
        $args = [];

        if (is_array($arguments)) {
            foreach ($arguments as $key => $vals) {
                if (gettype($vals) == 'object') {
                    $val = str_replace("\'", "'", $vals->__toString());
                    
                    $args[] = "$key: " . $val;
                }                    
                else if (gettype($vals) == 'string') {
                    $val = str_replace("\'", "'", $vals);

                    $args[] = "$key: \"$vals\"";
                }                    
                else {
                    $args[] = "$key: $vals";
                }
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

    public function attributes($attr_str_args) {

        $gql = "
            attributes: attributeValues($attr_str_args) {
                items {
                    attributeId
                    textValue {
                        language
                        values
                    }
                    enumValue
                    intValue
                    decimalValue
                    dateValue
                    attributeDescription {
                        id
                        name
                        searchId
                        description {
                            language
                            value
                        }
                        type
                        typeParam
                        group
                        isSearchable
                        isPublic
                        isHidden
                    }
                }
                itemsFound
                offset
                page
                pages
                start
                end
            }
        ";

        return $gql;
    }

    public function product_track_attributes() {
        $gql = "";

        if (defined('PROPELLER_PRODUCT_TRACK_ATTR') && !empty(PROPELLER_PRODUCT_TRACK_ATTR)) {
            $offset = 12;            
            $attr_track = explode(',', PROPELLER_PRODUCT_TRACK_ATTR);

            $offset = count($attr_track);
            $raw_params_array = 'filter: { name: ["' . implode('", "', $attr_track) . '"], offset: ' . $offset . ' }';

            $gql = "
                trackAttributes: attributeValues($raw_params_array) {
                    items {
                        attributeId
                        textValue {
                            language
                            values
                        }
                        enumValue
                        intValue
                        decimalValue
                        dateValue
                        attributeDescription {
                            id
                            name
                            searchId
                            description {
                                language
                                value
                            }
                            type
                            typeParam
                            group
                            isSearchable
                            isPublic
                            isHidden
                        }
                    }
                    itemsFound
                    offset
                    page
                    pages
                    start
                    end
                }
            ";
        }

        return $gql;
    }
}
