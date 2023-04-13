<?php

namespace Propeller\Includes\Model;

class MachineModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_installations($arguments, $images_args, $language) {
        $str_args = count($arguments) ? '(' . $this->parse_arguments($arguments) . ')' : '';

        $media_images_gql = $this->extract_query($images_args);

        $gql = <<<QUERY
            query {
                machines $str_args {
                    items {
                        id
                        name (language: "$language") {
                            language
                            value
                        }
                        description (language: "$language") {
                            language
                            value
                        }
                        slug (language: "$language") {
                            language
                            value
                        }
                        slugs: slug {
                            language
                            value
                        }
                        $media_images_gql
                        parts {
                            id
                        }
                    }
                    itemsFound
                    offset
                    page
                    pages
                    start
                    end
                }
            }        
        QUERY;

        return $gql;
    }

    public function get_machines($arguments, $parts_arguments, $images_args, $language) {
        $str_args = count($arguments) ? '(' . $this->parse_arguments($arguments) . ')' : '';

        $media_images_gql = $this->extract_query($images_args);

        $parts_gql = $this->parts($parts_arguments, $images_args, $language);

        $gql = <<<QUERY
            query {
                machine $str_args {
                    id
                    name (language: "$language") {
                        language
                        value
                    }
                    description (language: "$language") {
                        language
                        value
                    }
                    slug (language: "$language") {
                        language
                        value
                    }
                    slugs: slug {
                        language
                        value
                    }
                    $media_images_gql
                    machines {
                        id
                        name (language: "$language") {
                            language
                            value
                        }
                        description (language: "$language") {
                            language
                            value
                        }
                        slug (language: "$language") {
                            language
                            value
                        }
                        slugs: slug {
                            language
                            value
                        }
                        parts {
                            id
                        }
                        $media_images_gql
                    }
                    $parts_gql
                }
            }        
        QUERY;

        return $gql;
    }

    public function parts($arguments, $images_args, $language) {
        $media_images_gql = $this->extract_query($images_args);

        $track_attributes = $this->product_track_attributes();

        $str_args = "";

        if (is_array($arguments) && sizeof($arguments))
            $str_args = "(" . $this->parse_arguments($arguments) . ", language: \"$language\")";
        else 
            $str_args = "(language: \"$language\")";
            
        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $gql = "
            parts: sparePartProducts $str_args {
                itemsFound
                offset
                page
                pages
                start
                end
                minPrice
                maxPrice
                items {
                    id
                    sku
                    quantity
                    name (language: \"$language\") {
                        language
                        value
                    }
                    product {
                        class
                        name(language: \"$language\") {
                            value
                            language
                        }
                        sku
                        slug(language: \"$language\") {
                            value
                            language
                        }
                        ... on Product {
                            id
                            productId
                            urlId: productId
                            manufacturerCode
                            eanCode
                            manufacturer
                            supplierCode
                            taxCode
                            status
                            isOrderable
                            packageUnit
                            packageUnitQuantity
                            originalPrice
                            costPrice
                            suggestedPrice
                            storePrice
                            minimumQuantity
                            unit
                            purchaseUnit
                            purchaseMinimumQuantity
                            inventory {
                                totalQuantity
                            }
                            price {
                                net
                                gross
                                quantity
                                discount {
                                    formula
                                    type
                                    quantity
                                    value
                                    validFrom
                                    validTo
                                }
                                taxCode
                                type
                            }
                            $track_attributes
                            $media_images_gql
                        }
                        ... on Cluster {
                            id
                            class
                            clusterId
                            urlId: clusterId
                            defaultProduct {
                                productId
                            }
                            products {
                                class
                                name(language: \"$language\") {
                                    value
                                    language
                                }
                                sku
                                slug(language: \"$language\") {
                                    value
                                    language
                                }
                                ... on Product {
                                    id
                                    productId
                                    manufacturerCode
                                    eanCode
                                    manufacturer
                                    supplierCode
                                    taxCode
                                    status
                                    isOrderable
                                    packageUnit
                                    packageUnitQuantity
                                    originalPrice
                                    costPrice
                                    suggestedPrice
                                    storePrice
                                    minimumQuantity
                                    unit
                                    purchaseUnit
                                    purchaseMinimumQuantity
                                    price {
                                        net
                                        gross
                                        quantity
                                        discount {
                                            formula
                                            type
                                            quantity
                                            value
                                            validFrom
                                            validTo
                                        }
                                        taxCode
                                        type
                                    }
                                    $media_images_gql
                                }
                            }
                        }
                    }
                }
                filters {
                    id
                    searchId
                    description
                    type
                    isSearchable
                    isPublic
                    isHidden
                    textFilter {
                        value
                        count
                        countTotal
                        countActive
                        isSelected
                    }
                    integerRangeFilter {
                        min
                        max
                    }
                    decimalRangeFilter {
                        min
                        max
                    }
                }
            }
        ";

        return $gql;
    }
}