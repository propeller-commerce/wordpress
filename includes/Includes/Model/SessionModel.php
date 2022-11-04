<?php

namespace Propeller\Includes\Model;

class SessionModel {
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
}