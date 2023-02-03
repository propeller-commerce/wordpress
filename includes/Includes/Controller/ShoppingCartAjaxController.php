<?php 

namespace Propeller\Includes\Controller;

use Propeller\Includes\Enum\AddressType;
use Propeller\Includes\Enum\PageType;
use Propeller\Propeller;
use stdClass;

class ShoppingCartAjaxController extends BaseAjaxController {
    protected $shoppingCart;

    public function __construct() {
        $this->shoppingCart = new ShoppingCartController();
    }

    public function cart_add_item() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $prop = new Propeller();
            $prop->reinit_filters();

            $price = apply_filters('propel_shopping_cart_get_item_price', $_POST['product_id'], (int) $_POST['quantity']);

            $response = $this->shoppingCart->add_item(
                $_POST['quantity'],
                $_POST['product_id'],
                (isset($_POST['notes']) ? $_POST['notes'] : ''),
                $price
            );

            $response->modal = "add-to-basket-modal";
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
            
            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_add_bundle() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $response = $this->shoppingCart->add_item_bundle(
                $_POST['quantity'],
                $_POST['bundle_id'],
                (isset($_POST['notes']) ? $_POST['notes'] : '')
            );
    
            $response->modal = "add-to-basket-modal";
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_update_item() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $prop = new Propeller();
            $prop->reinit_filters();

            $price = apply_filters('propel_shopping_cart_get_item_price', $_POST['item_id'], (int) $_POST['quantity']);

            $response = $this->shoppingCart->update_item(
                $_POST['quantity'],
                (isset($_POST['notes']) ? $_POST['notes'] : ''),
                $_POST['item_id'],
                $price
            );
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_delete_item() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $prop = new Propeller();
            $prop->reinit_filters();

            $response = $this->shoppingCart->delete_item(
                $_POST['item_id']
            );
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_add_action_code() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $prop = new Propeller();
            $prop->reinit_filters();
    
            preg_match('/[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}/', $_POST['actionCode'], $result);
    
