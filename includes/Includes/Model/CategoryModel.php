<?php

namespace Propeller\Includes\Model;

class CategoryModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_catalog($slug_args, $arguments, $attributes_args, $images_query, $language) {
        $slug_args = $this->parse_arguments($slug_args);

        $str_args = "";

        if (is_array($arguments) && sizeof($arguments))
            $str_args = '(' . $this->parse_arguments($arguments) . ')';

        $attr_str_args = $this->parse_arguments($attributes_args);

        $media_images_gql = $this->extract_query($images_query);

        $track_attributes = $this->product_track_attributes();
        
        $gql = <<<QUERY
            query {
                category(
                    $slug_args
                ) {
                    id
                    categoryId
                    name(language: "$language") {
                        value
                        language
                    }
                    description(language: "$language") {
                        value
                        language
                    }
                    shortDescription(language: "$language") {
                        value
                        language
                    }
                    slug(language: "$language") {
                        value
                        language
                    }
                    categoryPath {
                        name(language: "$language") {
                            language
                          value
                      }
                      slug(language: "$language") {
                            language
                          value
                      }
                    }
                    parent {
                        id
                        categoryId
                        name(language: "$language") {
                            value
                            language
                        }
                        description(language: "$language") {
                            value
                            language
                        }
                        shortDescription(language: "$language") {
                            value
                            language
                        }
                        slug(language: "$language") {
                            value
                            language
                        }
                    }
                    slugs: slug {
                        value
                        language
                    }
                    products $str_args {
                        itemsFound
                        offset
                        page
                        pages
                        start
                        minPrice
                        maxPrice
                        end
                        items {
                            class
                            name(language: "$language") {
                                value
                                language
                            }
                            description(language: "$language") {
                                value
                                language
                            }
                            shortDescription(language: "$language") {
                                value
                                language
                            }
                            sku
                            slug(language: "$language") {
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
                                inventory {
                                    balance {
                                        id
                                        productId
                                        location
                                        warehouseId
                                        sku
                                        supplier
                                        supplierCode
                                        costPrice
                                        dateModified
                                        nextDeliveryDate
                                        notes
                                        quantity
                                    }
                                    localQuantity
                                    nextDeliveryDate
                                    productId
                                    supplierQuantity
                                    totalQuantity
                                }
                                category {
                                    id
                                    categoryId
                                    name(language: "$language") {
                                        value
                                        language
                                    }
                                    description(language: "$language") {
                                        value
                                        language
                                    }
                                    shortDescription(language: "$language") {
                                        value
                                        language
                                    }
                                    slug(language: "$language") {
                                        value
                                        language
                                    }
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
                                bulkPrices {
                                    net
                                    gross
                                    from
                                    to
                                }
                                attributes(
                                    $attr_str_args
                                ) {
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
                                $track_attributes
                                $media_images_gql
                            }
                            ... on Cluster {
                                id
                                class
                                clusterId
                                defaultProduct {
                                    productId
                                }
                                products {
                                    class
                                    name(language: "$language") {
                                        value
                                        language
                                    }
                                    description(language: "$language") {
                                        value
                                        language
                                    }
                                    shortDescription(language: "$language") {
                                        value
                                        language
                                    }
                                    sku
                                    slug(language: "$language") {
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
                                        inventory {
                                            balance {
                                                id
                                                productId
                                                location
                                                warehouseId
                                                sku
                                                supplier
                                                supplierCode
                                                costPrice
                                                dateModified
                                                nextDeliveryDate
                                                notes
                                                quantity
                                            }
                                            localQuantity
                                            nextDeliveryDate
                                            productId
                                            supplierQuantity
                                            totalQuantity
                                        }
                                        category {
                                            id
                                            categoryId
                                            name(language: "$language") {
                                                value
                                                language
                                            }
                                            description(language: "$language") {
                                                value
                                                language
                                            }
                                            shortDescription(language: "$language") {
                                                value
                                                language
                                            }
                                            slug(language: "$language") {
                                                value
                                                language
                                            }
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
                                        bulkPrices {
                                            net
                                            gross
                                            from
                                            to
                                        }
                                        $media_images_gql
                                    }
                                }
                                options {
                                    id
                                    defaultProduct {
                                        productId
                                    }
                                    products {
                                        class
                                        name(language: "$language") {
                                            value
                                            language
                                        }
                                        description(language: "$language") {
                                            value
                                            language
                                        }
                                        shortDescription(language: "$language") {
                                            value
                                            language
                                        }
                                        sku
                                        slug(language: "$language") {
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
                                            inventory {
                                                balance {
                                                    id
                                                    productId
                                                    location
                                                    warehouseId
                                                    sku
                                                    supplier
                                                    supplierCode
                                                    costPrice
                                                    dateModified
                                                    nextDeliveryDate
                                                    notes
                                                    quantity
                                                }
                                                localQuantity
                                                nextDeliveryDate
                                                productId
                                                supplierQuantity
                                                totalQuantity
                                            }
                                            category {
                                                id
                                                categoryId
                                                name(language: "$language") {
                                                    value
                                                    language
                                                }
                                                description(language: "$language") {
                                                    value
                                                    language
                                                }
                                                shortDescription(language: "$language") {
                                                    value
                                                    language
                                                }
                                                slug(language: "$language") {
                                                    value
                                                    language
                                                }
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
                                            bulkPrices {
                                                net
                                                gross
                                                from
                                                to
                                            }
                                            $media_images_gql
                                        }
                                    }
                                    name(language: "$language") {
                                        value
                                        language
                                    }
                                    description(language: "$language") {
                                        value
                                        language
                                    }
                                    shortDescription(language: "$language") {
                                        value
                                        language
                                    }
                                }
                                drillDown {
                                    attributeId
                                    attribute {
                                        description(language: "$language") {
                                            value
                                            language
                                        }
                                    }
                                    priority
                                    displayType
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
                }
            }        
        QUERY;

        return $gql;
    }

    public function get_category($arguments, $language) {
            $str_args = $this->parse_arguments($arguments);
            
            $gql = <<<QUERY
                query {
                    category(
                        $str_args
                    ) {
                        id
                        categoryId
                        name(language: "$language") {
                            value
                            language
                        }
                        description(language: "$language") {
                            value
                            language
                        }
                        shortDescription(language: "$language") {
                            value
                            language
                        }
                        slug(language: "$language") {
                            value
                            language
                        }
                        categoryPath {
                            name(language: "$language") {
                                language
                            value
                        }
                        slug(language: "$language") {
                                language
                            value
                        }
                        }
                        parent {
                            id
                            categoryId
                            name(language: "$language") {
                                value
                                language
                            }
                            description(language: "$language") {
                                value
                                language
                            }
                            shortDescription(language: "$language") {
                                value
                                language
                            }
                            slug(language: "$language") {
                                value
                                language
                            }
                        }
                        slugs: slug {
                            value
                            language
                        }
                    }
                }
            QUERY;

        return $gql;
    }
}