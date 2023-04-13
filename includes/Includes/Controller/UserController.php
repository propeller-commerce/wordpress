<?php

namespace Propeller\Includes\Controller;

use DateInterval;
use GraphQL\RawObject;
use stdClass;
use DateTime;
use Exception;
use Propeller\Includes\Enum\AddressType;
use Propeller\Includes\Enum\EmailEventTypes;
use Propeller\Includes\Enum\OrderStatus;
use Propeller\Includes\Enum\OrderType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Enum\UserTypes;
use Propeller\Includes\Object\Attribute as ObjectAttribute;


class UserController extends BaseController {
    protected $type = 'user';
    protected $model;
    protected $AuthController;
    protected $ShoppingCart;
    protected $response;

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('user');

        $this->AuthController = new AuthController();
        $this->ShoppingCart = new ShoppingCartController();

        add_action('wp_logout', array($this, 'logout'), PHP_INT_MAX);
        add_action('logout_redirect', array($this, 'logout_redirect'), PHP_INT_MAX);
    }

    /*
        User filters
    */
    public function account_title($title) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-title.php');
    }

    public function account_menu($obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-sidemenu.php');
    }

    public function account_user_details_title($title) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-user-details-title.php');
    }

    public function account_user_details($user, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-user-details.php');
    }

    public function account_company_details($user) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-company-details.php');
    }

    public function account_pass_newsletter_title($title) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-pass-newsletter-title.php');
    }
    
    public function account_pass_newsletter($user) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-pass-newsletter.php');
    }
    
    public function account_addresses_title($title) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-addresses-title.php');
    }




    
    public function my_account()
    {
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-page.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);
            die;
        }
    }

    public function account_mobile() {
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-mobile.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);

            die;
        }
       
    }
    public function account_details()
    {   
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-details.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);
            die;
        }
    }

    public function account_prices()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-prices-toggle.php');
        return ob_get_clean();
    }

    public function account_favorites() 
    {
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-favorites.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);
            die;
        }
    }

    public function account_orderlist() 
    {
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orderlist.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);
            die;
        }
    }

    public function account_invoices() 
    {
        if(self::is_propeller_logged_in()) {
            ob_start();
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-invoices.php');
            return ob_get_clean();
        }
        else {
            $redirect_url = home_url();

            $redirect_url = '/' . PageController::get_slug(PageType::LOGIN_PAGE);
            
            wp_safe_redirect($redirect_url);
            die;
        }
    }

    public function mini_account()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-mini-account.php');
        return ob_get_clean();
    }

    public function edit_address()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-edit-address.php');
        return ob_get_clean();
    }

    public function login_form()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-login-form.php');
        return ob_get_clean();
    }

    public function forgot_password_form()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-forgot-password-form.php');
        return ob_get_clean();
    }

    public function reset_password_form()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-reset-password-form.php');
        return ob_get_clean();
    }

    public function register_form()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-register-form.php');
        return ob_get_clean();
    }

    public function newsletter_subscription()
    {
        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-newsletter-subscription-form.php');
        return ob_get_clean();
    }
    
    public function start_session() {
        if (!self::is_logged_in()) {
            if (PROPELLER_USER_TRACK_ATTR != '') {
                $track_attrs = explode(',', PROPELLER_USER_TRACK_ATTR);
    
                foreach ($track_attrs as $track_attr) {
                    SessionController::set($track_attr, 'guest');
                }
            }
            else {
                SessionController::set(PROPELLER_USER_ATTR_VALUE, 'guest');
            }

            return null;
        }
            

        if (SessionController::has(PROPELLER_SESSION))
            return null;

        $type = 'startSession';

        $postprocess = new stdClass();
        $this->response = new stdClass();
        
        if (defined('PROPELLER_SITE_ID')) {
            $gql = $this->model->start_session(PROPELLER_SITE_ID);
        
            $sessionData = $this->query($gql, $type);
    
            if (is_object($sessionData)) {
                $this->postprocess_sesion($sessionData);
                
                $postprocess->dummy = 1;
            }
            else {
                $postprocess->message = $sessionData;
            }
    
            $this->response->postprocess = $postprocess;
        }
        else {
            $postprocess->dummy = 1;
            $this->response->postprocess = $postprocess;
        }
        
        return $this->response;
    }

    public function login($email, $password, $provider = '', $referrer = '') {
        $loginData = $this->AuthController->login($email, $password, $provider);

        $postprocess = new stdClass();
        $this->response = new stdClass();
        
        if (is_object($loginData)) {
            $this->postprocess_sesion($loginData);

            if (!$loginData->session->isAnonymous) {
                $postprocess->is_logged_in = true;
                $postprocess->redirect = $referrer != '' ? $referrer : home_url();
                
                // fetch user data
                $userData = $this->get_viewer();

                // do wp login if setting is enabled
                if (PROPELLER_WP_SESSIONS) {
                    $this->propeller_wp_login($userData);
                }
                
                // wp_clear_auth_cookie();
                $this->set_cookie(PROPELLER_USER_SESSION, $loginData->session->email, time() + (2 * DAY_IN_SECONDS));
                SessionController::set(PROPELLER_USER_ID, $userData->userId);

                if (isset($userData->attributes))
                    $this->process_attributes($userData->attributes);

                // set user to current cart
                $cartUserData = $this->ShoppingCart->set_user($userData->userId);
                
                $this->postprocess_login();
                
                $postprocess->message = __('Welcome', 'propeller-ecommerce') . ' ' . $userData->firstName;
                
                if (!is_object($cartUserData)) {
                    $postprocess->message = __('Failed assigning user to cart', 'propeller-ecommerce');
                }
            }
        }            
        else {
            $this->response->error = true;

            if(strpos($loginData, 'user') !== false || strpos($loginData, 'password') !== false) {
                $postprocess->message_user = true;
                $postprocess->message = __('Wrong credentials.', 'propeller-ecommerce');  
            }
            // else if(strpos($loginData, 'password') !== false) {
            //     $postprocess->message_pass = true;
            //     $postprocess->message = __('Your password is incorrect.', 'propeller-ecommerce'); 
            // }
            else 
                $postprocess->message = $loginData;
        }
            

        $this->response->postprocess = $postprocess;

        return $this->response;
    }

    protected function postprocess_login() {
        // set default user addresses in cart
        try {
            $this->ShoppingCart->set_user_default_cart_address();
        }
        catch (Exception $ex) {}

        $this->ShoppingCart->change_order_type(OrderType::REGULAR, false);

        // set user specific prices upon login
        SessionController::set(PROPELLER_SPECIFIC_PRICES, false);

        // is default delivery address changed
        SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, false);

        // preserve order status (NEW, REQUEST, etc)
        SessionController::set(PROPELLER_ORDER_STATUS_TYPE, OrderStatus::ORDER_STATUS_NEW);

        // delete menu transient/s
        // CacheController::delete(CacheController::PROPELLER_MENU_TRANSIENT);
    }

    protected function propeller_wp_login($userData) {
        $email = $userData->email;
        $response = [];
        
        $user = get_user_by("email", $email);
        if (!$user)
            $user = get_user_by("login", $email );
    
        if (!$user) {
            $user_name = explode('@', $email)[0];
            $userdata = array(
                'user_login' => $email,
                'user_pass'  => wp_generate_password( 10, false ),
                'user_email' => $email,
                'user_nicename' => $userData->firstName . ' ' . $userData->lastName,
                'user_name' => $user_name,
                'first_name' => $userData->firstName
            );
        
            $user_id = wp_insert_user($userdata);
    
            if (!is_wp_error($user_id))
                $user = get_user_by("email", $email);
        }
    
        if (!is_wp_error($user))
        {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID); // Set the current user detail
            wp_set_auth_cookie($user->ID); // Set auth details in cookie
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }
        
        return $response['success'];
    }

    public function refresh_access_token() {
        $expiration_date = new DateTime(SessionController::get(PROPELLER_EXPIRATION_TIME));
        $now_date = new DateTime('NOW');
        
        $diff = $now_date->diff($expiration_date);
        
        if ($diff->h != 0) {
            $refreshData = $this->AuthController->refresh();

            if (is_object($refreshData)) {
                $this->postprocess_refresh_token($refreshData);
            }
        }
    }

    public static function is_logged_in() {
        if (defined('PROPELLER_WP_SESSIONS') && PROPELLER_WP_SESSIONS) {
            return is_user_logged_in() && self::is_propeller_logged_in();
        }
        else {
            return self::is_propeller_logged_in();
            
            if (is_user_logged_in() && current_user_can('administrator'))
                return true;
        }
    }

    public static function is_propeller_logged_in() {
        return (SessionController::has(PROPELLER_SESSION) && !SessionController::get(PROPELLER_SESSION)->isAnonymous) &&
               (SessionController::has(PROPELLER_USER_DATA) && (isset(SessionController::get(PROPELLER_USER_DATA)->userId)));
                // && isset($_COOKIE[PROPELLER_USER_SESSION]);
    }

    public function logout($redirect = true) {
        $this->remove_cookie(PROPELLER_USER_SESSION);
        
        if (PROPELLER_USER_TRACK_ATTR != '') {
            $track_attrs = explode(',', PROPELLER_USER_TRACK_ATTR);

            foreach ($track_attrs as $track_attr) {
                SessionController::set($track_attr, 'guest');
            }
        }
        else {
            SessionController::set(PROPELLER_USER_ATTR_VALUE, 'guest');
        }

        // SessionController::set(PROPELLER_USER_ATTR_VALUE, 'guest');
        // CacheController::delete(CacheController::PROPELLER_MENU_TRANSIENT);

        if (SessionController::has(PROPELLER_ACCESS_TOKEN))
            $this->AuthController->logout();

        SessionController::end();

        if ($redirect) {
            $redirect_url = home_url();

            if (PROPELLER_WP_CLOSED_PORTAL)
                $redirect_url = home_url('/' . PageController::get_slug(PageType::LOGIN_PAGE));
            
            wp_safe_redirect($redirect_url);
    
            return $redirect_url;
        }
    }

    public function logout_redirect() {
        $redirect_url = home_url();

        if (PROPELLER_WP_CLOSED_PORTAL)
            $redirect_url = home_url('/' . PageController::get_slug(PageType::LOGIN_PAGE));
        
        return $redirect_url;
    }

    public function register_user($args) {
        $registration_response = null;

        switch ($args['user_type']) {
            case UserTypes::CONTACT:
                $registration_response = $this->register_contact($args);

                break;
            case UserTypes::CUSTOMER: 
                $registration_response = $this->register_customer($args);

                break;
            default: 
                $registration_response = $this->register_contact($args);

                break;
        }

        $this->response = new stdClass();

        $postprocess = new stdClass();
        $postprocess->message = "";

        if (is_object($registration_response)) {
            $postprocess->message .= __('Registration successful', 'propeller-ecommerce');
            
            $user_data = $args['user_type'] == UserTypes::CONTACT 
                         ? $registration_response->contact 
                         : $registration_response->customer;

            // Preserve addresses
            $addressController = new AddressController();
            $addressController->set_user_data($user_data);
            $addressController->set_user_type($user_data->__typename);
            $addressController->set_is_registration(true);

            // Check what can be filled in from the user data
            if (!isset($args['invoice_address']['email']))
                $args['invoice_address']['email'] = $args['email'];
            if (!isset($args['invoice_address']['phone']))
                $args['invoice_address']['phone'] = $args['phone'];
            if (!isset($args['invoice_address']['firstName']))
                $args['invoice_address']['firstName'] = $args['firstName'];
            if (!isset($args['invoice_address']['lastName']))
                $args['invoice_address']['lastName'] = $args['lastName'];
            if (!isset($args['invoice_address']['middleName']))
                $args['invoice_address']['middleName'] = $args['middleName'];
            $args['invoice_address']['isDefault'] = 'Y';

            $address_user_id = $user_data->__typename == UserTypes::CUSTOMER 
                               ? $user_data->userId 
                               : $user_data->company->companyId;
            
            $addressResult = $addressController->add_address($args['invoice_address'], $address_user_id);
            if (!is_object($addressResult))
                $postprocess->message .= '<br />' . __('Failed to create invoice address.');

            if (isset($args['save_delivery_address'])) {
                $args['invoice_address']['type'] = AddressType::DELIVERY;
                $addressResult = $addressController->add_address($args['invoice_address'], $address_user_id);

                if (!is_object($addressResult))
                    $postprocess->message .= '<br />' . __('Failed to create delivery address.');
            }                
            else {
                if (!isset($args['delivery_address']['email']))
                    $args['delivery_address']['email'] = $args['email'];
                if (!isset($args['delivery_address']['phone']))
                    $args['delivery_address']['phone'] = $args['phone'];
                if (!isset($args['delivery_address']['firstName']))
                    $args['delivery_address']['firstName'] = $args['firstName'];
                if (!isset($args['delivery_address']['lastName']))
                    $args['delivery_address']['lastName'] = $args['lastName'];
                if (!isset($args['delivery_address']['middleName']))
                    $args['delivery_address']['middleName'] = $args['middleName'];
                $args['delivery_address']['isDefault'] = 'Y';

                $addressResult = $addressController->add_address($args['delivery_address'], $address_user_id);

                if (!is_object($addressResult))
                    $postprocess->message .= '<br />' . __('Failed to create delivery address.');
            }

            $postprocess->is_registered = true;
            $redirect_url = $this->buildUrl('', PageController::get_slug(PageType::LOGIN_PAGE));
            $postprocess->redirect = esc_url_raw($redirect_url);

            $this->send_registration_email($user_data);

            $postprocess->error = false;
        }
        else {
            $postprocess->error = true;
            $postprocess->message .= '<br />' . $registration_response;
        }
            

        $this->response->postprocess = $postprocess;

        return $this->response;
    }

    private function register_contact($args) {
        $type = 'contactRegister';

        $companyController = new CompanyController();

        $company_data = [
            'name' => $args['company_name'], 
            'taxNumber' => $args['taxNumber'],
            'parentId' => $args['parentId']
        ];

        $company_response = $companyController->create($company_data);

        if (is_object($company_response))
            $args['parentId'] = $company_response->companyId;
        else
            return $company_response;

        $raw_params_array = [];

        $raw_params_array[] = 'firstName: "'. $args['firstName'] .'"';
        $raw_params_array[] = 'middleName: "'. $args['middleName'] .'"';
        $raw_params_array[] = 'lastName: "'. $args['lastName'] .'"';
        $raw_params_array[] = 'primaryLanguage: "'. PROPELLER_LANG .'"';
        // $raw_params_array[] = 'gender: '. new RawObject(isset($args['gender']) ? $args['gender'] : 'U');

        if (isset($args['company']) && !empty($args['company'])) $raw_params_array[] = 'company: "'. $args['company'] .'"';
        if (isset($args['email']) && !empty($args['email'])) $raw_params_array[] = 'email: "'. $args['email'] .'"';
        if (isset($args['homepage']) && !empty($args['homepage'])) $raw_params_array[] = 'homepage: "'. $args['homepage'] .'"';
        if (isset($args['cocNumber']) && !empty($args['cocNumber'])) $raw_params_array[] = 'cocNumber: "'. $args['cocNumber'] .'"';
        if (isset($args['phone']) && !empty($args['phone'])) $raw_params_array[] = 'phone: "'. $args['phone'] .'"';
        if (isset($args['mobile']) && !empty($args['mobile'])) $raw_params_array[] = 'mobile: "'. $args['mobile'] .'"';
        if (isset($args['dateOfBirth']) && !empty($args['dateOfBirth'])) $raw_params_array[] = 'dateOfBirth: "'. $args['dateOfBirth'] .'"';
        if (isset($args['password']) && !empty($args['password'])) $raw_params_array[] = 'password: "'. $args['password'] .'"';
        
        // $raw_params_array[] = 'mailingList: '. (isset($args['mailingList']) ? new RawObject('"' . $args['mailingList'] . '"') : new RawObject('"N"'));
        $raw_params_array[] = 'parentId: '. (isset($args['parentId']) ? $args['parentId'] : 0);
        
        $rawParams = '{' . implode(',', $raw_params_array) . '}';

        $gql = $this->model->contact_create(['input' => new RawObject($rawParams)]);

        $userData = $this->query($gql, $type);

        if (is_object($userData))
            $userData->company = $company_response;

        return $userData;
    }

    private function register_customer($args) {
        $type = 'customerRegister';

        $raw_params_array = [];

        $raw_params_array[] = 'firstName: "'. $args['firstName'] .'"';
        $raw_params_array[] = 'middleName: "'. $args['middleName'] .'"';
        $raw_params_array[] = 'lastName: "'. $args['lastName'] .'"';
        $raw_params_array[] = 'gender: '. new RawObject(isset($args['gender']) ? $args['gender'] : 'U');
        $raw_params_array[] = 'primaryLanguage: "'. PROPELLER_LANG .'"';

        if (isset($args['company']) && !empty($args['company'])) $raw_params_array[] = 'company: "'. $args['company'] .'"';
        if (isset($args['email']) && !empty($args['email'])) $raw_params_array[] = 'email: "'. $args['email'] .'"';
        if (isset($args['homepage']) && !empty($args['homepage'])) $raw_params_array[] = 'homepage: "'. $args['homepage'] .'"';
        if (isset($args['cocNumber']) && !empty($args['cocNumber'])) $raw_params_array[] = 'cocNumber: "'. $args['cocNumber'] .'"';
        if (isset($args['phone']) && !empty($args['phone'])) $raw_params_array[] = 'phone: "'. $args['phone'] .'"';
        if (isset($args['mobile']) && !empty($args['mobile'])) $raw_params_array[] = 'mobile: "'. $args['mobile'] .'"';
        if (isset($args['dateOfBirth']) && !empty($args['dateOfBirth'])) $raw_params_array[] = 'dateOfBirth: "'. $args['dateOfBirth'] .'"';
        if (isset($args['dateOfBirth']) && !empty($args['dateOfBirth'])) $raw_params_array[] = 'dateOfBirth: "'. $args['dateOfBirth'] .'"';
        if (isset($args['password']) && !empty($args['password'])) $raw_params_array[] = 'password: "'. $args['password'] .'"';
        
        // $raw_params_array[] = 'mailingList: '. (isset($args['mailingList']) ? new RawObject('"' . $args['mailingList'] . '"') : new RawObject('"N"'));
        $raw_params_array[] = 'parentId: '. (isset($args['parentId']) ? $args['parentId'] : 0);
        
        $rawParams = '{' . implode(',', $raw_params_array) . '}';

        $gql = $this->model->customer_create(['input' => new RawObject($rawParams)]);

        return $this->query($gql, $type);
    }

    public function forgot_password($args) {
        $reset_link_type = 'passwordResetLink';

        $pass_recovery_input = '{
            email: "' . $args['user_mail'] . '" 
            redirectUrl: "' . home_url('/' . PageController::get_slug(PageType::LOGIN_PAGE) . '/') . '" 
            language: "' . PROPELLER_LANG . '"
        }';

        $reset_link_gql = $this->model->forgot_password([
                'redirectUrl' => home_url('/' . PageController::get_slug(PageType::LOGIN_PAGE) . '/'), 
                'email' => $args['user_mail'], 
                'input' => new RawObject($pass_recovery_input)
        ]);

        $reset_link_response = $this->query($reset_link_gql, $reset_link_type, true);
        
        $response = new stdClass();
        $response->postprocess = new stdClass();

        if (filter_var($reset_link_response, FILTER_VALIDATE_URL) === FALSE) {
            $response->postprocess->error = true;
            $response->postprocess->message = $reset_link_response;
        }
        else {

            $to = sprintf('to: { email: "%s" }', $args['user_mail']);
            $from = sprintf('from: { name: "%s", email: "%s" }', get_bloginfo('name'), get_bloginfo('admin_email'));
            $subject = __('Reset password?', 'propeller-ecommerce');

            ob_start();

            require $this->load_template('emails', DIRECTORY_SEPARATOR . 'propeller-reset-password-template.php');
            
            $body = ob_get_clean();
            ob_end_clean();

            $body = str_replace('"', '\"', $body);
            $body = trim(preg_replace('/\s+/', ' ', $body));
            
            $vars = '
                site: { name: "' . get_bloginfo('name') . '" }, 
                resetLink: "' . $reset_link_response . '"
            ';

            $email_controller = new EmailController();

            $reset_email_response = $email_controller->send_propeller_email($to, $from, $subject, $body, EmailEventTypes::TRANSACTIONAL, [], [], $vars);
            
            if (isset($reset_email_response->messageId))
                $response->postprocess->message = __("Your reset password email sent", "propeller-ecommerce");
            else {
                $response->postprocess->error = true;
                $response->postprocess->message = __("An error occurred sending Your reset password email", "propeller-ecommerce");
            }      
        }
            
        return $response;
    }

    private function send_registration_email($user_data) {
        $to = sprintf('to: { name: "%s", email: "%s" }', sprintf('%s %s %s', $user_data->firstName, $user_data->middleName, $user_data->lastName), $user_data->email);
        $from = sprintf('from: { name: "%s", email: "%s" }', get_bloginfo('name'), get_bloginfo('admin_email'));
        $subject = __('Welcome to', 'propeller-ecommerce') . ' ' . get_bloginfo('name');

        ob_start();

        require $this->load_template('emails', DIRECTORY_SEPARATOR . 'propeller-registration-template.php');
        
        $body = ob_get_clean();
        ob_end_clean();

        $body = str_replace('"', '\"', $body);
        $body = trim(preg_replace('/\s+/', ' ', $body));
        
        $vars = '
            site: { name: "' . get_bloginfo('name') . '" }, 
            user: { firstName: "' . $user_data->firstName . '" },
            url: { login: "' . $this->buildUrl('', PageController::get_slug(PageType::LOGIN_PAGE)) . '" }
        ';

        $email_controller = new EmailController();

        $registration_email_response = $email_controller->send_propeller_email($to, $from, $subject, $body, EmailEventTypes::TRANSACTIONAL, [], [], $vars);

        return $registration_email_response;
    }

    public function get_viewer() {
        $type = 'viewer';

        $raw_params_array = [];

        $attrs_offset = 12;

        if (defined('PROPELLER_USER_TRACK_ATTR') && !empty(PROPELLER_USER_TRACK_ATTR)) {
            $attr_track = explode(',', PROPELLER_USER_TRACK_ATTR);
            $attrs_offset = count($attr_track);

            $raw_params_array[] = 'name: ["' . implode('", "', $attr_track) . '"]';
            $raw_params_array[] = 'offset: ' . $attrs_offset;
        }

        $attribute_filter = ['filter' => new RawObject('{' . implode(',', $raw_params_array) . '}')];

        $gql = $this->model->viewer($attribute_filter);

        $userData = $this->query($gql, $type);

        if (is_object($userData))
            $this->postprocess_user($userData);
        
        return $userData;
    }

    public function get_user($userId = null) {
        if (SessionController::has(PROPELLER_USER_DATA))
            return SessionController::get(PROPELLER_USER_DATA);

        $type = 'user';
        
        $raw_params_array = [];

        if (PROPELLER_USER_TRACK_ATTR != '') 
            $raw_params_array[] = 'name: "[' . implode('", "', PROPELLER_USER_TRACK_ATTR) . '"]';

        $gql = $this->model->get_user_data(
            ['userId' => SessionController::get(PROPELLER_USER_DATA)->userId],
            ['filter' => new RawObject('{' . implode(',', $raw_params_array) . '}')]
        );

        $userData = $this->query($gql, $type);

        if (is_object($userData))
            $this->postprocess_user($userData);
        
        return $userData;
    }

    public function get_user_data($user_input = null) {
        $type = 'user';
        
        $user_param = is_numeric($user_input) ? 'userId' : 'login';

        $gql = $this->model->get_user_data([$user_param => $user_input]);

        $userData = $this->query($gql, $type);

        if (is_object($userData))
            $this->postprocess_user($userData);
        
        return $userData;
    }

    public function get_addresses() {
        $user = $this->get_user();

        $addresses = [];

        switch ($user->__typename) {
            case UserTypes::USER: 
                $addresses = $user->addresses;

                break;
            case UserTypes::CUSTOMER:
                $addresses = $user->addresses;

                break;
            case UserTypes::CONTACT: 
                $addresses = $user->company->addresses;
                
                break;
        }

        return $addresses;
    }

    public function get_default_address($address_type) {
        $addresses = $this->get_addresses();

        $found = array_filter($addresses, function($obj) use ($address_type) { 
            return $obj->isDefault == 'Y' && $obj->type == $address_type; 
        });

        if (count($found))
            return current($found);

        return null;
    }

    protected function process_attributes($attributes) {
        if (defined('PROPELLER_USER_TRACK_ATTR') && !empty(PROPELLER_USER_TRACK_ATTR)) {
            $track_attrs = explode(',', PROPELLER_USER_TRACK_ATTR);

            foreach ($track_attrs as $track_attr) {
                $found = array_filter($attributes->items, function($obj) use($track_attr) { 
                    return $obj->attributeDescription->name == $track_attr; 
                });

                if (count($found)) {
                    $attribute = new ObjectAttribute(current($found));

                    SessionController::set($track_attr, $attribute->get_value());

                    continue;
                }
            }
        }
    }

    public function user_prices($user_specific_prices) {
        SessionController::set(PROPELLER_SPECIFIC_PRICES, $user_specific_prices == 1 ? true : false);

        $response = new stdClass();
        $response->success = true;
        $response->reload = true;

        return $response;
    }

    protected function postprocess_user($user) {
        SessionController::set(PROPELLER_USER_DATA, $user);
    }

    protected function postprocess_sesion($session) {
        SessionController::start();

        SessionController::set(PROPELLER_SESSION, $session->session);
        
        SessionController::set(PROPELLER_ACCESS_TOKEN, $session->session->accessToken);
        SessionController::set(PROPELLER_REFRESH_TOKEN, $session->session->refreshToken);
        SessionController::set(PROPELLER_EXPIRATION_TIME, $session->session->expirationTime);
    }

    protected function postprocess_refresh_token($refresh) {
        SessionController::get(PROPELLER_USER_DATA);

        SessionController::set(PROPELLER_ACCESS_TOKEN, $refresh->access_token);
        SessionController::set(PROPELLER_REFRESH_TOKEN, $refresh->refresh_token);

        // update the expiration datetime
        $now_date = new DateTime('NOW');
        $now_date->add(new DateInterval('PT' . $refresh->expires_in . 'S'));
        SessionController::set(PROPELLER_EXPIRATION_TIME, $now_date->format('c'));
    }
}