            $response = sizeof($result) > 0 
                ? $this->shoppingCart->voucher_code($_POST['actionCode'])
                : $this->shoppingCart->action_code($_POST['actionCode']);
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }
        
        die(json_encode($response));
    }

    public function cart_remove_action_code() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $prop = new Propeller();
            $prop->reinit_filters();

            $_POST = $this->sanitize($_POST);

            preg_match('/[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}/', $_POST['actionCode'], $result);

            $response = sizeof($result) > 0 
                ? $this->shoppingCart->remove_voucher_code($_POST['actionCode'])
                : $this->shoppingCart->remove_action_code($_POST['actionCode']);
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }
        
        die(json_encode($response));
    }

    public function cart_update() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $prop = new Propeller();
            $prop->reinit_filters();
    
            $response = $this->shoppingCart->update(
                $this->shoppingCart->get_payment_data(), 
                $this->shoppingCart->get_postage_data(), 
                $this->shoppingCart->get_notes(), 
                $this->shoppingCart->get_reference(), 
                $this->shoppingCart->get_extra3(), 
                $this->shoppingCart->get_extra4(), 
                $this->shoppingCart->get_carriers());
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        $response->object = 'Cart';

        die(json_encode($response));
    }

    public function cart_update_address() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);
            
            $addr_response = null;

            $response = $this->shoppingCart->update_address($_POST);
    
            if ($_POST['type'] == AddressType::INVOICE) {
                if (isset($_POST['save_invoice_address'])) {
                    $address = new AddressController();
                    $address->update_address($_POST, null, false);
                }
            }
    
            if (isset($_POST['update_delivery_address'])) {
                $address = new AddressController();
                $addr_response = $address->update_address($_POST, null, false);
            }
    
            if (isset($_POST['add_delivery_address'])) {
                $address = new AddressController();
                $addr_response = $address->add_address($_POST, null, false);
            }
    
            if (isset($addr_response->id))
                SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $addr_response->id);
    
            if (isset($_POST['subaction']) && $_POST['subaction'] == 'cart_update_delivery_address')
                SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, true);
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function do_replenish() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_REQUEST = $this->sanitize($_REQUEST);
        
            $response = $this->shoppingCart->replenish(explode(',', $_REQUEST['items']));

            $response->modal = "add-to-basket-modal";
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_change_order_type() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $response = $this->shoppingCart->change_order_type($_POST['order_type']);
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }

    public function cart_step_1() { 
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            // $response = $this->shoppingCart->update_address($_POST);
            
            $postprocess = new stdClass();
            $postprocess->success = true;
            $postprocess->message = __("Proceeding to next step", 'propeller-ecommerce');
            $postprocess->redirect = home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $_POST['next_step'] . '/');
            
            $response->object = 'Cart';
        
            $response->postprocess = $postprocess;
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        die(json_encode($response));
    }
    
    public function cart_step_2() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);
            
            $addressController = new AddressController();
            
            if (isset($_POST['add_delivery_address']) && $_POST['add_delivery_address'] == 'Y') {
                $response = $this->shoppingCart->update_address($_POST);

                if (isset($_POST['save_delivery_address'])) {
                    $address = new AddressController();
                    $add_response = $address->add_address($_POST);

                    if (isset($add_response->id))
                        SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $add_response->id);
                }
            }
            else if (isset($_POST['delivery_address'])) {
                $delivery_addresses = $addressController->get_addresses(['type' => AddressType::DELIVERY]);
                $selected_address_id = (int) $_POST['delivery_address'];

                $found = array_filter($delivery_addresses, function($obj) use($selected_address_id) { return $obj->id == $selected_address_id; });
                    
                if (count($found)) {
                    $response = $this->shoppingCart->update_address((array) current($found));

                    SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $selected_address_id);
                }
            }
            
            // $postage = $this->shoppingCart->get_postage_data();
            // //$postage->requestDate = $_POST['delivery_select'];

            // $cartupdate = $this->shoppingCart->update(
            //     $this->shoppingCart->get_payment_data(), 
            //     $postage, 
            //     $this->shoppingCart->get_notes(), 
            //     $this->shoppingCart->get_reference(), 
            //     $this->shoppingCart->get_extra3(), 
            //     $this->shoppingCart->get_extra4(), 
            //     $_POST['carrier'] 
            // );

            // if (!is_object($cartupdate)) {
            //     $response->message = $cartupdate;
            // }
            // else {
            //     $postprocess = new stdClass();
            //     $postprocess->success = true;
            //     $postprocess->message = __("Address updated", 'propeller-ecommerce');
            //     $postprocess->redirect = home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $_POST['next_step'] . '/');
            // }

            $postprocess = new stdClass();
            $postprocess->success = true;
            $postprocess->message = __("Address updated", 'propeller-ecommerce');
            $postprocess->redirect = home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $_POST['next_step'] . '/');
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
        }
        
        $response->object = 'Cart';
    
        $response->postprocess = $postprocess;

        die(json_encode($response));
    }

    public function cart_step_3() { 
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);
            
            $payment = $this->shoppingCart->get_payment_data();
            $payment->method = $_POST['payMethod'];

            $cartupdate = $this->shoppingCart->update(
                $payment, 
                $this->shoppingCart->get_postage_data(), 
                $this->shoppingCart->get_notes(), 
                $this->shoppingCart->get_reference(), 
                $this->shoppingCart->get_extra3(), 
                $this->shoppingCart->get_extra4(), 
                $this->shoppingCart->get_carrier() 
            );

            if (!is_object($cartupdate)) {
                $response->message = $cartupdate;
            }
            else {
                $product = new ProductController();
                
                $postprocess->success = true;
                $postprocess->message = __("Completed", 'propeller-ecommerce');
                $postprocess->redirect = $product->buildUrl('', 'checkout-summary');
            }
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;
        }

        $response->object = 'Cart';

        $response->postprocess = $postprocess;
        
        die(json_encode($response));
    }

    public function cart_process() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_REQUEST = $this->sanitize($_REQUEST);

            $response = $this->shoppingCart->process($_REQUEST);
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }

        $response->object = 'Checkout';

        die(json_encode($response));
    }

    public function cart_change_order_status() {
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $_POST = $this->sanitize($_POST);

            $response = new stdClass();
            
            $this->shoppingCart->set_order_status($_POST);
            $response->success = true;
        }
        else {
            $postprocess->message       = __("Security check failed", "propeller-ecommerce");
            $postprocess->error = true;
            $postprocess->success = false;

            $response->postprocess = $postprocess;
        }
        
        die(json_encode($response));
    }
}