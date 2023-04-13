<?php 

namespace Propeller\Includes\Controller;

use Propeller\Includes\Enum\AddressType;
use Propeller\Includes\Enum\PageType;
use Propeller\Propeller;
use stdClass;

class ShoppingCartAjaxController extends BaseAjaxController {
    protected $shoppingCart;

    public function __construct() {
        parent::__construct();

        $this->shoppingCart = new ShoppingCartController();
    }

    public function cart_add_item() {
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);

            $price = apply_filters('propel_shopping_cart_get_item_price', $data['product_id'], (int) $data['quantity']);

            $response = $this->shoppingCart->add_item(
	            $data['quantity'],
	            $data['product_id'],
                (isset($data['notes']) ? $data['notes'] : ''),
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);

            $response = $this->shoppingCart->add_item_bundle(
	            $data['quantity'],
	            $data['bundle_id'],
                (isset($data['notes']) ? $data['notes'] : '')
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);

            $price = apply_filters('propel_shopping_cart_get_item_price', $data['item_id'], (int) $data['quantity']);

            $response = $this->shoppingCart->update_item(
	            $data['quantity'],
                (isset($data['notes']) ? $data['notes'] : ''),
	            $data['item_id'],
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);

            $response = $this->shoppingCart->delete_item(
	            $data['item_id']
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);

            preg_match('/[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}/', $data['actionCode'], $result);
    
            $response = sizeof($result) > 0 
                ? $this->shoppingCart->voucher_code($data['actionCode'])
                : $this->shoppingCart->action_code($data['actionCode']);
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_POST);

            preg_match('/[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}/', $data['actionCode'], $result);

            $response = sizeof($result) > 0 
                ? $this->shoppingCart->remove_voucher_code($data['actionCode'])
                : $this->shoppingCart->remove_action_code($data['actionCode']);
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);
            
            $addr_response = null;

            $response = $this->shoppingCart->update_address($data);
    
            if ($data['type'] == AddressType::INVOICE) {
                if (isset($data['save_invoice_address'])) {
                    $address = new AddressController();
                    $address->update_address($data, null, false);
                }
            }
    
            if (isset($data['update_delivery_address'])) {
                $address = new AddressController();
                $addr_response = $address->update_address($data, null, false);
            }
    
            if (isset($data['add_delivery_address'])) {
                $address = new AddressController();
                $addr_response = $address->add_address($data, null, false);
            }
    
            if (isset($addr_response->id))
                SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $addr_response->id);
    
            if (isset($data['subaction']) && $data['subaction'] == 'cart_update_delivery_address')
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_REQUEST);
        
            $response = $this->shoppingCart->replenish(explode(',', $data['items']));

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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);

            $response = $this->shoppingCart->change_order_type($data['order_type']);
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);

            // $response = $this->shoppingCart->update_address($data);
            
            $postprocess = new stdClass();
            $postprocess->success = true;
            $postprocess->message = __("Proceeding to next step", 'propeller-ecommerce');
            $postprocess->redirect = esc_url_raw(home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $data['next_step'] . '/'));
            
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);
            
            $addressController = new AddressController();
            
            if (isset($data['add_delivery_address']) && $data['add_delivery_address'] == 'Y') {
                $response = $this->shoppingCart->update_address($data);

                if (isset($data['save_delivery_address'])) {
                    $address = new AddressController();
                    $add_response = $address->add_address($data);

                    if (isset($add_response->id))
                        SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $add_response->id);
                }
            }
            else if (isset($data['delivery_address'])) {
                $delivery_addresses = $addressController->get_addresses(['type' => AddressType::DELIVERY]);
                $selected_address_id = (int) $data['delivery_address'];

                $found = array_filter($delivery_addresses, function($obj) use($selected_address_id) { return $obj->id == $selected_address_id; });
                    
                if (is_array($found) && count($found)) {
                    $response = $this->shoppingCart->update_address((array) current($found));

                    SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, $selected_address_id);
                }
            }
            
            // $postage = $this->shoppingCart->get_postage_data();
            // //$postage->requestDate = $data['delivery_select'];

            // $cartupdate = $this->shoppingCart->update(
            //     $this->shoppingCart->get_payment_data(), 
            //     $postage, 
            //     $this->shoppingCart->get_notes(), 
            //     $this->shoppingCart->get_reference(), 
            //     $this->shoppingCart->get_extra3(), 
            //     $this->shoppingCart->get_extra4(), 
            //     $data['carrier']
            // );

            // if (!is_object($cartupdate)) {
            //     $response->message = $cartupdate;
            // }
            // else {
            //     $postprocess = new stdClass();
            //     $postprocess->success = true;
            //     $postprocess->message = __("Address updated", 'propeller-ecommerce');
            //     $postprocess->redirect = home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $data['next_step'] . '/');
            // }

            $postprocess = new stdClass();
            $postprocess->success = true;
            $postprocess->message = __("Address updated", 'propeller-ecommerce');
            $postprocess->redirect = esc_url_raw(home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $data['next_step'] . '/'));
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);
            
            $payment = $this->shoppingCart->get_payment_data();
            $payment->method = $data['payMethod'];

            $postageData = $this->shoppingCart->get_postage_data();
            $postageData->partialDeliveryAllowed = $data['partialDeliveryAllowed'];
            $postageData->requestDate = $data['delivery_select'];

            $carrier = $data['carrier'];

            $cartupdate = $this->shoppingCart->update(
                $payment, 
                $postageData,
                $this->shoppingCart->get_notes(), 
                $this->shoppingCart->get_reference(), 
                $this->shoppingCart->get_extra3(), 
                $this->shoppingCart->get_extra4(),
                $carrier
                //$this->shoppingCart->get_carrier() 
            );

            if (!is_object($cartupdate)) {
                $response->message = $cartupdate;
            }
            else {
                $product = new ProductController();
                
                $postprocess->success = true;
                $postprocess->message = __("Completed", 'propeller-ecommerce');
                $postprocess->redirect = esc_url_raw($product->buildUrl('', 'checkout-summary'));
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
        $this->init_ajax();

        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
            $data = $this->sanitize($_REQUEST);

            $response = $this->shoppingCart->process($data);
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
        $this->init_ajax();
        
        $postprocess = new stdClass();
        $response = new stdClass();
        
        if ($this->validate_form_request('nonce')) {
	        $data = $this->sanitize($_POST);

            $response = new stdClass();
            
            $this->shoppingCart->set_order_status($data);
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