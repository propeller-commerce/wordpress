<?php 

namespace Propeller\Includes\Controller;

use stdClass;

class UserAjaxController {
    protected $user;
    protected $object_name = 'Login';

    public function __construct() {
        $this->user = new UserController();
    }

    public function do_login() {
        $response = $this->user->login(
            $_POST['user_mail'],
            $_POST['user_password'], 
            '', 
            isset($_POST['referrer']) ? $_POST['referrer'] : ''
        );

        $postprocess = new stdClass();

        if (isset($response->error) && $response->error) {
            if (isset($response->postprocess))
                $postprocess = $response->postprocess;

            $response = new stdClass();

            $postprocess->status = false;
            $postprocess->reload = false;
            $postprocess->error = true;
        }   
        else {
            if (isset($response->postprocess))
                $postprocess = $response->postprocess;
                
            $postprocess->toast = true;
            $postprocess->status = true;
            $postprocess->reload = true;
            $postprocess->error = null;
        }

        $response->postprocess = $postprocess;
        $response->object = $this->object_name;

        die(json_encode($response));
    }

    public function user_prices() {
        $response = $this->user->user_prices(
            $_POST['active']
        );

        die(json_encode($response));
    }

    public function do_register() {
        $response = $this->user->register_user($_POST);

        $response->object = 'Register';

        die(json_encode($response));
    }

    public function forgot_password() {
        $response = $this->user->forgot_password($_POST);

        $response->object = 'Register';

        die(json_encode($response));
    }
}