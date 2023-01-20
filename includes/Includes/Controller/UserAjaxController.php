<?php 

namespace Propeller\Includes\Controller;

use Propeller\Propeller;
use stdClass;

class UserAjaxController extends BaseAjaxController {
    protected $user;
    protected $object_name = 'Login';

    public function __construct() {
        $this->user = new UserController();
    }

    public function do_login() {
        $postprocess = new stdClass();
        $response = new stdClass();

        $proceed = true;

        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            if (Propeller::use_recaptcha() ) {
                if (!$this->validate_recaptcha($_POST)) {
                    $postprocess->status = false;
                    $postprocess->reload = false;
                    $postprocess->error = true;
                    $postprocess->message = __("Security check failed (reCaptcha)", "propeller-ecommerce");

                    $proceed = false;
                }
            }

            if ($proceed) {
                $response = $this->user->login(
                    $_POST['user_mail'],
                    $_POST['user_password'], 
                    '', 
                    isset($_POST['referrer']) ? $_POST['referrer'] : ''
                );
        
                if (isset($response->error) && $response->error) {
                    if (isset($response->postprocess))
                        $postprocess = $response->postprocess;
        
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
            }
        }
        else {
            $postprocess->status = false;
            $postprocess->reload = false;
            $postprocess->error = true;
            $postprocess->message = __("Security check failed", "propeller-ecommerce");
        }    

        $response->postprocess = $postprocess;
        $response->object = $this->object_name;

        die(json_encode($response));
    }
    
    public function user_prices() {
        $_POST = $this->sanitize($_POST);
        
        $response = $this->user->user_prices(
            $_POST['active']
        );

	    do_action( 'propel_vat_switch', $response );

        die(json_encode($response));
    }

    public function do_register() {
        $postprocess = new stdClass();
        $response = new stdClass();

        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $proceed = true;

            if (Propeller::use_recaptcha() ) {
                if (!$this->validate_recaptcha($_POST)) {
                    $postprocess->status = false;
                    $postprocess->reload = false;
                    $postprocess->error = true;
                    $postprocess->message = __("Security check failed (reCaptcha)", "propeller-ecommerce");

                    $response->postprocess = $postprocess;
                    
                    $proceed = false;
                }
            }

            if ($proceed) {
                $response = $this->user->register_user($_POST);

                if (!$response->postprocess->error && PROPELLER_REGISTER_AUTOLOGIN) {
                    $_POST['user_mail'] = $_POST['email'];
                    $_POST['user_password'] = $_POST['password'];
    
                    $this->do_login();
                }
            }
        }
        else {
            $postprocess->status = false;
            $postprocess->reload = false;
            $postprocess->error = true;
            $postprocess->message = __("Security check failed", "propeller-ecommerce");

            $response->postprocess = $postprocess;
        }    
        
        $response->object = 'Register';

        die(json_encode($response));
    }

    public function forgot_password() {
        $postprocess = new stdClass();
        $response = new stdClass();

        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);
        
            $response = $this->user->forgot_password($_POST);
        }
        else {
            $postprocess->error = true;
            $postprocess->message = __("Security check failed", "propeller-ecommerce");
            $response->postprocess = $postprocess;
        }    

        $response->object = 'Register';

        die(json_encode($response));
    }
}