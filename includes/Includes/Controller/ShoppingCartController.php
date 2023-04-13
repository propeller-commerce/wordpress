<?php

namespace Propeller\Includes\Controller;

use Exception;
use GraphQL\RawObject;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Propeller\Includes\Enum\AddressType;
use Propeller\PropellerHelper;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\CrossupsellTypes;
use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\MediaType;
use Propeller\Includes\Enum\OrderStatus;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Enum\PaymentStatuses;
use Propeller\Includes\Enum\UserTypes;
use Propeller\Includes\Object\Product;
use Propeller\Includes\Query\Media;
use Propeller\Propeller;
use stdClass;

class ShoppingCartController extends BaseController {
    protected $type = 'cart';
    protected $cart_id;
    protected $cart;
    protected $items;
    protected $response;
    protected $object_name = 'Checkout';

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('shoppingCart');
    }

    public function init_cart() {
        (!SessionController::get(PROPELLER_CART_INITIALIZED) || !SessionController::has(PROPELLER_CART)) 
            ? $this->start()
            : $this->init_postprocess(SessionController::get(PROPELLER_CART));
    }

    /**
     * 
     * Shopping cart filters
     * 
     */
    public function shopping_cart_title($cart) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-title.php');
    }

    public function shopping_cart_info($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-info.php');
    }

    public function shopping_cart_table_header($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-table-header.php');
    }

    public function shopping_cart_table_items($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-table-items.php');
    }

    public function shopping_cart_table_product_item($item, $cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-product-item.php');
    }

    public function shopping_cart_table_bundle_item($item, $cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-bundle-item.php');
    }

    public function shopping_cart_bonus_items($cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-bonus-items.php');
    }

    public function shopping_cart_bonus_items_title($cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-bonus-items-title.php');
    }

    public function shopping_cart_bonus_item($bonusItem, $cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-bonus-item.php');
    }

    public function shopping_cart_action_code($cart, $obj) {
        require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-action-code.php');
    }

    public function shopping_cart_voucher($cart, $obj) {

		$this->assets()->std_requires_asset('propeller-action-tooltip');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-voucher.php');
    }
    
    public function shopping_cart_order_type($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-order-type.php');
    }

    public function shopping_cart_totals($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-totals.php');
    }

    public function shopping_cart_totals_with_items($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-totals-with-items.php');
    }

    public function shopping_cart_buttons($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-buttons.php');
    }

    public function shopping_cart_invoice_address_form($invoice_address, $cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-cart-invoice-address-form.php');
    }

    public function shopping_cart_delivery_address_form($delivery_address, $cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
        
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-cart-delivery-address-form.php');
    }

    /*
        Checkout reusable filters  
    */
    public function checkout_step_1_info($cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $invoice_address = $obj->get_invoice_address();

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-step-1-info.php');
    }

    public function checkout_step_2_info($cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $delivery_address = $obj->get_delivery_address();

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-step-2-info.php');
    }

    public function checkout_step_3_info($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-step-3-info.php');
    }

    public function checkout_invoice_details($cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $address_controller = new AddressController();

        $invoice_address = $address_controller->get_default_address(['type' => AddressType::INVOICE]);

        add_action('wp_footer', function() use ($invoice_address, $cart, $obj) {
            apply_filters('propel_shopping_cart_invoice_address_form', $invoice_address, $cart, $obj);
        });

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-invoice-details.php');
    }

    public function checkout_delivery_addresses($cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $address_controller = new AddressController();
        $delivery_addresses = $address_controller->get_addresses(['type' => AddressType::DELIVERY]);

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-delivery-addresses.php');
    }

    public function checkout_delivery_address($delivery_address, $cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $checked = '';
        $checked_label = '';

        if (SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) && SessionController::get(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) == $delivery_address->id) {
            $checked = 'checked="checked"';
            $checked_label = 'selected';
        }
        else if ($delivery_address->isDefault == 'Y' && !SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED)) {
            $checked = 'checked="checked"';
            $checked_label = 'selected';
        }

        add_action('wp_footer', function() use ($delivery_address, $cart, $obj) {
            apply_filters('propel_shopping_cart_delivery_address_form', $delivery_address, $cart, $obj);
        });

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-delivery-address.php');
    }

    public function checkout_delivery_address_new($cart, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
        
        $address_controller = new AddressController();
        $delivery_address = $address_controller->get_address_obj(AddressType::DELIVERY);

        add_action('wp_footer', function() use ($delivery_address, $cart, $obj) {
            apply_filters('propel_shopping_cart_delivery_address_form', $delivery_address, $cart, $obj);
        });

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-delivery-address-new.php');
    }

    public function checkout_shipping_method($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-shipping-method.php');
    }

    public function checkout_paymethods($pay_methods, $cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-paymethods.php');
    }

    public function checkout_paymethod($payMethod, $cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-paymethod.php');
    }

    /*
        /Checkout reusable filters 
    */

    /*
        Checkout - regular 
    */
    
    public function checkout_regular_page_title($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-page-title.php');
    }

    public function checkout_regular_step_1_titles($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-1-titles.php');
    }

    public function checkout_regular_step_1_submit($cart, $obj, $slug) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-1-submit.php');
    }

    public function checkout_regular_step_1_other_steps($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-1-other-steps.php');
    }
    
    public function checkout_regular_step_2_titles($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-2-titles.php');
    }

    public function checkout_regular_step_2_form($cart, $obj, $slug) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        $address_controller = new AddressController();
        $delivery_addresses = $address_controller->get_addresses(['type' => AddressType::DELIVERY]);

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-2-form.php');
    }

    public function checkout_regular_step_2_submit($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-2-submit.php');
    }
    
    public function checkout_regular_step_2_other_steps($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-2-other-steps.php');
    }

    public function checkout_regular_step_3_titles($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-3-titles.php');
    }

    public function checkout_regular_step_3_form($cart, $obj, $slug) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-3-form.php');
    }

    public function checkout_regular_step_3_submit($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'regular' . DIRECTORY_SEPARATOR . 'propeller-checkout-regular-step-3-submit.php');
    }

    /*
        Checkout summary
    */
    public function checkout_summary_form($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-summary-form.php');
    }

    public function checkout_summary_submit($cart, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-summary-submit.php');
    }

    /**
     * Used for external price calculation in case custom/external prices are
     * being used. Call this filter in the ShoppingCartAjaxController
     */
    public function shopping_cart_get_item_price($product_identifier, $quantity) {
        // by default return null since we're using Propeller prices
        return null;
    }
    
    /**
     * 
     * FRONTEND HOOKS
     * 
     */
    public function mini_shopping_cart() {
        $this->init_cart();

        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-mini-shopping-cart.php');
        return ob_get_clean();
    }

    public function mini_checkout_cart() {
        $this->init_cart();

        ob_start();
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-mini-checkout-cart.php');
        return ob_get_clean();
    }

    public function checkout() {
        if (!UserController::is_logged_in()) {
            wp_redirect('/' . PageController::get_slug(PageType::LOGIN_PAGE));
            die;
        }

        $this->init_cart();

        // empty cart, redirect to index?
        if ($this->get_items_count() == 0) {
            wp_redirect('/' . PageController::get_slug(PageType::SHOPPING_CART_PAGE));
            die;
        }

        ob_start();

        $slug = isset($applied_filters['slug']) ? $applied_filters['slug'] : get_query_var('slug');
        if (empty($slug))
            $slug = '1';

        $this->cart = SessionController::get(PROPELLER_CART);

        $order_type = SessionController::get(PROPELLER_ORDER_TYPE);
        if (!$order_type || empty($order_type))
            $order_type = 'regular';

        $checkout_page = $this->partials_dir . DIRECTORY_SEPARATOR . "checkout" . DIRECTORY_SEPARATOR . "$order_type" . DIRECTORY_SEPARATOR . "propeller-checkout-step-$slug.php";
        
        if (file_exists($checkout_page)) {
            require $checkout_page;

            return ob_get_clean();
        }
    }
    
    public function checkout_summary() {
        $this->init_cart();

        ob_start();

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-summary.php');
        return ob_get_clean();
    }

	public function quick_add_to_basket() {

		$this->init_cart();

		$error    = null;
		$products = [];

		$subtotal = 0;
		$exclbtw  = 0;
		$total    = 0;

		if($this->is_post_request()) {
			if ( ! $this->validate_form_request( '_wpnonce', PROPELLER_NONCE_KEY_FRONTEND ) ) {
				$error = __( 'Permission denied!', 'propeller-ecommerce' );
			} else {

				$values = $this->parse_excel();

                if ( ! is_array( $values ) || count($values) === 0 ) {
					$error = __( 'Error uploading Excel file', 'propeller-ecommerce' );
				} else {

					$productController = new ProductController();

					$skus = [];
					foreach ( $values as $key => $val ) {
						$skus[] = "\"" . $val['code'] . "\"";
					}

					$products_search = $productController->get_products( [ 'sku' => implode(',', $skus) ], true );

					if(isset($products_search->items)) {
						foreach ( $products_search->items as $item ) {
							$net_price = $this->get_quick_item_price( $item->price );
							$quantity  = $this->get_quick_item_quantity( $values, $item->sku );

							$quick_item            = new stdClass();
							$quick_item->code      = $item->sku;
							$quick_item->id        = $item->classId;
							$quick_item->name      = $item->name[0]->value;
							$quick_item->net_price = $net_price;
							$quick_item->quantity  = $quantity;
							$quick_item->total     = $net_price * $quantity;

							$products[] = $quick_item;

							$total += $quick_item->total;
						}
					}

					$exclbtw  = PropellerHelper::percentage( 21, $total );
					$subtotal = $total - $exclbtw;

					$products = array_reverse( $products );
				}
			}
		}

		ob_start();

		require $this->load_template( 'templates', DIRECTORY_SEPARATOR . 'propeller-quick-add-to-basket.php' );

		return ob_get_clean();
	}

    private function get_quick_item_price($price) {
        if ($price->discount)
            return $price->discount->value;
        
        return $price->gross;
    }

    private function get_quick_item_quantity($values, $sku) {
        foreach ($values as $vals) {
            if ($vals['code'] == $sku)
                return $vals['quantity'];
        }

        return 0;
    }

    private function parse_excel() {
        $values = [];

		$action = !empty($_POST['action']) ? sanitize_text_field($_POST['action']) : null;

        if ($action === 'upload_excel_file' && !empty($_FILES['attachment']['tmp_name'])) {
            $file = wp_upload_bits($_FILES['attachment']['name'], null, file_get_contents($_FILES['attachment']['tmp_name']));

            if ($file['error'] != false) {
                return [];
            }
            
            $spreadsheet = IOFactory::load($file['file']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(false, true, true, true);

            if (is_array($sheetData)) {
                $index = 0;
                foreach ($sheetData as $sheet) {
                    if ($index >= 2) {
                        $ar = array_replace($sheet, array_fill_keys(array_keys($sheet, null), ''));
                        
                        $temp = array_keys(array_count_values($ar));

                        $values[] = [
                            'code' => $temp[0],
                            'quantity' => $temp[1]
                        ];
                    }

                    $index++;
                }
            }
        }

        return $values;
    }

    public function checkout_thank_you() {
        ob_start();

        $orderController = new OrderController();

        $order = isset($propel['order']) 
            ? $propel['order'] 
            : $orderController->get_order((int) $_GET['order_id']);
        
        
        if(isset($order) && is_object($order)) {
            if ($order->paymentData->status == PaymentStatuses::FAILED || 
                $order->paymentData->status == PaymentStatuses::CANCELLED ||
                $order->paymentData->status == PaymentStatuses::EXPIRED) {
                wp_redirect('/' . PageController::get_slug(PageType::PAYMENT_FAILED_PAGE));
                die;
            } 
        }

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-checkout-thank-you.php');
        
        return ob_get_clean();
    }

    public function shopping_cart() {
        $this->init_cart();

		$this->assets()->std_requires_asset('propeller-action-tooltip');

        ob_start();
        
        require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-shopping-cart.php');
        return ob_get_clean();
    }

    public function start() {
        if (!defined('PROPELLER_SITE_ID')) 
            Propeller::register_settings();

        $type = 'cartStart';

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';

        $gql = $this->model->cart_start(
            new RawObject('siteId: ' . PROPELLER_SITE_ID), 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)], 
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        if (is_object($cartData)) {
            SessionController::set(PROPELLER_CART_INITIALIZED, true);

            $this->start_postprocess($cartData);

            if (UserController::is_propeller_logged_in()) {
                $this->set_user(SessionController::get(PROPELLER_USER_ID));

                $this->set_user_default_cart_address();
            }
        }
    }

    public function set_user($user_id) {
        $this->init_cart();
        
        $type = 'cartSetUser';

        $rawParams = '{
            cartId: "' . $this->cart_id . '",
            userId: '. $user_id .'
        }';

        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';

        $gql = $this->model->set_user(
            ['input' => new RawObject($rawParams)], 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)], 
            PROPELLER_LANG
        );
        
        $cartData = $this->query($gql, $type);

        SessionController::set(PROPELLER_CART_USER_SET, true);

        $this->postprocess($cartData);

        return $cartData;
    }

    public function add_item($quantity, $product_id, $notes = '', $price = null) {
        $this->init_cart();
        
        $type = 'cartAddItem';

        $raw_params_arr = [
            'cartId: "' . $this->cart_id . '"',
            'quantity: '. $quantity,
            'notes: "' . $notes .'"',
            'productId: '. $product_id
        ];

        if ($price)
            $raw_params_arr[] = 'price: ' . $price;

        $rawParams = '{
            ' . implode(',', $raw_params_arr) . '
        }';

        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->add_item(
            ['input' => new RawObject($rawParams)],
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        $added_item = null;
        
        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $added_item = $this->get_item_by_product_id($product_id);
    
            $postprocess->error         = false;
            $postprocess->badge         = $this->get_items_count();
            $postprocess->message       = $added_item->product->name[0]->value . " added to cart";
            $postprocess->totals        = $this->get_totals();
            $postprocess->taxLevels     = $this->get_tax_levels();
            $postprocess->postageData   = $this->get_postage_data();
            $postprocess->show_modal    = true;
            $postprocess->item          = $added_item;
        }
        else {
            $this->response = new stdClass();

            $postprocess->error = true;
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;

        return $this->response;
    }

    public function add_item_bundle($quantity, $bundle_id, $notes = '') {
        $this->init_cart();
        
        $type = 'cartAddBundle';

        $rawParams = '{
            cartId: "' . $this->cart_id . '",
            quantity: '. $quantity .',
            notes: "' . $notes .'",
            bundleId: '. $bundle_id .'
        }';

        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->add_item_bundle(
            ['input' => new RawObject($rawParams)],
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $added_item = $this->get_item_by_bundle_id($bundle_id);
    
            $postprocess->badge         = $this->get_items_count();
            $postprocess->message       = __("Bundle added to cart", 'propeller-ecommerce');
            $postprocess->totals        = $this->get_totals();
            $postprocess->taxLevels     = $this->get_tax_levels();
            $postprocess->postageData   = $this->get_postage_data();
            $postprocess->show_bundle   = true;
            $postprocess->item          = $added_item;
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;

        return $this->response;
    }

    public function update_item($quantity, $notes, $item_id, $price = null) {
        $this->init_cart();

        $type = 'cartUpdateItem';

        $raw_params_arr = [
            'cartId: "' . $this->cart_id . '"',
            'quantity: '. $quantity,
            'notes: "' . $notes .'"',
            'itemId: '. $item_id
        ];

        if ($price)
            $raw_params_arr[] = 'price: ' . $price;

        $rawParams = '{
            ' . implode(',', $raw_params_arr) . '
        }';

        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';

        $gql = $this->model->update_item(
            ['input' => new RawObject($rawParams)],
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);
            
            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Item updated", 'propeller-ecommerce');
            $postprocess->totals = $this->get_totals();
            $postprocess->items = $this->get_items();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();

            ob_start();
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-shopping-cart.php');
            $postprocess->content = ob_get_clean();
        }
        else {
            $this->response = new stdClass();

            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function delete_item($item_id) {
        $this->init_cart();

        $type = 'cartDeleteItem';

        $rawParams = '{
            cartId: "' . $this->cart_id . '",
            itemId: '. $item_id .'
        }';

        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
    
        $gql = $this->model->delete_item(
            ['input' => new RawObject($rawParams)],
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Item remove from cart", 'propeller-ecommerce');
            $postprocess->remove = $item_id;
            $postprocess->totals = $this->get_totals();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();

            ob_start();
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-shopping-cart.php');
            $postprocess->content = ob_get_clean();
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function update($payment_data, $postage_data, $notes, $reference, $extra3, $extra4, $carrier) {
        $this->init_cart();

        $type = 'cartUpdate';

        $rawParams = '{
            paymentData: '. new RawObject('{ method: "' . $payment_data->method . '"}') .'
            postageData: '. new RawObject('{ partialDeliveryAllowed: ' . $postage_data->partialDeliveryAllowed . ', requestDate: "' . $postage_data->requestDate . '", shippingMethod: "' . $postage_data->shippingMethod . '" }') .'
            cartId: "' . $this->cart_id . '"
            notes: "' . $notes . '"
            reference: "' . $reference . '"
            extra3: "' . $extra3 . '"
            extra4: "' . $extra4 . '"
            carrier: "' . $carrier . '"
        }';
        
        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->update(
            ['input' => new RawObject($rawParams)], 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $this->postprocess($cartData);

        return $this->response;
    }

    public function update_address($address_data) {
        $this->init_cart();

        $type = 'cartUpdateAddress';

        $params = $this->format_address_params($address_data);
        $params[] = 'cartId: "' . $this->cart_id . '"';
        $rawParams = '{' . implode(',', $params) . '}';

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';

        $gql = $this->model->cart_update_address(
            ['input' => new RawObject($rawParams)],
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->success = true;
            $postprocess->message = __("Address updated", 'propeller-ecommerce');

            if (isset($address_data['next_step']))
                $postprocess->redirect = esc_url_raw(home_url("/" . PageController::get_slug(PageType::CHECKOUT_PAGE) . "/" . $address_data['next_step'] . '/'));
            else
                $postprocess->reload = true;
        }
        else {
            $postprocess->success = false;
            $postprocess->message = $cartData;
        }

        if (!$this->response)
            $this->response = new stdClass();

        $this->response->object = $this->object_name;

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function action_code($action_code) {
        $this->init_cart();

        $type = 'cartAddActionCode';

        $raw_params = [
            "cartId" => $this->cart_id,
            "actionCode" => $action_code
        ];

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->action_code(
            $raw_params, 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Added action code to cart", 'propeller-ecommerce');
            $postprocess->totals = $this->get_totals();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();
            $postprocess->reload = true;
        }
        else {
            $postprocess->message = __("This action code is not found", 'propeller-ecommerce');
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function remove_action_code($action_code) {
        $this->init_cart();

        $type = 'cartRemoveActionCode';

        $raw_params = [
            "cartId" => $this->cart_id,
            "actionCode" => $action_code
        ];

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->remove_action_code(
            $raw_params, 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Action code removed", 'propeller-ecommerce');
            $postprocess->totals = $this->get_totals();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();
            $postprocess->reload = true;
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function voucher_code($voucher_code) {
        $this->init_cart();

        $type = 'cartAddVoucherCode';

        $raw_params = [
            "cartId" => $this->cart_id,
            "voucherCode" => $voucher_code
        ];

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->voucher_code(
            $raw_params, 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Added voucher code to cart", 'propeller-ecommerce');
            $postprocess->totals = $this->get_totals();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();
            $postprocess->reload = true;
        }
        else {
            $postprocess->message = __("This voucher code is not found", 'propeller-ecommerce');
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function remove_voucher_code($voucher_code) {
        $this->init_cart();

        $type = 'cartRemoveVoucherCode';

        $raw_params = [
            "cartId" => $this->cart_id,
            "voucherCode" => $voucher_code
        ];

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->remove_voucher_code(
            $raw_params, 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            $postprocess->badge = $this->get_items_count();
            $postprocess->message = __("Voucher code removed", 'propeller-ecommerce');
            $postprocess->totals = $this->get_totals();
            $postprocess->postageData = $this->get_postage_data();
            $postprocess->taxLevels = $this->get_tax_levels();
            $postprocess->reload = true;
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    /**
     * regular, quick, dropshipment
     */
    public function change_order_type($order_type, $update_addresses = true) {
        $this->init_cart();

        $type = 'cartUpdate';

        $raw_data = '{
            cartId: "' . $this->cart_id . '", 
            carrier: "' . $this->get_carrier() . '",
            extra3: "' . $this->get_extra3() . '",
            extra4: "' . $this->get_extra4() . '",
            notes: "' . $this->get_notes() . '",
            paymentData: {
                method: "' . $this->get_payment_data()->method . '"
            },
            postageData: {
                partialDeliveryAllowed: '. $this->get_postage_data()->partialDeliveryAllowed .', 
                requestDate: "' . $this->get_request_date() . '",
                shippingMethod: "' . $order_type . '"
            },
            reference: "' . $this->get_reference() . '"
        }';

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->update(
            ['input' => new RawObject($raw_data)], 
            Media::get([
                'name' => MediaImagesType::LARGE
            ], MediaType::IMAGES)->__toString(),
            ['input' => new RawObject($crossupsells_rawParams)],
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $this->postprocess($cartData);

            SessionController::set(PROPELLER_ORDER_TYPE, $order_type);

            SessionController::set(PROPELLER_ORDER_STATUS_TYPE, OrderStatus::ORDER_STATUS_NEW);

            if ($update_addresses) {
                $this->set_user_default_cart_address();
                SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, false);
            }

            $postprocess->message = __("Order type changed", 'propeller-ecommerce');
            $postprocess->reload = true;
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;        
    }

    public function replenish($items) {
        $responses = [];
        
        foreach ($items as $item) {
            $chunks = explode('-', $item);

            $response = new stdClass();

            $response->product_id = $chunks[0];
            $response->response = $this->add_item($chunks[1], $chunks[0], '');

            $responses[] = $response;
        }

        $postprocess = new stdClass();
        $items_content = [];
        $items_messages = [];

        $added_item = null;
        $has_errors = false;

        foreach ($responses as $response) {
            if ($response->response->postprocess->error) {
                $items_messages[] = '<span class="text-danger">' . $response->response->postprocess->message . '</span>';
                $has_errors = true;
            }
            else {
                $added_item = $this->get_item_by_product_id($response->product_id);
                
                $items_messages[]    = '<span class="text-success">' . $added_item->product->name[0]->value . " added to cart</span>";
                
                ob_start();
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-popup-item.php');

                $item_content = ob_get_clean();
                $items_content[] = $item_content;
            }
        }

        $postprocess->content       = implode('', $items_content);
        $postprocess->message       = implode('<br />', $items_messages);
        $postprocess->badge         = $this->get_items_count();
        $postprocess->totals        = $this->get_totals();
        $postprocess->taxLevels     = $this->get_tax_levels();
        $postprocess->postageData   = $this->get_postage_data();
        $postprocess->show_modal    = !$has_errors;
        $postprocess->error         = $has_errors;

        $this->response->postprocess = $postprocess;

        $this->response->object = "QuickOrder";

        return $this->response;
    }

    public function set_order_status($data) {
        SessionController::set(PROPELLER_ORDER_STATUS_TYPE, $data['order_status']);
    }

    public function process($order_data) {
        $this->init_cart();

		$payMethod = !empty($_POST['payMethod']) ? sanitize_text_field($_POST['payMethod']) : PROPELLER_DEFAULT_PAYMETHOD;

        if ($order_data['status'] == OrderStatus::ORDER_STATUS_NEW) {
            $payment = $this->get_payment_data();
            $payment->method = $order_data['payMethod'];

            $this->update($payment, 
                        $this->get_postage_data(), 
                        $order_data['notes'], 
                        $order_data['reference'], 
                        $this->cart->extra3, 
                        $this->cart->extra4, 
                        $this->cart->carrier);
        }
        else if ($order_data['status'] == OrderStatus::ORDER_STATUS_REQUEST) {
            // $order_data['type'] = 'delivery';
            // $this->update_address($order_data);

            // $order_data['type'] = 'invoice';
            // $this->update_address($order_data);
        }

        $type = 'cartProcess';

        $raw_params = '{
            cartId: "' . $this->cart_id . '"
            orderStatus: "' . $order_data['status'] . '" 
            language: "' . PROPELLER_LANG . '"
        }';

        // params for cross/upsells
        $crossupsells_rawParams = '{
            types: [' . strtoupper(CrossupsellTypes::ACCESSORIES) . ']
        }';
        
        $gql = $this->model->process(
            ['input' => new RawObject($raw_params)], 
            Media::get([
                'name' => MediaImagesType::SMALL
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );

        $cartData = $this->query($gql, $type);

        $postprocess = new stdClass();

        if (is_object($cartData)) {
            $payment_controller = new PaymentController();
            $redirect_url = $this->buildUrl('', PageController::get_slug(PageType::THANK_YOU_PAGE)) . '?order_id=' . $cartData->cartOrderId;
            
            if ($payMethod != PROPELLER_DEFAULT_PAYMETHOD && $payment_controller->has_providers()) {
                
                $payment_args = [
                    "user_id" => SessionController::get(PROPELLER_USER_ID),
                    "method" => $payMethod,
                    "order_id" => $cartData->cartOrderId,
                    "amount" => sprintf("%.2F", number_format($cartData->order->total->net, 2, '.', '')),
                    "currency" => "EUR",    // TODO: check how to make this dynamic
                    "redirect_url" => $this->buildUrl(PageController::get_slug(PageType::THANK_YOU_PAGE), '') . '?order_id=' . $cartData->cartOrderId,
                    "description" => "Order $cartData->cartOrderId - payment",
                    "user_data" => SessionController::get(PROPELLER_USER_DATA),
                    "cart" => SessionController::get(PROPELLER_CART),
                ];

                $payment_response = $payment_controller->create($payment_args);

                if (isset($payment_response->error) && $payment_response->error) {
                    $this->response = new stdClass();

                    $this->response->postprocess = $payment_response;
                    
                    return $this->response;
                }

                $redirect_url = $payment_response->payment_data['checkout_url'];
            }
            else {
                $orderController = new OrderController();
                if ( SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == OrderStatus::ORDER_STATUS_NEW) {
                    $orderController->change_status([
                        'order_id' => $cartData->cartOrderId,
                        'status' => SessionController::get(PROPELLER_ORDER_STATUS_TYPE),
                        'add_pdf' => 'true',
                        'payStatus' => 'UNKNOWN',
                        'send_email' => 'true',
                        'delete_cart' => 'true'
                    ]);
                }
                else {
                    $orderController->change_status([
                        'order_id' => $cartData->cartOrderId,
                        'status' => SessionController::get(PROPELLER_ORDER_STATUS_TYPE),
                        'add_pdf' => 'false',
                        'payStatus' => 'UNKNOWN',
                        'send_email' => 'false',
                        'delete_cart' => 'true'
                    ]);
                }
            }

            $this->response = new stdClass();

            $this->response->order = $cartData->order;
            $this->response->cartOrderId = $cartData->cartOrderId;

            FlashController::add(PROPELLER_ORDER_PLACED, $cartData->cartOrderId);

            $postprocess->message = __("Cart processed", 'propeller-ecommerce');
            $postprocess->redirect = esc_url_raw($redirect_url);
        }
        else {
            $postprocess->message = $cartData;
        }

        $this->response->postprocess = $postprocess;
        
        return $this->response;
    }

    public function clear_cart() {
        // clean up previous cart data
        SessionController::remove(PROPELLER_CART);
        SessionController::remove(PROPELLER_CART_ID);
        SessionController::remove(PROPELLER_CART_INITIALIZED);
        SessionController::remove(PROPELLER_CART_USER_SET);
        // SessionController::remove(PROPELLER_ORDER_TYPE);

        SessionController::set(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED, false);
        SessionController::set(PROPELLER_ORDER_STATUS_TYPE, OrderStatus::ORDER_STATUS_NEW);
    }

    private function init_postprocess($cart) {
        // SessionController::set(PROPELLER_CART, $cart);
        SessionController::set(PROPELLER_CART_ID, $cart->cartId);
        SessionController::set(PROPELLER_USER_ID, $cart->userId);
        
        $this->cart = $cart;
        $this->cart_id = $cart->cartId;

        $items = [];
        foreach ($this->cart->items as $item) {
            $product = new Product($item->product);

            if (!empty($item->product->crossupsells)) {
                $crossupsells = [];

                foreach ($item->product->crossupsells as $crossupsell) {
                    $crossupsell_product = new Product($crossupsell->product);

                    $crossupsell->product = $crossupsell_product;
                    $crossupsells[] = $crossupsell;
                }

                $item->product->crossupsells = $crossupsells;
            }

            $item->product = $product;
            $items[] = $item;
        }

        $this->cart->items = $items;
    }

    private function start_postprocess($cart) {
        if (!is_object($cart)) {
            if ($this->response)
                unset($this->response);

            $this->response = new stdClass();

            $this->response->error = $cart;
            return;
        }   

        SessionController::set(PROPELLER_CART, $cart);
        SessionController::set(PROPELLER_CART_ID, $cart->cartId);
        
        $this->cart = $cart;
        $this->cart_id = $cart->cartId;
    }

    private function postprocess($cart) {
        // start the response fresh after every call
        if ($this->response)
            unset($this->response);
        
        $this->response = new stdClass();

        if (!is_object($cart)) {
            $this->response->error = $cart;
            return;
        }   

        if (isset($cart->response)) {
            $this->response = new stdClass();

            $this->response->data = $cart->response->data;

            if (is_array($cart->response->messages)) {
                $this->response->api_messages = [];

                foreach ($cart->response->messages as $msg) {
                    $this->response->api_messages[] = $msg;
                }
            }


            if (isset($cart->response->error)) {
                $this->response->errors = [];

                foreach ($cart->response->errors as $err) {
                    $this->response->errors[] = $err;
                }
            }

            unset($cart->response);
        }

        SessionController::set(PROPELLER_CART, $cart->cart);
        SessionController::set(PROPELLER_CART_ID, $cart->cart->cartId);
        
        $this->cart = $cart->cart;
        $this->cart_id = $cart->cart->cartId;

        $items = [];
        foreach ($this->cart->items as $item) {
            $product = new Product($item->product);

            if (!empty($item->product->crossupsells)) {
                $crossupsells = [];

                foreach ($item->product->crossupsells as $crossupsell) {
                    $crossupsell_product = new Product($crossupsell->product);

                    $crossupsell->product = $crossupsell_product;
                    $crossupsells[] = $crossupsell;
                }

                $item->product->crossupsells = $crossupsells;
            }

            $item->product = $product;
            $items[] = $item;
        }

        $this->cart->items = $items;
    }

    public function set_user_default_cart_address() {
        $userData = SessionController::get(PROPELLER_USER_DATA);
        
        $invoiceAddress = null;
        $deliveryAddress = null;

        if ($userData->__typename == UserTypes::CONTACT) {
            foreach ($userData->company->addresses as $addr) {
                if ($addr->type == AddressType::DELIVERY && $addr->isDefault == 'Y')
                    $deliveryAddress = $addr;
                if ($addr->type == AddressType::INVOICE && $addr->isDefault == 'Y')
                    $invoiceAddress = $addr;
            }
        }
        else {
            foreach ($userData->addresses as $addr) {
                if ($addr->type == AddressType::DELIVERY && $addr->isDefault == 'Y')
                    $deliveryAddress = $addr;
                if ($addr->type == AddressType::INVOICE && $addr->isDefault == 'Y')
                    $invoiceAddress = $addr;
            }
        }

        try {
            if (is_object($invoiceAddress))
                $this->update_address((array) $invoiceAddress);
        }
        catch (Exception $ex) {}

        try {
            if (is_object($deliveryAddress))
                $this->update_address((array) $deliveryAddress);
        }
        catch (Exception $ex) {}
    }

    protected function format_address_params($args) {
        $params = [];

        if (isset($args['city']) && !empty($args['city'])) $params[] = 'city: "' . $args['city'] . '"';
        if (isset($args['code']) && !empty($args['code'])) $params[] = 'code: "' . $args['code'] . '"';
        if (isset($args['company']) && !empty($args['company'])) $params[] = 'company: "' . $args['company'] . '"';
        if (isset($args['country']) && !empty($args['country'])) $params[] = 'country: "' . $args['country'] . '"';
        if (isset($args['email']) && !empty($args['email'])) $params[] = 'email: "' . $args['email'] . '"';
        
        if (isset($args['firstName']) && !empty($args['firstName'])) $params[] = 'firstName: "' . $args['firstName'] . '"';
        else $params[] = 'firstName: "' . SessionController::get(PROPELLER_USER_DATA)->firstName . '"';
        
        if (isset($args['lastName']) && !empty($args['lastName'])) $params[] = 'lastName: "' . $args['lastName'] . '"';
        else $params[] = 'lastName: "' . SessionController::get(PROPELLER_USER_DATA)->lastName . '"';

        if (isset($args['middleName']) && !empty($args['middleName'])) $params[] = 'middleName: "' . $args['middleName'] . '"';
        if (isset($args['gender']) && !empty($args['gender'])) $params[] = 'gender: ' . new RawObject($args['gender']);
        else  $params[] = 'gender: ' . new RawObject("U");
        // if (isset($args['isDefault']) && !empty($args['isDefault'])) $params[] = 'isDefault: ' . new RawObject($args['isDefault']);
        if (isset($args['notes']) && !empty($args['notes'])) $params[] = 'notes: "' . $args['notes'] . '"';
        if (isset($args['number']) && !empty($args['number'])) $params[] = 'number: "' . $args['number'] . '"';
        if (isset($args['numberExtension']) && !empty($args['numberExtension'])) $params[] = 'numberExtension: "' . $args['numberExtension'] . '"';
        if (isset($args['postalCode']) && !empty($args['postalCode'])) $params[] = 'postalCode: "' . $args['postalCode'] . '"';
        if (isset($args['region']) && !empty($args['region'])) $params[] = 'region: "' . $args['region'] . '"';
        if (isset($args['street']) && !empty($args['street'])) $params[] = 'street: "' . $args['street'] . '"';
        if (isset($args['icp']) && !empty($args['icp'])) $params[] = 'icp: ' . new RawObject($args['icp']);
        else $params[] = 'icp: ' . new RawObject("N");
        if (isset($args['phone']) && !empty($args['phone'])) $params[] = 'phone: "' . $args['phone'] . '"';
        $params[] = 'type: ' . new RawObject(isset($args['type']) ?  $args['type'] : AddressType::DELIVERY);

        return $params;
    }

    /**
     * 
     *  GETTERS
     * 
     */
    public function get_cart() {
        if (!$this->cart) $this->init_cart();

        return $this->cart;
    }

    public function get_items() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->items;
    }

    public function get_item_by_product_id($product_id) {
        if (!$this->cart) $this->init_cart();

        foreach ($this->get_items() as $item) {
            if ($item->productId == $product_id) {
                $item->product = new Product($item->product);
                return $item;
            }                
        }

        return null;
    }

    public function get_item_by_bundle_id($bundle_id) {
        if (!$this->cart) $this->init_cart();

        foreach ($this->get_items() as $item) {
            if ($item->bundleId == $bundle_id)
                return $item;
        }

        return null;
    }
    public function get_items_count() {
        if (!$this->cart) $this->init_cart();

        $count = 0;
        
        foreach ($this->get_items() as $item) 
            $count += $item->quantity;

        return $count;
    }

    public function get_payment_data() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->paymentData;
    }

    public function get_postage_data() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->postageData;
    }

    public function get_totals() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->total;
    }

    public function get_total_price() {
        if (!$this->cart) $this->init_cart();

        if (!$this->cart || !$this->cart->total)
            return 0.00;

        return $this->cart->total->totalNet;
    }

    public function get_date_created() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->dateCreated;
    }

    public function get_notes() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->notes;
    }

    public function get_carrier() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->carrier;
    }

    public function get_action_code() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->actionCode;
    }

    public function get_invoice_address() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->invoiceAddress;
    }

    public function get_delivery_address() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->deliveryAddress;
    }

    public function get_tax_levels() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->taxLevels;
    }

    public function get_pay_methods() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->payMethods;
    }

    public function get_carriers() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->carriers;
    }

    public function get_reference() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->reference;
    }

    public function get_extra3() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->extra3;
    }

    public function get_extra4() {
        if (!$this->cart) $this->init_cart();

        return $this->cart->extra4;
    }

    public function get_request_date() {
        if (!$this->cart) $this->init_cart();
        
        return $this->get_postage_data()->requestDate;
    }
}