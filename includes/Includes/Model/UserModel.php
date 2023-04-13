<?php

namespace Propeller\Includes\Model;

class UserModel extends BaseModel {
    public function __construct() {
        
    }

    public function start_session($site_id) {
        $gql = <<<QUERY
            mutation {
                startSession(siteId: $site_id) {
                    session {
                        uid
                        email
                        emailVerified
                        displayName
                        photoUrl
                        phoneNumber
                        disabled
                        isAnonymous
                        metadata {
                            lastSignInTime
                            creationTime
                            lastRefreshTime
                        }
                        tokensValidAfterTime
                        tenantId
                        passwordHash
                        passwordSalt
                        authDomain
                        lastLoginAt
                        createdAt
                        accessToken
                        refreshToken
                        expirationTime
                    }
                }
            }
        QUERY;

        return $gql;
    }

    public function viewer($attributes_args) {
        $attr_str_args = $this->parse_arguments($attributes_args);

        $attributes_gql = $this->attributes($attr_str_args);

        $gql = <<<QUERY
            query {
                viewer {
                    __typename
                    firstName
                    middleName
                    lastName
                    email
                    gender
                    phone
                    mobile
                    primaryLanguage
                    dateOfBirth
                    isLoggedIn
                    mailingList
                    ... on Contact {
                        userId: contactId
                        debtorId
                        company {
                            id
                            companyId
                            name
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
                                icp
                                notes
                                type
                                isDefault
                            }
                        }
                        $attributes_gql
                    }
                    ... on Customer {
                        userId: customerId
                        debtorId
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
                            icp
                            type
                            isDefault
                        }
                        $attributes_gql
                    }
                }
            }             
        QUERY;

        return $gql;
    }

    public function contact_create($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                contactRegister($str_args) {
                    contact {
                        __typename
                        firstName
                        middleName
                        lastName
                        email
                        gender
                        phone
                        mobile
                        primaryLanguage
                        dateOfBirth
                        isLoggedIn
                        mailingList
                        ... on Contact {
                            userId: contactId
                            company {
                                id
                                companyId
                                name
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
                    }
                }
            }
        QUERY;

        return $gql;
    }

    public function customer_create($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                customerRegister($str_args) {
                    customer {
                        __typename
                        firstName
                        middleName
                        lastName
                        email
                        gender
                        phone
                        mobile
                        primaryLanguage
                        dateOfBirth
                        isLoggedIn
                        mailingList
                        ... on Customer {
                            userId: customerId
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
                }
            }
        
        QUERY;

        return $gql;
    }

    public function forgot_password($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                passwordResetLink($str_args)
            }
        QUERY;

        return $gql;
    }

    public function get_user_data($arguments, $attributes_args = []) {
        $str_args = $this->parse_arguments($arguments);

        $attr_str_args = '';
        $attributes_gql = '';
        if (count($attributes_args)) {
            $attr_str_args = '(' . $this->parse_arguments($attributes_args) . ')';
            $attributes_gql = $this->attributes($attr_str_args);
        }
            

        $gql = <<<QUERY
            query {
                user($str_args) {
                    __typename
                    firstName
                    middleName
                    lastName
                    email
                    gender
                    phone
                    mobile
                    primaryLanguage
                    dateOfBirth
                    isLoggedIn
                    mailingList
                    ... on Contact {
                        userId: contactId
                        debtorId
                        company {
                            id
                            companyId
                            name
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
                        $attributes_gql
                    }
                    ... on Customer {
                        userId: customerId
                        debtorId
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
                        $attributes_gql
                    }
                }
            }        
        QUERY;

        return $gql;
    }

    
}