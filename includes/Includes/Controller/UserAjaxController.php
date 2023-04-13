<?php 

namespace Propeller\Includes\Controller;

use Propeller\Propeller;
use stdClass;

class UserAjaxController extends BaseAjaxController {
    protected $user;
    protected $object_name = 'Login';

    public function __construct() {
        parent::__construct();

        $this->user = new UserController();
    }

    public function login($data, $skip_recaptcha = false) {
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();

        $proceed = true;

        if ($this->validate_form_request('nonce')) {

            if (Propeller::use_recaptcha() && !$skip_recaptcha) {
                if (!$this->validate_recaptcha($data)) {
                    $postprocess->status = false;
                    $postprocess->reload = false;
                    $postprocess->error = true;
                    $postprocess->message = __("Security check failed (reCaptcha)", "propeller-ecommerce") . " in login";

                    $proceed = false;
                }
            }

            if ($proceed) {
                $response = $this->user->login(
	                $data['user_mail'],
	                $data['user_password'],
                    '',
                    isset($data['referrer']) ? $data['referrer'] : ''
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
        $this->init_ajax();

        $data = $this->sanitize($_POST);
        
        $response = $this->user->user_prices(
	        isset($data['active']) && (int) $data['active'] == 1 ? true : false,
        );

	    do_action( 'propel_vat_switch', $response );

        die(json_encode($response));
    }

	public function do_login() {
        $this->init_ajax();

		$data = $this->sanitize($_POST);
		$this->login($data);
	}

	public function do_register() {
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();

        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);

            $proceed = true;

            if (Propeller::use_recaptcha() ) {
                if (!$this->validate_recaptcha($data)) {
                    $postprocess->status = false;
                    $postprocess->reload = false;
                    $postprocess->error = true;
                    $postprocess->message = __("Security check failed (reCaptcha)", "propeller-ecommerce") . " in register";

                    $response->postprocess = $postprocess;
                    
                    $proceed = false;
                }
            }

            if ($proceed) {
                $response = $this->user->register_user($data);

                if (!$response->postprocess->error && PROPELLER_REGISTER_AUTOLOGIN) {
	                $data['user_mail'] = $data['email'];
	                $data['user_password'] = $data['password'];

                    $this->login($data, true);
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
        $this->init_ajax();
        
        $postprocess = new stdClass();
        $response = new stdClass();

        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);
        
            $response = $this->user->forgot_password($data);
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