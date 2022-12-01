<?php

namespace Propeller\Includes\Model;

class ProductModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_product($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            query {
                product(
                    $str_args
                ) {
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
                    slugs: slug {
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
                    crossupsells(input: { types: [ACCESSORIES] }) {
                        type
                    }
                    ... on Product {
                        id
                        productId
                        shortName
                        manufacturerCode
                        eanCode
                        manufacturer
                        supplier
                        supplierCode
                        taxCode
                        status
                        isOrderable
                        hasBundle
                        isBundleLeader
                        originalPrice
                        suggestedPrice
                        minimumQuantity
                        unit
                        purchaseUnit
                        purchaseMinimumQuantity
                        inventory {
                            totalQuantity
                        }
                        price(taxZone: "$tax_zone") {
                            net
                            gross
                            quantity
                            discount {
                                value
                                formula
                                quantity
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
                        attributes($attr_str_args) {
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
                        crossupsells(input: { types: [ACCESSORIES] }) {
                            type
                            subtype
                            clusterId
                            productId
                            item {
                                class
                                name(language: "$language") {
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
                                    shortName
                                    manufacturerCode
                                    eanCode
                                    manufacturer
                                    supplier
                                    supplierCode
                                    taxCode
                                    status
                                    isOrderable
                                    hasBundle
                                    isBundleLeader
                                    originalPrice
                                    suggestedPrice
                                    minimumQuantity
                                    unit
                                    purchaseUnit
                                    purchaseMinimumQuantity
                                    inventory {
                                        totalQuantity
                                    }
                                    price(taxZone: "$tax_zone") {
                                        net
                                        gross
                                        quantity
                                        discount {
                                            value
                                            formula
                                            quantity
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
                        bundles {
                            comboId
                            name
                            description
                            condition
                            discount
                            price {
                                gross
                                net
                                originalGross
                                originalNet
                            }
                            items {
                                isLeader
                                price {
                                    gross
                                    net
                                }
                                product {
                                    class
                                    name(language: "$language") {
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
                                        shortName
                                        manufacturerCode
                                        eanCode
                                        manufacturer
                                        supplier
                                        supplierCode
                                        taxCode
                                        status
                                        isOrderable
                                        hasBundle
                                        isBundleLeader
                                        originalPrice
                                        suggestedPrice
                                        minimumQuantity
                                        unit
                                        purchaseUnit
                                        purchaseMinimumQuantity
                                        inventory {
                                            totalQuantity
                                        }
                                        price(taxZone: "$tax_zone") {
                                            net
                                            gross
                                            quantity
                                            discount {
                                                value
                                                formula
                                                quantity
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
                        category {
                            id
                            categoryId
                            name(language: "$language") {
                                value
                                language
                            }
                            slug(language: "$language") {
                                value
                                language
                            }
                        }
                    }
                }
            }        
        QUERY;

        return $gql;
    }

    public function check_product($arguments) {
        $str_args = $this->parse_arguments($arguments);
        
        $gql = <<<QUERY
            query {
                product(
                    $str_args
                ) {
                    class
                }
            }
        QUERY;

        return $gql;
    }

    public function check_cluster($arguments) {
        $str_args = $this->parse_arguments($arguments);
        
        $gql = <<<QUERY
            query {
                cluster(
                    $str_args
                ) {
                    class
                }
            }
        QUERY;

        return $gql;
    }

    public function get_cluster($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            query {
                cluster($str_args) {
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
                    slugs: slug {
                        value
                        language
                    }
                    crossupsells(input: { types: [ACCESSORIES] }) {
                        type
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
                                shortName
                                manufacturerCode
                                eanCode
                                manufacturer
                                supplier
                                supplierCode
                                taxCode
                                status
                                isOrderable
                                hasBundle
                                isBundleLeader
                                originalPrice
                                suggestedPrice
                                minimumQuantity
                                unit
                                purchaseUnit
                                purchaseMinimumQuantity
                                inventory {
                                    totalQuantity
                                }
                                price(taxZone: "$tax_zone") {
                                    net
                                    gross
                                    quantity
                                    discount {
                                        value
                                        formula
                                        quantity
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
                                attributes($attr_str_args) {
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
                                category {
                                    id
                                    categoryId
                                    name(language: "$language") {
                                        value
                                        language
                                    }
                                    slug(language: "$language") {
                                        value
                                        language
                                    }
                                }
                                $media_images_gql
                                bundles {
                                    comboId
                                    name
                                    description
                                    condition
                                    discount
                                    price {
                                        gross
                                        net
                                        originalGross
                                        originalNet
                                    }
                                    items {
                                        isLeader
                                        price {
                                            gross
                                            net
                                        }
                                        product {
                                            class
                                            name(language: "$language") {
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
                                                shortName
                                                manufacturerCode
                                                eanCode
                                                manufacturer
                                                supplier
                                                supplierCode
                                                taxCode
                                                status
                                                isOrderable
                                                hasBundle
                                                isBundleLeader
                                                originalPrice
                                                suggestedPrice
                                                minimumQuantity
                                                unit
                                                purchaseUnit
                                                purchaseMinimumQuantity
                                                inventory {
                                                    totalQuantity
                                                }
                                                price(taxZone: "$tax_zone") {
                                                    net
                                                    gross
                                                    quantity
                                                    discount {
                                                        value
                                                        formula
                                                        quantity
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
                                    shortName
                                    manufacturerCode
                                    eanCode
                                    manufacturer
                                    supplier
                                    supplierCode
                                    taxCode
                                    status
                                    isOrderable
                                    hasBundle
                                    isBundleLeader
                                    originalPrice
                                    suggestedPrice
                                    minimumQuantity
                                    unit
                                    purchaseUnit
                                    purchaseMinimumQuantity
                                    inventory {
                                        totalQuantity
                                    }
                                    price(taxZone: "$tax_zone") {
                                        net
                                        gross
                                        quantity
                                        discount {
                                            value
                                            formula
                                            quantity
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
                                    attributes($attr_str_args) {
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
                                    category {
                                        id
                                        categoryId
                                        name(language: "$language") {
                                            value
                                            language
                                        }
                                        slug(language: "$language") {
                                            value
                                            language
                                        }
                                    }
                                    $media_images_gql
                                    crossupsells(input: { types: [ACCESSORIES] }) {
                                        type
                                        subtype
                                        product {
                                            class
                                            name(language: "$language") {
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
                                                shortName
                                                manufacturerCode
                                                eanCode
                                                manufacturer
                                                supplier
                                                supplierCode
                                                taxCode
                                                status
                                                isOrderable
                                                hasBundle
                                                isBundleLeader
                                                originalPrice
                                                suggestedPrice
                                                minimumQuantity
                                                unit
                                                purchaseUnit
                                                purchaseMinimumQuantity
                                                inventory {
                                                    totalQuantity
                                                }
                                                price(taxZone: "$tax_zone") {
                                                    net
                                                    gross
                                                    quantity
                                                    discount {
                                                        value
                                                        formula
                                                        quantity
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
                                    bundles {
                                        comboId
                                        name
                                        description
                                        condition
                                        discount
                                        price {
                                            gross
                                            net
                                            originalGross
                                            originalNet
                                        }
                                        items {
                                            isLeader
                                            price {
                                                gross
                                                net
                                            }
                                            product {
                                                class
                                                name(language: "$language") {
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
                                                    shortName
                                                    manufacturerCode
                                                    eanCode
                                                    manufacturer
                                                    supplier
                                                    supplierCode
                                                    taxCode
                                                    status
                                                    isOrderable
                                                    hasBundle
                                                    isBundleLeader
                                                    originalPrice
                                                    suggestedPrice
                                                    minimumQuantity
                                                    unit
                                                    purchaseUnit
                                                    purchaseMinimumQuantity
                                                    inventory {
                                                        totalQuantity
                                                    }
                                                    price(taxZone: "$tax_zone") {
                                                        net
                                                        gross
                                                        quantity
                                                        discount {
                                                            value
                                                            formula
                                                            quantity
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
                                id
                                name
                                searchId
                                group
                                typeParam
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
            }
        QUERY;

        return $gql;
    }

    public function cluster_crossupsells($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            query {
                cluster($str_args) {
                    crossupsells(input: { types: [ACCESSORIES] }) {
                        type
                        subtype
                        productId
                        clusterId
                        item {
                            class
                            name(language: "$language") {
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
                                shortName
                                manufacturerCode
                                eanCode
                                manufacturer
                                supplier
                                supplierCode
                                taxCode
                                status
                                isOrderable
                                hasBundle
                                isBundleLeader
                                originalPrice
                                suggestedPrice
                                minimumQuantity
                                unit
                                purchaseUnit
                                purchaseMinimumQuantity
                                inventory {
                                    totalQuantity
                                }
                                price(taxZone: "$tax_zone") {
                                    net
                                    gross
                                    quantity
                                    discount {
                                        value
                                        formula
                                        quantity
                                        validFrom
                                        validTo
                                    }
                                    taxCode
                                    type
                                }
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
                                    sku
                                    slug(language: "$language") {
                                        value
                                        language
                                    }
                                    ... on Product {
                                        id
                                        productId
                                        shortName
                                        manufacturerCode
                                        eanCode
                                        manufacturer
                                        supplier
                                        supplierCode
                                        taxCode
                                        status
                                        isOrderable
                                        hasBundle
                                        isBundleLeader
                                        originalPrice
                                        suggestedPrice
                                        minimumQuantity
                                        unit
                                        purchaseUnit
                                        purchaseMinimumQuantity
                                        price(taxZone: "$language") {
                                            net
                                            gross
                                            quantity
                                            discount {
                                                value
                                                formula
                                                quantity
                                                validFrom
                                                validTo
                                            }
                                            taxCode
                                            type
                                        }
                                        category {
                                            id
                                            categoryId
                                            name(language: "$language") {
                                                value
                                                language
                                            }
                                            slug(language: "$language") {
                                                value
                                                language
                                            }
                                        }
                                        $media_images_gql                                   
                                    }
                                }
                            }
                        }
                    }
                }
            }
            QUERY;

            return $gql;
    }

    public function product_crossupsells($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            query {
                product($str_args) {
                    crossupsells(input: { types: [ACCESSORIES] }) {
                        type
                        subtype
                        productId
                        clusterId
                        item {
                            class
                            name(language: "$language") {
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
                                shortName
                                manufacturerCode
                                eanCode
                                manufacturer
                                supplier
                                supplierCode
                                taxCode
                                status
                                isOrderable
                                hasBundle
                                isBundleLeader
                                originalPrice
                                suggestedPrice
                                minimumQuantity
                                unit
                                purchaseUnit
                                purchaseMinimumQuantity
                                inventory {
                                    totalQuantity
                                }
                                price(taxZone: "$tax_zone") {
                                    net
                                    gross
                                    quantity
                                    discount {
                                        value
                                        formula
                                        quantity
                                        validFrom
                                        validTo
                                    }
                                    taxCode
                                    type
                                }
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
                                    sku
                                    slug(language: "$language") {
                                        value
                                        language
                                    }
                                    ... on Product {
                                        id
                                        productId
                                        shortName
                                        manufacturerCode
                                        eanCode
                                        manufacturer
                                        supplier
                                        supplierCode
                                        taxCode
                                        status
                                        isOrderable
                                        hasBundle
                                        isBundleLeader
                                        originalPrice
                                        suggestedPrice
                                        minimumQuantity
                                        unit
                                        purchaseUnit
                                        purchaseMinimumQuantity
                                        price(taxZone: "$language") {
                                            net
                                            gross
                                            quantity
                                            discount {
                                                value
                                                formula
                                                quantity
                                                validFrom
                                                validTo
                                            }
                                            taxCode
                                            type
                                        }
                                        category {
                                            id
                                            categoryId
                                            name(language: "$language") {
                                                value
                                                language
                                            }
                                            slug(language: "$language") {
                                                value
                                                language
                                            }
                                        }
                                        $media_images_gql                                   
                                    }
                                }
                            }
                        }
                    }
                }
            }
        QUERY;

        return $gql;
    }

    public function get_slider_products($arguments, $images_query, $language) {
        $str_args = $this->parse_arguments($arguments);
        $media_images_gql = $this->extract_query($images_query);

        $gql = <<<QUERY
            query {
                products(
                    $str_args
                ) {
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
                                            totalQuantity
                                        }
                                        category {
                                            id
                                            categoryId
                                            name(language: "$language") {
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
                }
            }
        QUERY;

        return $gql;
    }

    public function get_products($arguments, $attributes_args, $filters_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);
        $filter_str_args = $this->parse_arguments($filters_args);

        $media_images_gql = $this->extract_query($images_args);

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            query {
                products($str_args) {
                    itemsFound
                    offset
                    page
                    pages
                    start
                    minPrice
                    maxPrice
                    end
                    filters($filter_str_args) {
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
                    items {
                        class
                        name(language: "$language") {
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
                            defaultProduct {
                                productId
                            }
                            products {
                                class
                                name(language: "$language") {
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
                }
            }        
        QUERY;

        return $gql;
    }

    public function global_search_products($arguments, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $media_images_gql = $this->extract_query($images_args);

        $gql = <<<QUERY
            query {
                products($str_args) {
                    itemsFound
                    items {
                        name(language: "$language") {
                            value
                            language
                        }
                        slug(language: "$language") {
                            value
                            language
                        }
                        ... on Product {
                            $media_images_gql
                        }
                    }
                }
            }
        QUERY;

        return $gql;
    }

    
    
}