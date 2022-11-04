<?php

namespace Propeller\Includes\Model;

class AuthModel extends BaseModel {
    public function __construct() {
        
    }

    public function login($username, $password, $provider = "") {
        $gql = <<<QUERY
            mutation {
                login(
                    input: {
                        email: "$username"
                        password: "$password"
                        provider: "$provider"
                    }
                ) {
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

    public function refresh($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                exchangeRefreshToken($str_args) {
                    access_token
                    refresh_token
                    expires_in
                    token_type
                    user_id
                }
            }
        QUERY;

        return $gql;
    }

    public function create($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                authenticationCreate($str_args) {
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

    public function logout($arguments) {
        $str_args = $this->parse_arguments($arguments);

        $gql = <<<QUERY
            mutation {
                logout($str_args) {
                    todo
                }
            }       
        QUERY;

        return $gql;
    }
}

