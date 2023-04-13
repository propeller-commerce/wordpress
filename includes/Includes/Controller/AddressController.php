<?php

namespace Propeller\Includes\Controller;

use Exception;
use GraphQL\RawObject;
use Propeller\Includes\Enum\AddressType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Enum\UserTypes;
use stdClass;

class AddressController extends BaseController {
    protected $type = 'address';

    protected $user;
    protected $user_type;
    protected $is_registration;
    protected $model;
    
    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('address');

        $this->is_registration = false;

        if (UserController::is_logged_in()) {
            $this->user = SessionController::get(PROPELLER_USER_DATA);

            $this->user_type = $this->user->__typename;
        }
    }

    /*
        Addresses filters
    */
    public function address_box($address, $obj, $title, $show_title = false, $show_modify = false, $show_delete = false, $show_set_default = false) {

		$this->assets()->std_requires_asset('propeller-address-default');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-box.php');
    }

    public function address_add($type, $title, $obj) {
        $address = $this->get_address_obj($type);

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-add.php');
    }

    public function address_form($address) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-form.php');
    }

    public function address_modify($address) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-modify.php');
    }

    public function address_delete($address) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-delete.php');
    }

    public function address_delete_popup($address) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-delete-popup.php');
    }

    public function address_set_default($address) {

		$this->assets()->std_requires_asset('propeller-address-default');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-set-default.php');
    }

    public function address_popup($address, $type) {
        if (!isset($address) || !$address || !is_object($address))
            $address = $this->get_address_obj($type);

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-address-popup.php');
    }

    

    

    


    public function set_user_data($user_data) {
        $this->user = $user_data;
    }

    public function set_user_type($user_type) {
        $this->user_type = $user_type;
    }

    public function set_is_registration($is_registration) {
        $this->is_registration = $is_registration;
    }

    public function addresses() {
        if (!UserController::is_logged_in()) {
            wp_redirect('/' . PageController::get_slug(PageType::LOGIN_PAGE));
            exit;
        }

        ob_start();

		$args = [
			'type' => isset($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : AddressType::DELIVERY,
		];

        $this->addresses = $this->get_addresses($args);

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-addresses.php');
        
        return ob_get_clean();
    }

    public function get_addresses($args = []) {
        $type = 'addressesByCompanyId';
        $param_name = 'companyId';
        $param_value = 0;

        switch ($this->user_type) {
            case UserTypes::CUSTOMER:
                $type = 'addressesByCustomerId';
                $param_name = 'customerId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CONTACT:
                $type = 'addressesByCompanyId';
                $param_name = 'companyId';
                $param_value = $this->user->company->companyId;

                break;
        }

        $params = [];
        $params[$param_name] = $param_value;

        if (isset($args['type'])) 
            $params['type'] = new RawObject(isset($args['type']) ? sanitize_text_field($args['type']) : AddressType::DELIVERY);

        $gql = $this->model->get_addresses($type, $params);
            
        $addressesData = $this->query($gql, $type);

        return $addressesData;
    }

    public function add_address($args, $userId = null) {
        $type = 'companyAddressCreate';
        $param_name = 'companyId';
        $param_value = 0;

        switch ($this->user_type) {
            case UserTypes::USER:
                $type = 'userAddressCreate';
                $param_name = 'userId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CUSTOMER:
                $type = 'customerAddressCreate';
                $param_name = 'customerId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CONTACT:
                $type = 'companyAddressCreate';
                $param_name = 'companyId';
                $param_value = $this->user->company->companyId;

                break;
        }

        if ($userId)
            $param_value = $userId;

        if (isset($args['save_delivery_address'])) {
            $args['type'] = AddressType::DELIVERY;
            // $args['isDefault'] = 'Y';
        }
        
        $params = $this->format_params($args);
        $params[] = $param_name . ': '. $param_value;

        $rawParams = '{' . implode(',', $params) . '}';

        $gql = $this->model->add_address($type, ['input' => new RawObject($rawParams)]);
        
        $addressesData = $this->query($gql, $type);

        if (isset($addressesData->id))
            $this->update_user_addresses();

        return $addressesData;
    }

    public function update_address($args, $userId = null) {
        $type = 'companyAddressUpdate';
        $param_name = 'companyId';
        $param_value = 0;

        switch ($this->user_type) {
            case UserTypes::USER:
                $type = 'userAddressUpdate';
                $param_name = 'userId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CUSTOMER:
                $type = 'customerAddressUpdate';
                $param_name = 'customerId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CONTACT:
                $type = 'companyAddressUpdate';
                $param_name = 'companyId';
                $param_value = $this->user->company->companyId;

                break;
        }

        if ($userId)
            $param_value = $userId;

        if (isset($args['save_delivery_address'])) {
            $args['type'] = AddressType::DELIVERY;
            // $args['isDefault'] = 'Y';
        }
            
        $params = $this->format_params($args);
        $params[] = $param_name . ': '. $param_value;

        $rawParams = '{' . implode(', ', $params) . '}';

        $gql = $this->model->update_address($type, ['input' => new RawObject($rawParams)]);
         
        $addressesData = $this->query($gql, $type);

        if (isset($addressesData->id))
            $this->update_user_addresses();

        return $addressesData;
    }

    public function delete_address($args) {
        $type = 'companyAddressDelete';
        $param_name = 'companyId';
        $param_value = 0;

        switch ($this->user_type) {
            case UserTypes::USER:
                $type = 'userAddressDelete';
                $param_name = 'userId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CUSTOMER:
                $type = 'customerAddressDelete';
                $param_name = 'customerId';
                $param_value = $this->user->userId;

                break;
            case UserTypes::CONTACT:
                $type = 'companyAddressDelete';
                $param_name = 'companyId';
                $param_value = $this->user->company->companyId;

                break;
        }


        $params = [];
        $params[] = $param_name . ': '. $param_value;
        $params[] = 'id: '. (int) $args['id'];

        $rawParams = '{' . implode(',', $params) . '}';

        $gql = $this->model->delete_address($type, ['input' => new RawObject($rawParams)]);
        
        $addressesData = $this->query($gql, $type);

        if (isset($addressesData->id))
            $this->update_user_addresses();

        return $addressesData;
    }

    public function get_address_obj($type) {
        $address = new stdClass();

        $address->city = '';
        $address->code = '';
        $address->company = '';
        $address->country = '';
        $address->email = '';
        $address->firstName = '';
        $address->id = 0;
        $address->lastName = '';
        $address->middleName = '';
        $address->gender = '';
        $address->notes = '';
        $address->number = '';
        $address->numberExtension = '';
        $address->postalCode = '';
        $address->region = '';
        $address->street = '';
        $address->phone = '';
        $address->icp = '';
        $address->type = $type;

        return $address;
    }

    public function get_default_address($address_type) {
        if (!is_array($address_type))
            $address_type = ['type' => $address_type];

        $addresses = $this->get_addresses($address_type);

        $found = array_filter($addresses, function($obj) use ($address_type) { 
            return $obj->isDefault == 'Y' && $obj->type == $address_type['type']; 
        });

        if (count($found))
            return current($found);

        return null;
    }

    private function format_params($args) {
        $params = [];

        $args['code'] = '';

        if (isset($args['city']) && !empty($args['city'])) $params[] = 'city: "' . $args['city'] . '"';
        if (isset($args['code']) && !empty($args['code'])) $params[] = 'code: "' . $args['code'] . '"';
        if (isset($args['company']) && !empty($args['company'])) $params[] = 'company: "' . $args['company'] . '"';
        if (isset($args['country']) && !empty($args['country'])) $params[] = 'country: "' . $args['country'] . '"';
        if (isset($args['email']) && !empty($args['email'])) $params[] = 'email: "' . $args['email'] . '"';
        if (isset($args['firstName']) && !empty($args['firstName'])) $params[] = 'firstName: "' . $args['firstName'] . '"';
        if (isset($args['lastName']) && !empty($args['lastName'])) $params[] = 'lastName: "' . $args['lastName'] . '"';
        if (isset($args['middleName']) && !empty($args['middleName'])) $params[] = 'middleName: "' . $args['middleName'] . '"';
        if (isset($args['gender']) && !empty($args['gender'])) $params[] = 'gender: ' . new RawObject($args['gender']);
          
        if (isset($args['isDefault']) && !empty($args['isDefault'])) $params[] = 'isDefault: ' . new RawObject($args['isDefault']);
        if (isset($args['notes']) && !empty($args['notes'])) $params[] = 'notes: "' . $args['notes'] . '"';
        if (isset($args['number']) && !empty($args['number'])) $params[] = 'number: "' . $args['number'] . '"';
        if (isset($args['numberExtension']) && !empty($args['numberExtension'])) $params[] = 'numberExtension: "' . $args['numberExtension'] . '"';
        if (isset($args['postalCode']) && !empty($args['postalCode'])) $params[] = 'postalCode: "' . $args['postalCode'] . '"';
        if (isset($args['region']) && !empty($args['region'])) $params[] = 'region: "' . $args['region'] . '"';
        if (isset($args['street']) && !empty($args['street'])) $params[] = 'street: "' . $args['street'] . '"';
        if (isset($args['phone']) && !empty($args['phone'])) $params[] = 'phone: "' . $args['phone'] . '"';

        // if ($this->user_type != UserTypes::CUSTOMER) {
        //     if (isset($args['icp']) && !empty($args['icp'])) $params[] = 'icp: ' . new RawObject($args['icp']);
        //     else $params[] = 'icp: ' . new RawObject("N");
        // }
        
        if (!isset($args['id']) || !is_numeric($args['id']) || (int) $args['id'] == 0)
            $params[] = 'type: ' . new RawObject(isset($args['type']) ? sanitize_text_field($args['type']) : AddressType::DELIVERY);
        else 
            $params[] = 'id: ' . (isset($args['id']) ? (int) $args['id'] : 0);

        return $params;
    }

    private function update_user_addresses() {
        if ($this->is_registration)
            return;
            
        $invoice_addresses = $this->get_addresses(['type' => AddressType::INVOICE]);
        $delivery_addresses = $this->get_addresses(['type' => AddressType::DELIVERY]);
    
        switch ($this->user_type) {
            case UserTypes::USER:
                $this->user->addresses = array_merge($invoice_addresses, $delivery_addresses);

                break;
            case UserTypes::CUSTOMER:
                $this->user->addresses = array_merge($invoice_addresses, $delivery_addresses);

                break;
            case UserTypes::CONTACT:
                $this->user->company->addresses = array_merge($invoice_addresses, $delivery_addresses);

                break;
        }

        SessionController::set(PROPELLER_USER_DATA, $this->user);

        try {
            $shoppingCart = new ShoppingCartController();

            $shoppingCart->set_user_default_cart_address();
        }
        catch (Exception $ex) {}
    }
}