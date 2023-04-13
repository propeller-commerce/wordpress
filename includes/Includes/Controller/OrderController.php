<?php

namespace Propeller\Includes\Controller;

use GraphQL\RawObject;
use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\MediaType;
use Propeller\Includes\Enum\OrderStatus;
use Propeller\Includes\Enum\OrderType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Product;
use Propeller\Includes\Query\Media;
use Propeller\PropellerUtils;
use stdClass;

class OrderController extends BaseController {
    protected $type = 'order';
    protected $model;
    
    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('order');
    }

    public function orders_table($orders, $data, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders-table.php');
    }

    public function orders_table_header($orders) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders-table-header.php');
    }

    public function orders_table_list($orders, $data, $obj) {

		$this->assets()->std_requires_asset('propeller-account-paginator');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders-table-list.php');
    }

    public function orders_table_list_item($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders-table-list-item.php');
    }

    public function orders_table_list_paging($data, $obj) {

		$this->assets()->std_requires_asset('propeller-account-paginator');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders-table-list-paging.php');
    }

    public function quotations_table($orders, $data, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations-table.php');
    }

    public function quotations_table_header($orders) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations-table-header.php');
    }

    public function quotations_table_list($orders, $data, $obj) {

	    $this->assets()->std_requires_asset('propeller-account-paginator');

	    require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations-table-list.php');
    }

    public function quotations_table_list_item($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations-table-list-item.php');
    }

    public function quotations_table_list_paging($data, $obj) {

		$this->assets()->std_requires_asset('propeller-account-paginator');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations-table-list-paging.php');
    }

    public function order_details_back_button($obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-back-button.php');
    }

    public function order_details_title($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-title.php');
    }

    public function order_details_data($order) {
	    require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-data.php');
    }

    public function order_details_shipments($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-shipments.php');
    }

    public function order_details_pdf($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-pdf.php');
    }

    public function order_details_returns($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-returns.php');
    }

    public function order_details_returns_form($order) {

		$this->assets()->std_requires_asset('propeller-order-details-return');

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-returns-form.php');
    }

    public function order_details_reorder($order) {
        $reorder_item_ids = [];
        
        foreach($order->items as $item) { 
            if ($item->class == 'product' && $item->isBonus == 'N')
                $reorder_item_ids[] = $item->productId . '-' . $item->quantity;
        } 

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-reorder.php');
    }

    public function order_details_overview_headers($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-overview-headers.php');
    }

    public function order_details_overview_items($items, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-overview-items.php');
    }

    public function order_details_overview_item($item, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-overview-item.php');
    }

    public function order_details_overview_bonus_items($items, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-overview-bonus-items.php');
    }

    public function order_details_overview_bonus_item($item, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-overview-bonus-item.php');
    }

    public function order_details_totals($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details-totals.php');
    }

    public function quote_details_back_button($obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-back-button.php');
    }

    public function quote_details_title($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-title.php');
    }
    
    public function quote_details_data($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-data.php');
    }

    public function quote_details_overview_headers($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-overview-headers.php');
    }

    public function quote_details_overview_items($items, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-overview-items.php');
    }

    public function quote_details_overview_item($item, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-overview-item.php');
    }

    public function quote_details_overview_bonus_items($items, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-overview-bonus-items.php');
    }

    public function quote_details_overview_bonus_item($item, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-overview-bonus-item.php');
    }

    public function quote_details_totals($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-totals.php');
    }

    public function quote_details_order($order) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details-order.php');
    }

    public function order_thank_you_headers($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-headers.php');
    }

    public function order_thank_you_billing_info($order, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-billing-info.php');
    }

    public function order_thank_you_delivery_info($order, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
        
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-delivery-info.php');
    }

    public function order_thank_you_shipping_info($order, $obj) {
        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
        
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-shipping-info.php');
    }
    
    public function order_thank_you_payment_info($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-payment-info.php');
    }

    public function order_thank_you_items_title($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-items-title.php');
    }

    public function order_thank_you_items($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-items.php');
    }

    public function order_thank_you_summary_totals($order, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'propeller-order-thank-you-summary-totals.php');
    }


    

    public function quotations($is_ajax = false) {
        if (!UserController::is_logged_in()) {
            wp_redirect('/' . PageController::get_slug(PageType::LOGIN_PAGE));
            exit;
        }

        ob_start();

        $_REQUEST = PropellerUtils::sanitize($_REQUEST);

        $order_args = [
            'status' => '["' . new RawObject(OrderStatus::ORDER_STATUS_QUOTATION) .'"]'
        ];
    
        $order_args['offset'] = (isset($_REQUEST['offset']) ? (int) $_REQUEST['offset'] : 12);
    
        $order_args['page'] = (isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1);
    
        $this->data = $this->get_orders($order_args);
    
        $this->orders = $this->data->items; 
        
        if ($is_ajax) {
            apply_filters('propel_account_quotations_table_list', $this->orders, $this->data, $this);
            
            $response = new stdClass();
            $response->content = ob_get_clean();
            
            return $response;
        }
        else {
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quotations.php');
        }
        
        return ob_get_clean();
    }

    public function orders($is_ajax = false) {
        if (!UserController::is_logged_in()) {
            wp_redirect('/' . PageController::get_slug(PageType::LOGIN_PAGE));
            exit;
        }

        $_REQUEST = PropellerUtils::sanitize($_REQUEST);

        $order_args = [
            'type'   => '['. new RawObject(OrderType::DROPSHIPMENT) . ', '.  new RawObject(OrderType::PURCHASE) .', '.  new RawObject(OrderType::QUOTATION) .']',
            'status' => '["' . new RawObject(OrderStatus::ORDER_STATUS_NEW) .'", "' . new RawObject(OrderStatus::ORDER_STATUS_CONFIRMED) .'", "' . new RawObject(OrderStatus::ORDER_STATUS_VALIDATED) .'", "' . new RawObject(OrderStatus::ORDER_STATUS_ARCHIVED) .'"]'
        ];
    
        $order_args['offset'] = (isset($_REQUEST['offset']) ? (int) $_REQUEST['offset'] : 12);
    
        $order_args['page'] = (isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1);
    
        $this->data = $this->get_orders($order_args);
    
        $this->orders = $this->data->items; 

        ob_start();

        if ($is_ajax) {
            apply_filters('propel_account_orders_table_list', $this->orders, $this->data, $this);
            
            $response = new stdClass();
            $response->content = ob_get_clean();
            
            return $response;
        }
        else {
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-orders.php');
        }
        
        return ob_get_clean();
    }

    public function order_details() {
        global $propel;

        $_REQUEST = PropellerUtils::sanitize($_REQUEST);
    
        $this->order = isset($propel['order']) 
            ? $propel['order'] 
            : $this->get_order((int) $_REQUEST['order_id']);

        $pdf_data = $this->get_pdf((int) $_REQUEST['order_id']);
        
        $this->order->pdf = $pdf_data;

        ob_start();

        if ($this->order->status == 'QUOTATION')
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-quote-details.php');
        else 
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-order-details.php');

        return ob_get_clean();
    }

    private function fix_order(&$order) {
        if (isset($order->items) && is_array($order->items)) {
            for ($i = 0; $i < count($order->items); $i++) {

                $order->items[$i]->product = new Product($order->items[$i]->product);

                // var_dump($order->items[$i]);

                if ($order->items[$i]->class == 'incentive') {
                    for ($j = 0; $j < count($order->items); $j++) {
                        if ($order->items[$i]->sku == 'free' && $order->items[$i]->parentOrderItemId == $order->items[$j]->id &&
                            $order->items[$j]->isBonus == 'Y' ) {
                            if (!isset($order->items[$i]->bonusitems))
                                $order->items[$i]->bonusitems = array();
                            
                            $order->items[$i]->bonusitems[] = $order->items[$j];
                            unset($order->items[$j]);
                        }
                    }
                }
            }
        }
    }

    public function get_orders($args = []) {
        $type = 'orders';

        $params = [];

        $userId = SessionController::get(PROPELLER_USER_DATA)->userId;

        $params[] = 'userId: '. $userId;
        
        if (isset($args['offset']))
            $params[] = 'offset: ' . (isset($args['offset']) ? (int) $args['offset'] : 12);
        if (isset($args['page']))
            $params[] = 'page: ' . (isset($args['page']) ? (int) $args['page'] : 1);
        if (isset($args['status']))
            $params[] = 'status: ' . (isset($args['status']) ? $args['status'] : '');
        if (isset($args['type']))
            $params[] = 'type: ' . (isset($args['type']) ? $args['type'] : '');

        $rawParams = '{' . implode(',', $params) . '}';

        $gql = $this->model->get_orders(['input' => new RawObject($rawParams)]);

        $ordersData = $this->query($gql, $type);

        return $ordersData;
    }

    public function get_order($order_id) {
        $type = 'order';

        $gql = $this->model->get_order(
            ['orderId' => $order_id],
            Media::get([
                'name' => MediaImagesType::SMALL
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );
        
        $orderData = $this->query($gql, $type);
        
        $this->fix_order($orderData);

        return $orderData;
    }

    public function get_pdf($order_id) {
        $type = 'orderGetPDF';

        $gql = $this->model->get_pdf(['orderId' => $order_id]);

        return $this->query($gql, $type);
    }

    public function order_cofirm_email($order_id) {
        $type = 'orderSendConfirmationEmail';

        $gql = $this->model->order_cofirm_email(['orderId' =>  $order_id]);

        return $this->query($gql, $type);
    }

    public function change_status($data) {
        $type = 'orderSetStatus';

        $raw_params = [];
        $raw_params[] = 'orderId: ' . $data['order_id'];
        $raw_params[] = 'status: "' . $data['status'] . '"';
        $raw_params[] = 'addPDFAttachment: ' . (isset($data['add_pdf']) ? $data['add_pdf'] : 'true');
        $raw_params[] = 'sendOrderConfirmationEmail: ' . (isset($data['send_email']) ? $data['send_email'] : 'true');
        $raw_params[] = 'deleteCart: ' . (isset($data['delete_cart']) ? $data['delete_cart'] : 'true');
        
        if (isset($data['payStatus']) && !empty(isset($data['payStatus'])))
            $raw_params[] = 'payStatus: "' . $data['payStatus'] . '"';

        $gql = $this->model->change_status(
            ['input' => new RawObject('{' . implode(',', $raw_params) . '}')],
            Media::get([
                'name' => MediaImagesType::SMALL
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );
        
        $orderData = $this->query($gql, $type);
        $response = new stdClass();

        if (is_object($orderData)) {
            $postprocess = new stdClass();

            $response->status = true;

            FlashController::add(PROPELLER_ORDER_PLACED, (int) $data['order_id']);
            
            $postprocess->redirect = esc_url_raw(add_query_arg(['order_id' => $data['order_id']], $this->buildUrl(PageController::get_slug(PageType::THANK_YOU_PAGE), '')));
            $postprocess->status = true;

            $response->postprocess = $postprocess;
        }
        else {
            $response->message = $orderData;
        }

        return $response;
    }

    public function return_request($args) {
        $cc = !empty(PROPELLER_CC_EMAIL) ? PROPELLER_CC_EMAIL : get_bloginfo('admin_email');
        $bcc = !empty(PROPELLER_BCC_EMAIL) ? PROPELLER_BCC_EMAIL : get_bloginfo('admin_email');
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>',
            'Cc: ' . $cc,
            'Bcc: ' . $bcc,
            'X-Priority: 1',
	        'X-Mailer: PHP/' . PHP_VERSION
        ];

        ob_start();

        require $this->load_template('emails', DIRECTORY_SEPARATOR . 'propeller-return-request-template.php');

        $email_content = ob_get_contents();
        ob_end_clean();    
        
        $response = new stdClass();
        $response->postprocess = new stdClass();

        $response->object = 'Order';

        $response->postprocess->success = wp_mail($args['return_email'], __('Return request', 'propeller-ecommerce'), $email_content, implode("\r\n", $headers));
        // $response->postprocess->success = mail($args['return_email'], __('Return request', 'propeller-ecommerce'), $email_content, implode("\r\n", $headers));

        $msg = __('Return request sent. We will contact you.', 'propeller-ecommerce');
        if (!$response->postprocess->success) {
            $err = debug_wpmail($response->postprocess->success);

            if (count($err))
                $msg = $err[0];
        }            

        $response->postprocess->order_id = $args['return_order'];
        $response->postprocess->order_email = $args['return_email'];
        $response->postprocess->return_success = $response->postprocess->success;
        $response->postprocess->message = $msg;

        return $response;
    }
}