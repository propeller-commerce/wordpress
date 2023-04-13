<?php

namespace Propeller\Includes\Model;

class OrderModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_orders($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                orders($str_args) {
                    start
                    end
                    itemsFound
                    offset
                    page
                    pages
                    items {
                        cartId
                        currency
                        currencyRatio
                        date
                        email
                        emailDate
                        externalId
                        id
                        items {
                            id
                            class
                        }
                        reference
                        remarks
                        shipments {
                            date
                            id
                            orderId
                            printDate
                            status
                            totalDiscountValue
                            totalGross
                            totalNet
                            totalTax
                            trackAndTrace {
                                carrierId
                                code
                                id
                                orderId
                                shipmentId
                            }
                        }
                        siteId
                        source
                        status
                        statusDate
                        total {
                            gross
                            net
                            tax
                            discountType
                            discountValue
                            taxPercentages {
                                percentage
                                total
                            }
                        }
                        type
                        userId
                        uuid
                    }
                }
            }        
        QUERY;

        return $gql;
    }

    public function get_order($arguments, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $order_data = $this->order_data($images_args, $language);
        
        $gql = <<<QUERY
            query {
                order($str_args) {
                    $order_data
                }
            }        
        QUERY;

        return $gql;
    }

    public function get_pdf($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                orderGetPDF($str_args) {
                    base64
                    contentType
                    fileName
                }
            }
        QUERY;

        return $gql;
    }

    public function order_cofirm_email($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            query {
                orderSendConfirmationEmail($str_args) {
                    messageId
                    success
                }
            }
        QUERY;

        return $gql;
    }

    public function change_status($arguments, $images_args, $language) {
        $str_args = $this->parse_arguments($arguments);

        $order_data = $this->order_data($images_args, $language);

        $gql = <<<QUERY
            mutation {
                orderSetStatus($str_args) {
                    $order_data
                }
            }
        QUERY;

        return $gql;
    }

    public function order_data($images_args, $language) {
        $media_images_gql = $this->extract_query($images_args);

        $track_attributes = $this->product_track_attributes();

        $gql = <<<QUERY
            cartId
            currency
            currencyRatio
            date
            email
            emailDate
            externalId
            id
            reference
            remarks
            shipments {
                date
                id
                orderId
                printDate
                status
                totalDiscountValue
                totalGross
                totalNet
                totalTax
                trackAndTrace {
                    carrierId
                    code
                    id
                    orderId
                    shipmentId
                }
            }
            siteId
            source
            status
            statusDate
            total {
                gross
                net
                tax
                discountType
                discountValue
                taxPercentages {
                    percentage
                    total
                }
            }
            type
            userId
            uuid
            accountManagerId
            language
            pickupStoreId
            deliveryAddress: addresses(type: delivery) {
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
            invoiceAddress: addresses(type: invoice) {
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
            items {
                id
                orderId
                productId
                class
                name
                price
                priceTotal
                tax
                taxCode
                taxPercentage
                discount
                quantity
                sku
                supplier
                supplierCode
                manufacturer
                manufacturerCode
                isBonus
                notes
                parentOrderItemId
                product {
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
                    cluster {
                        id
                        clusterId
                        urlId: clusterId
                        slug(language: "$language") {
                            value
                            language
                        }
                    }
                    name(language: "$language") {
                        value
                        language
                    }
                    slug(language: "$language") {
                        value
                        language
                    }
                    $track_attributes
                    $media_images_gql
                }
            }
            paymentData {
                gross
                method
                net
                status
                statusDate
                tax
                taxPercentage
            }
            postageData {
                method
                gross
                net
                partialDeliveryAllowed
                requestDate
                tax
                taxPercentage
            }
            shipments {
                date
                id
                orderId
                printDate
                status
                totalDiscountValue
                totalGross
                totalNet
                totalTax
                trackAndTrace {
                    carrierId
                    code
                    id
                    orderId
                    shipmentId
                }
            }
        QUERY;

        return $gql;
    }
}
