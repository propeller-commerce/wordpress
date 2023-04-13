<?php

namespace Propeller\Includes\Model;

class ShoppingCartModel extends BaseModel {
    public function __construct() {
        
    }

    public function cart_start($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
            mutation {
                cartStart($str_args) {
                    $cart_data
                }
            }
        QUERY;

        return $gql;
    }

    public function set_user($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
            mutation {
                cartSetUser(
                    $str_args
                ) {
                    cart {
                        $cart_data
                    }
                    response {
                        data
                        messages
                    }
                }
            }        
        QUERY;

        return $gql;
    }

    public function cart_update_address($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
            mutation {
                cartUpdateAddress($str_args) {
                    cart {
                        $cart_data
                    }
                    response {
                        data
                        messages
                    }
                }
            }
        QUERY;

        return $gql;
    }

    public function add_item($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartAddItem($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function add_item_bundle($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartAddBundle($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function update_item($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartUpdateItem($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function delete_item($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartDeleteItem($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function action_code($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartAddActionCode($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function remove_action_code($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartRemoveActionCode($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function voucher_code($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartAddVoucherCode($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function remove_voucher_code($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartRemoveVoucherCode($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function update($arguments, $images_args, $crossupsells_args, $language) {
        $str_args = $this->parse_arguments($arguments);
        
        $cart_data = $this->cart_data($images_args, $crossupsells_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartUpdate($str_args) {
                            cart {
                                $cart_data
                            }
                            response {
                                data
                                messages
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function process($arguments, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $order_model = new OrderModel();
        
        $order_data = $order_model->order_data($images_args, $language);

        $gql = <<<QUERY
                    mutation {
                        cartProcess($str_args) {
                            cartOrderId
                            response {
                                data
                                messages
                            }
                            order {
                                $order_data                  
                            }
                        }
                    }
                QUERY;

        return $gql;
    }

    public function cart_data($images_args, $crossupsells_args, $language) {
        $media_images_gql = $this->extract_query($images_args);

        $crossupsells_str_args = $this->parse_arguments($crossupsells_args);

        $tax_zone = PROPELLER_DEFAULT_TAXZONE;

        $gql = <<<QUERY
            cartId
            userId
            carrier
            notes
            reference
            extra3
            extra4
            orderStatus
            actionCode
            couponCode
            vouchers {
                code
                name
                description
                ruleId
                redeemed
                combinable
                partialRedemption
                available
                remaining
            }
            dateCreated
            dateChanged
            paymentData {
                netAmount
                grossAmount
                tax
                taxPercentage
                method
            }
            postageData {
                shippingMethod
                postageTaxPercentage
                requestDate
                postage
                postageNet
                partialDeliveryAllowed
            }
            total {
                subTotal
                subTotalNet
                discountPercentage
                totalNet
                totalGross
                discountNet
                discountGross
            }
            items {
                id
                notes
                price
                totalPrice
                sum
                totalSum
                quantity
                taxCode
                bundleId
                bundle {
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
                productId
                urlId: productId
                product {
                    class
                    urlId: productId
                    name(language: "$language") {
                        value
                        language
                    }
                    sku
                    slug(language: "$language") {
                        value
                        language
                    }
                    $media_images_gql
                    crossupsells($crossupsells_str_args) {
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
                    }
                    cluster {
                        id
                        clusterId
                        urlId: clusterId
                        slug(language: "$language") {
                            value
                            language
                        }
                    }
                }
            }
            bonusItems {
                id
                quantity
                totalPrice
                totalPriceNet
                product {
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
                    slug(language: "$language") {
                        value
                        language
                    }
                    name(language: "$language") {
                        value
                        language
                    }
                    $media_images_gql
                }
            }
            invoiceAddress {
                code
                firstName
                middleName
                lastName
                email
                gender
                country
                city
                street
                number
                numberExtension
                postalCode
                company
                phone
                mobile
                notes
                icp
                gender
                url
            }
            deliveryAddress {
                code
                firstName
                middleName
                lastName
                email
                gender
                country
                city
                street
                number
                numberExtension
                postalCode
                company
                phone
                mobile
                notes
                icp
                gender
                url
            }
            taxLevels {
                taxCode
                price
            }
            payMethods {
                description
                code
                externalCode
                type
                price
                amount
            }
            carriers {
                name
                description
                logo
                price
            }            
        QUERY;

        return $gql;
    }
}