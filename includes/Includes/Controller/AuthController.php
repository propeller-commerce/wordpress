<?php

namespace Propeller\Includes\Controller;

use GraphQL\RawObject;

class AuthController extends BaseController {
    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('auth');
    }

    public function login($email, $password, $provider = '') {
        $type = 'login';

        $gql = $this->model->login($email, $password, $provider);
        
        $loginData = $this->query($gql, $type);

        return $loginData;
    }

    public function refresh() {
        $type = 'exchangeRefreshToken';

        $rawParams = '{
            refreshToken: "' . SessionController::get(PROPELLER_REFRESH_TOKEN) . '"
        }';

        $gql = $this->model->refresh(['input' => new RawObject($rawParams)]);

        $loginData = $this->query($gql, $type);

        return $loginData;
    }

    public function logout() {
        $type = 'logout';

        $gql = $this->model->logout(['siteId' => PROPELLER_SITE_ID]);
        
        $logoutData = $this->query($gql, $type);

        return $logoutData;
    }

    public function create($args) {
        $type = 'authenticationCreate';

        $raw_params = '{
            email: "' . $args['email'] . '",
            password: "' . $args['password'] . '"
        }';

        $gql = $this->model->create(['input' => new RawObject($raw_params)]);

        return $this->query($gql, $type);
    }
}