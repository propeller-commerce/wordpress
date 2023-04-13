<?php

namespace Propeller\Includes\Model;

class ProductModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_product($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        $attr_str_args = $this->parse_arguments($attributes_args);
        // $attributes_gql = $this->attributes($attr_str_args);
        
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
                    category {
                        urlId: categoryId
                        name(language: "$language") {
                            language
                          value
                        }
                        slug(language: "$language") {
                                language
                            value
                        }
                    }
                    categoryPath {
                        urlId: categoryId
                        name(language: "$language") {
                            language
                          value
                      }
                      slug(language: "$language") {
                            language
                          value
                      }
                    }
                    accessories: crossupsells(input: { types: [ACCESSORIES] }) {
                        type
                    }
                    alternatives: crossupsells(input: { types: [ALTERNATIVES] }) {
                        type
                    }
                    related: crossupsells(input: { types: [RELATED] }) {
                        type
                    }
                    ... on Product {
                        id
                        productId
                        urlId: productId
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
                        package
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
                        $track_attributes
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
                                        urlId: productId
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
                                        package
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
                            urlId: categoryId
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

    public function check_product_language($slug, $product_id, $type, $language) {
        $str_args = ($product_id ? $type . "Id: $product_id" : "slug: \"$slug\"") . ", language: \"$language\"";

        $gql = <<<QUERY
            query {
                $type(
                    $str_args
                ) {
                    slugs: slug {
                        value
                        language
                    }
                }
            }
        QUERY;

        return $gql;
    }

    public function get_cluster_attributes($arguments, $language) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                cluster($str_args) {
                    ... on Cluster {
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

    private function cluster_attributes($attributes_args) {
        if (!$attributes_args)
            return "";

        $attr_str_args = $this->parse_arguments($attributes_args);

        return $this->attributes($attr_str_args);
    }

    public function get_cluster($arguments, $attributes_args, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $cluster_attributes = $this->cluster_attributes($attributes_args);

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
                    accessories: crossupsells(input: { types: [ACCESSORIES] }) {
                        type
                    }
                    alternatives: crossupsells(input: { types: [ALTERNATIVES] }) {
                        type
                    }
                    related: crossupsells(input: { types: [RELATED] }) {
                        type
                    }
                    category {
                        urlId: categoryId
                        name(language: "$language") {
                            language
                          value
                        }
                        slug(language: "$language") {
                                language
                            value
                        }
                    }
                    categoryPath {
                        urlId: categoryId
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
                        urlId: clusterId
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
                                urlId: productId
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
                                $cluster_attributes         
                                $track_attributes
                                category {
                                    id
                                    categoryId
                                    urlId: categoryId
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
                                                urlId: productId
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
                                    urlId: productId
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
                                    $cluster_attributes
                                    $track_attributes
                                    category {
                                        id
                                        categoryId
                                        urlId: categoryId
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
                                                    urlId: productId
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

    public function crossupsells($arguments, $attributes_args, $images_args, $language, $type, $class) {
        $str_args = $this->parse_arguments($arguments);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $track_attributes = $this->product_track_attributes();

        $crossupsell_type = strtoupper($type);

        $gql = <<<QUERY
            query {
                $class($str_args) {
                    $type: crossupsells(input: { types: [$crossupsell_type] }) {
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
                            shortDescription(language: "$language") {
                                value
                                language
                            }
                            ... on Product {
                                id
                                productId
                                urlId: productId
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
                                package
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
                                $track_attributes
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
                                    name(language: "$language") {
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
                                        urlId: productId
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
                                            urlId: categoryId
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
                                        $track_attributes                            
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

    public function specifications($product_id, $attributes_args) {
        $attr_str_args = $this->parse_arguments($attributes_args);

        $attributes_gql = $this->attributes($attr_str_args);

        $gql = <<<QUERY
            query {
                product(productId: $product_id) {
                    productId
                    eanCode
                    manufacturer
                    $attributes_gql
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
                            urlId: productId
                            manufacturerCode
                            eanCode
                            manufacturer
                            supplierCode
                            taxCode
                            status
                            isOrderable
                            package
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
                            urlId: clusterId
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
                            urlId: productId
                            manufacturerCode
                            eanCode
                            manufacturer
                            supplierCode
                            taxCode
                            status
                            isOrderable
                            package
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
                                    urlId: productId
                                    manufacturerCode
                                    eanCode
                                    manufacturer
                                    supplierCode
                                    taxCode
                                    status
                                    isOrderable
                                    package
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
            }        
        QUERY;

        return $gql;
    }

    public function global_search_products($arguments, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $media_images_gql = $this->extract_query($images_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $gql = <<<QUERY
            query {
                products($str_args) {
                    itemsFound
                    items {
                        class
                        sku
                        name(language: "$language") {
                            value
                            language
                        }
                        slug(language: "$language") {
                            value
                            language
                        }
                        ... on Product {
                            productId
                            urlId: productId
                            minimumQuantity
                            unit
                            $media_images_gql
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
                        }
                    }
                }
            }
        QUERY;

        // ... on Cluster {
        //     defaultProduct {
        //         $media_images_gql
        //     }
        //     products {
        //         $media_images_gql
        //     }
        // }

        return $gql;
    }

    public function downloads($product_id, $downloads_args) {
        $media_downloads_gql = $this->extract_query($downloads_args);

        $gql = <<<QUERY
            query {
                product(productId: $product_id) {
                    $media_downloads_gql
                }
            }
        QUERY;

        return $gql;
    }

    public function videos($product_id, $videos_args) {
        $media_videos_gql = $this->extract_query($videos_args);

        $gql = <<<QUERY
            query {
                product(productId: $product_id) {
                    $media_videos_gql
                }
            }
        QUERY;

        return $gql;
    }
}