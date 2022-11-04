<?php

namespace Propeller;

use Exception;
use Propeller\PropellerLoader;
use Propeller\Admin\PropellerAdmin;
use Propeller\Frontend\PropellerFrontend;
use MatthiasMullie\Minify;
use Propeller\Custom\ExtendFrontend;
use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;

class Propeller {
    
    protected $propeller;
    
    protected $version;

    protected $fe_actions = [
        'init' => 'init_propeller', 
        'wp_head' => ['wp_head_script', 1]
    ];

    public static $fe_shortcodes = [
        'menu'                              => 'draw_menu',
        'home-page'                         => 'home_page',
        'product-listing'                   => 'product_listing', 
        'product-details'                   => 'product_details',
        'product-slider'                    => 'product_slider',
        'search'                            => 'search_products',   // search form
        'product-search'                    => 'search',            // search results
        'brand-listing'                     => 'brand_listing',
        'brand-listing-content'             => 'brand_listing_content',
        'shopping-cart'                     => 'shopping_cart',
        'checkout'                          => 'checkout',
        'checkout-summary'                  => 'checkout_summary',
        'checkout-thank-you'                => 'checkout_thank_you',
        'mini-shopping-cart'                => 'mini_shopping_cart',
        'mini-checkout-cart'                => 'mini_checkout_cart',
        'account-favorites'                 => 'account_favorites',
        'account-invoices'                  => 'account_invoices',
        'account-orderlist'                 => 'account_orderlist',
        'account-quotations'                => 'quotations',
        'account-orders'                    => 'orders',
        'account-order-details'             => 'order_details',
        'account-addresses'                 => 'addresses',
        'account-page'                      => 'my_account',
        'account-details'                   => 'account_details',
        'account-mobile'                    => 'account_mobile',
        'edit-address'                      => 'edit_address',
        'price-toggle'                      => 'account_prices',
        'mini-account'                      => 'mini_account',
        'login-form'                        => 'login_form',
        'forgot-password-form'              => 'forgot_password_form',
        'reset-password-form'               => 'reset_password_form',
        'registration-form'                 => 'register_form',
        'shopping-cart-popup'               => 'shopping_cart_popup',
        'quick-add-to-basket'               => 'quick_add_to_basket',
        'newsletter-subscription-form'      => 'newsletter_subscription'
    ];

    // filter name => [controller, method, number of arguments]
    public static $fe_filters = [
        'propel_breadcrumbs' => ['HomepageController', 'breadcrumbs', 1], 
        
        'propel_category_title' => ['CategoryController', 'category_title', 1], 
        'propel_category_menu' => ['CategoryController', 'category_menu', 1], 
        'propel_category_filters' => ['CategoryController', 'category_filters', 1], 
        'propel_category_gecommerce_listing' => ['CategoryController', 'category_gecommerce_listing', 2], 
        'propel_category_listing_products' => ['CategoryController', 'category_listing_products', 2], 
        'propel_category_listing_pagination' => ['CategoryController', 'category_listing_pagination', 4], 
        'propel_category_description' => ['CategoryController', 'category_listing_description', 1], 
        'propel_category_grid' => ['CategoryController', 'category_listing_grid', 7], 
        'propel_product_listing_pre_grid' => ['CategoryController', 'product_listing_pre_grid', 6], 

        'propel_product_card' => ['ProductController', 'product_card', 2], 
        'propel_cluster_card' => ['ProductController', 'cluster_card', 2], 
        
        'propel_product_gecommerce' => ['ProductController', 'product_gecommerce', 2], 
        'propel_product_gallery' => ['ProductController', 'product_gallery', 2], 
        'propel_product_name' => ['ProductController', 'product_name', 1], 
        'propel_product_meta' => ['ProductController', 'product_meta', 2], 
        'propel_product_name_mobile' => ['ProductController', 'product_name_mobile', 1], 
        'propel_product_meta_mobile' => ['ProductController', 'product_meta_mobile', 2], 
        'propel_product_desc_media' => ['ProductController', 'product_desc_media', 2], 
        'propel_product_price_details' => ['ProductController', 'product_price_details', 1], 
        'propel_product_stock' => ['ProductController', 'product_stock', 1], 
        'propel_product_short_desc' => ['ProductController', 'product_short_desc', 1], 
        'propel_product_bulk_prices' => ['ProductController', 'product_bulk_prices', 1], 
        'propel_product_bundles' => ['ProductController', 'product_bundles', 2], 
        'propel_product_crossupsells' => ['ProductController', 'product_crossupsells', 2], 
        'propel_product_crossupsell_card' => ['ProductController', 'product_crossupsell_card', 2], 
        'propel_product_add_favorite' => ['ProductController', 'product_add_favorite', 1], 
        'propel_product_add_to_basket' => ['ProductController', 'product_add_to_basket', 1], 
        'propel_product_description' => ['ProductController', 'product_description', 1], 
        'propel_product_downloads' => ['ProductController', 'product_downloads', 1], 
        'propel_product_videos' => ['ProductController', 'product_videos', 1], 
        'propel_product_specifications' => ['ProductController', 'product_specifications', 1], 

        'propel_cluster_name' => ['ProductController', 'cluster_name', 1], 
        'propel_cluster_name_mobile' => ['ProductController', 'cluster_name_mobile', 1], 
        'propel_cluster_product_name' => ['ProductController', 'cluster_product_name', 1], 
        'propel_cluster_product_name_mobile' => ['ProductController', 'cluster_product_name_mobile', 1], 
        'propel_cluster_meta' => ['ProductController', 'cluster_meta', 3], 
        'propel_cluster_meta_mobile' => ['ProductController', 'cluster_meta_mobile', 3], 
        'propel_cluster_gallery' => ['ProductController', 'cluster_gallery', 2], 
        'propel_cluster_desc_media' => ['ProductController', 'cluster_desc_media', 3], 
        'propel_cluster_description' => ['ProductController', 'cluster_description', 1], 
        'propel_cluster_specifications' => ['ProductController', 'cluster_specifications', 1], 
        'propel_cluster_downloads' => ['ProductController', 'cluster_downloads', 1], 
        'propel_cluster_videos' => ['ProductController', 'cluster_videos', 1], 
        'propel_cluster_price_details' => ['ProductController', 'cluster_price_details', 1], 
        'propel_cluster_stock' => ['ProductController', 'cluster_stock', 1], 
        'propel_cluster_gecommerce' => ['ProductController', 'cluster_gecommerce', 3], 
        'propel_cluster_bundles' => ['ProductController', 'cluster_bundles', 2], 
        'propel_cluster_crossupsells' => ['ProductController', 'cluster_crossupsells', 2], 
        'propel_cluster_options' => ['ProductController', 'cluster_options', 3], 
        'propel_cluster_add_favorite' => ['ProductController', 'cluster_add_favorite', 1], 
        
        'propel_my_account_title' => ['UserController', 'account_title', 1], 
        'propel_my_account_menu' => ['UserController', 'account_menu', 1], 
        'propel_my_account_user_details_title' => ['UserController', 'account_user_details_title', 1], 
        'propel_my_account_user_details' => ['UserController', 'account_user_details', 2], 
        'propel_my_account_company_details' => ['UserController', 'account_company_details', 1], 
        'propel_my_account_pass_newsletter_title' => ['UserController', 'account_pass_newsletter_title', 1], 
        'propel_my_account_pass_newsletter' => ['UserController', 'account_pass_newsletter', 1], 
        'propel_my_account_addresses_title' => ['UserController', 'account_addresses_title', 1], 
        
        'propel_address_box' => ['AddressController', 'address_box', 7], 
        'propel_address_add' => ['AddressController', 'address_add', 3], 
        'propel_address_popup' => ['AddressController', 'address_popup', 2], 
        'propel_address_form' => ['AddressController', 'address_form', 1], 
        'propel_address_modify' => ['AddressController', 'address_modify', 1], 
        'propel_address_delete' => ['AddressController', 'address_delete', 1], 
        'propel_address_delete_popup' => ['AddressController', 'address_delete_popup', 1], 
        'propel_address_set_default' => ['AddressController', 'address_set_default', 1], 

        'propel_account_orders_table' => ['OrderController', 'orders_table', 3], 
        'propel_account_orders_table_header' => ['OrderController', 'orders_table_header', 1], 
        'propel_account_orders_table_list' => ['OrderController', 'orders_table_list', 3], 
        'propel_account_orders_table_list_item' => ['OrderController', 'orders_table_list_item', 2], 
        'propel_account_orders_table_list_paging' => ['OrderController', 'orders_table_list_paging', 2], 
        'propel_account_quotations_table' => ['OrderController', 'quotations_table', 3], 
        'propel_account_quotations_table_header' => ['OrderController', 'quotations_table_header', 1], 
        'propel_account_quotations_table_list' => ['OrderController', 'quotations_table_list', 3], 
        'propel_account_quotations_table_list_item' => ['OrderController', 'quotations_table_list_item', 2], 
        'propel_account_quotations_table_list_paging' => ['OrderController', 'quotations_table_list_paging', 2], 
        
        'propel_order_details_back_button' => ['OrderController', 'order_details_back_button', 1], 
        'propel_order_details_title' => ['OrderController', 'order_details_title', 1], 
        'propel_order_details_data' => ['OrderController', 'order_details_data', 1], 
        'propel_order_details_shipments' => ['OrderController', 'order_details_shipments', 1], 
        'propel_order_details_pdf' => ['OrderController', 'order_details_pdf', 1], 
        'propel_order_details_returns' => ['OrderController', 'order_details_returns', 1], 
        'propel_order_details_returns_form' => ['OrderController', 'order_details_returns_form', 1], 
        'propel_order_details_reorder' => ['OrderController', 'order_details_reorder', 1], 
        'propel_order_details_overview_headers' => ['OrderController', 'order_details_overview_headers', 1], 
        'propel_order_details_overview_items' => ['OrderController', 'order_details_overview_items', 2], 
        'propel_order_details_overview_item' => ['OrderController', 'order_details_overview_item', 2], 
        'propel_order_details_overview_bonus_items' => ['OrderController', 'order_details_overview_bonus_items', 2], 
        'propel_order_details_overview_bonus_item' => ['OrderController', 'order_details_overview_bonus_item', 2], 
        'propel_order_details_totals' => ['OrderController', 'order_details_totals', 1], 
        
        'propel_quote_details_back_button' => ['OrderController', 'quote_details_back_button', 1], 
        'propel_quote_details_title' => ['OrderController', 'quote_details_title', 1], 
        'propel_quote_details_data' => ['OrderController', 'quote_details_data', 1], 
        'propel_quote_details_overview_headers' => ['OrderController', 'quote_details_overview_headers', 1], 
        'propel_quote_details_overview_items' => ['OrderController', 'quote_details_overview_items', 2], 
        'propel_quote_details_overview_item' => ['OrderController', 'quote_details_overview_item', 2], 
        'propel_quote_details_overview_bonus_items' => ['OrderController', 'quote_details_overview_bonus_items', 2], 
        'propel_quote_details_overview_bonus_item' => ['OrderController', 'quote_details_overview_bonus_item', 2], 
        'propel_quote_details_totals' => ['OrderController', 'quote_details_totals', 1], 
        'propel_quote_details_order' => ['OrderController', 'quote_details_order', 2], 

        'propel_shopping_cart_title' => ['ShoppingCartController', 'shopping_cart_title', 1], 
        'propel_shopping_cart_info' => ['ShoppingCartController', 'shopping_cart_info', 2], 
        'propel_shopping_cart_table_header' => ['ShoppingCartController', 'shopping_cart_table_header', 2], 
        'propel_shopping_cart_table_items' => ['ShoppingCartController', 'shopping_cart_table_items', 2], 
        'propel_shopping_cart_table_product_item' => ['ShoppingCartController', 'shopping_cart_table_product_item', 3], 
        'propel_shopping_cart_table_bundle_item' => ['ShoppingCartController', 'shopping_cart_table_bundle_item', 3], 
        'propel_shopping_cart_bonus_items' => ['ShoppingCartController', 'shopping_cart_bonus_items', 2], 
        'propel_shopping_cart_bonus_items_title' => ['ShoppingCartController', 'shopping_cart_bonus_items_title', 2], 
        'propel_shopping_cart_bonus_item' => ['ShoppingCartController', 'shopping_cart_bonus_item', 3], 
        'propel_shopping_cart_action_code' => ['ShoppingCartController', 'shopping_cart_action_code', 2], 
        'propel_shopping_cart_voucher' => ['ShoppingCartController', 'shopping_cart_voucher', 2], 
        'propel_shopping_cart_buttons' => ['ShoppingCartController', 'shopping_cart_buttons', 2], 
        'propel_shopping_cart_totals' => ['ShoppingCartController', 'shopping_cart_totals', 2], 
        'propel_shopping_cart_totals_with_items' => ['ShoppingCartController', 'shopping_cart_totals_with_items', 2], 
        

        'propel_shopping_cart_invoice_address_form' => ['ShoppingCartController', 'shopping_cart_invoice_address_form', 3], 
        'propel_shopping_cart_delivery_address_form' => ['ShoppingCartController', 'shopping_cart_delivery_address_form', 3], 

        'propel_checkout_step_1_info' => ['ShoppingCartController', 'checkout_step_1_info', 2], 
        'propel_checkout_step_2_info' => ['ShoppingCartController', 'checkout_step_2_info', 2], 
        'propel_checkout_step_3_info' => ['ShoppingCartController', 'checkout_step_3_info', 2], 

        'propel_checkout_paymethods' => ['ShoppingCartController', 'checkout_paymethods', 3], 
        'propel_checkout_paymethod' => ['ShoppingCartController', 'checkout_paymethod', 3], 

        'propel_checkout_invoice_details' => ['ShoppingCartController', 'checkout_invoice_details', 2], 

        'propel_checkout_summary_form' => ['ShoppingCartController', 'checkout_summary_form', 2], 
        'propel_checkout_summary_submit' => ['ShoppingCartController', 'checkout_summary_submit', 2], 

        'propel_checkout_delivery_addresses' => ['ShoppingCartController', 'checkout_delivery_addresses', 2], 
        'propel_checkout_delivery_address' => ['ShoppingCartController', 'checkout_delivery_address', 3], 
        'propel_checkout_delivery_address_new' => ['ShoppingCartController', 'checkout_delivery_address_new', 2], 
        
        'propel_checkout_shipping_method' => ['ShoppingCartController', 'checkout_shipping_method', 2], 
        
        'propel_checkout_regular_page_title' => ['ShoppingCartController', 'checkout_regular_page_title', 2], 
        'propel_checkout_regular_step_1_titles' => ['ShoppingCartController', 'checkout_regular_step_1_titles', 2], 
        'propel_checkout_regular_step_1_submit' => ['ShoppingCartController', 'checkout_regular_step_1_submit', 3], 
        'propel_checkout_regular_step_1_other_steps' => ['ShoppingCartController', 'checkout_regular_step_1_other_steps', 2], 
        
        
        'propel_checkout_regular_step_2_titles' => ['ShoppingCartController', 'checkout_regular_step_2_titles', 2], 
        'propel_checkout_regular_step_2_form' => ['ShoppingCartController', 'checkout_regular_step_2_form', 3], 
        'propel_checkout_regular_step_2_submit' => ['ShoppingCartController', 'checkout_regular_step_2_submit', 2], 
        'propel_checkout_regular_step_2_other_steps' => ['ShoppingCartController', 'checkout_regular_step_2_other_steps', 2], 

        'propel_checkout_regular_step_3_titles' => ['ShoppingCartController', 'checkout_regular_step_3_titles', 2], 
        'propel_checkout_regular_step_3_form' => ['ShoppingCartController', 'checkout_regular_step_3_form', 3], 
        'propel_checkout_regular_step_3_submit' => ['ShoppingCartController', 'checkout_regular_step_3_submit', 2], 
        
        'propel_order_thank_you_headers' => ['OrderController', 'order_thank_you_headers', 2], 
        'propel_order_thank_you_billing_info' => ['OrderController', 'order_thank_you_billing_info', 2], 
        'propel_order_thank_you_delivery_info' => ['OrderController', 'order_thank_you_delivery_info', 2], 
        'propel_order_thank_you_shipping_info' => ['OrderController', 'order_thank_you_shipping_info', 2], 
        'propel_order_thank_you_payment_info' => ['OrderController', 'order_thank_you_payment_info', 2], 
        'propel_order_thank_you_items_title' => ['OrderController', 'order_thank_you_items_title', 2], 
        'propel_order_thank_you_items' => ['OrderController', 'order_thank_you_items', 2], 
        'propel_order_thank_you_summary_totals' => ['OrderController', 'order_thank_you_summary_totals', 2], 
        
    ];

    protected $loader;

    public function __construct()
    {
        if (defined('PROPELLER_VERSION')) 
            $this->version = PROPELLER_VERSION;
		else 
            $this->version = '1.0.0';
		
        $this->plugin_name = 'propeller_ecommerce';

        $this->loader = new PropellerLoader();

        $this->register_globals();
        
        $this->minify();

        if (is_admin())
            $this->admin_hooks();
        else 
            $this->public_hooks();
    }

    public function public_hooks()
    {
        $frontend = new PropellerFrontend($this->propeller, $this->version);
        $frontend_extend = new ExtendFrontend($this->propeller, $this->version);

        // check where to load CSS files from
        if ((int) method_exists($frontend_extend, 'styles') == 1)
            $this->loader->add_action('wp_enqueue_scripts', $frontend_extend, 'styles');
        else 
            $this->loader->add_action('wp_enqueue_scripts', $frontend, 'styles');

        // Check where to load JS files from
        if ((int) method_exists($frontend_extend, 'scripts') == 1)
            $this->loader->add_action('wp_enqueue_scripts', $frontend_extend, 'scripts');
        else
            $this->loader->add_action('wp_enqueue_scripts', $frontend, 'scripts');


        // register actions
        foreach ($this->fe_actions as $action_key => $action_val) {
            if (!is_array($action_val)) {
                $fe_class = (int) method_exists($frontend_extend, $action_val) == 1 ? $frontend_extend : $frontend;

                $this->loader->add_action($action_key, $fe_class, $action_val);
            }
            else {
                $fe_class = (int) method_exists($frontend_extend, $action_val[0]) == 1 ? $frontend_extend : $frontend;

                $this->loader->add_action($action_key, $fe_class, $action_val[0], $action_val[1]);
            }
        }

        // register shortcodes
        foreach (self::$fe_shortcodes as $shortcode_key => $shortcode_val) {
            $fe_class = (int) method_exists($frontend_extend, $shortcode_val) == 1 ? $frontend_extend : $frontend;

            $this->loader->add_shortcode($shortcode_key, $fe_class, $shortcode_val , 1000, 2);
        }

        // register filters
        $this->add_filters();
    }

    public function add_filters() {
        foreach (self::$fe_filters as $filter_key => $filter_val) {
            $default_ref = "Propeller\Includes\Controller\\$filter_val[0]";
            $custom_ref = "Propeller\Custom\Includes\Controller\\$filter_val[0]";

            $default_controller_obj = new $default_ref();
            $custom_controller_obj = class_exists($custom_ref, true) ? new $custom_ref() : null;                                

            $ref_class = $custom_controller_obj && (int) method_exists($custom_controller_obj, $filter_val[1]) == 1 ? $custom_controller_obj : $default_controller_obj;

            $this->loader->add_filter($filter_key, $ref_class, $filter_val[1], 1000, $filter_val[2]);
        }
    }

    public function reinit_filters() {
        $this->add_filters();

        $this->loader->run();
    }

    public function admin_hooks() {
        $_admin = new PropellerAdmin($this->propeller, $this->version);

        // add wp admin menu for Propeller
		$this->loader->add_action('admin_menu', $_admin, 'menu');

        // include admin scripts and styles
        if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'propeller') {
            $this->loader->add_action('admin_enqueue_scripts', $_admin, 'styles');
            $this->loader->add_action('admin_enqueue_scripts', $_admin, 'scripts');
        }
    }

    public function run() {
        // run the loader
        $this->loader->run();

        $this->after_run();
    }

    public function after_run() {
        if (defined('PROPELLER_WP_CLOSED_PORTAL') && PROPELLER_WP_CLOSED_PORTAL) {
            remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
            remove_action('template_redirect', 'redirect_canonical', 1000);
            add_action('template_redirect', [$this, 'check_access'], 1000);
        }
    }

    
    function remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin())
            show_admin_bar(false);
    }

    public function check_access() {
        global $wp;

        $login_page = get_page_by_path(PageController::get_slug(PageType::LOGIN_PAGE) , OBJECT);

        $exclusions = [];

        foreach (explode(',', PROPELLER_PAGE_EXCLUSIONS) as $ex)
            $exclusions[] = get_permalink($ex);

        if (isset($login_page)) {
            if (!UserController::is_logged_in() && 
                home_url($wp->request . '/') != get_permalink($login_page) && 
                !in_array(home_url($wp->request . '/'), $exclusions)) {

                header( 'Location: '. get_permalink($login_page) );

                exit;
            }
        }
    }

    function stop_redirect($scheme) {
        if ( $user_id = wp_validate_auth_cookie( '',  $scheme) ) {
            return $scheme;
        }

        global $wp_query;
        $wp_query->set_404();
        get_template_part( 404 );
        exit();
    }

    public function check_admin_bar() {
        $show = is_user_logged_in() && is_admin();
        show_admin_bar($show);
    }

    public function getPropeller() {
        return $this->propeller;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getLoader() {
        return $this->loader;
    }

    private function register_globals()
    {
        $this->register_pages();
        $this->register_settings();
        $this->register_behavior();
    }

    public static function register_pages() {
        global $wpdb, $propellerPages, $propellerSluggablePages;

        $pages_result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . PROPELLER_PAGES_TABLE);

        if ($pages_result) {
            $propellerPages = $pages_result;

            foreach ($pages_result as $index => $page) {
                if ($page->page_sluggable == 1)
                    $propellerSluggablePages[$page->page_type] = $page->page_slug;
            }
        }
    }

    public static function register_settings() {
        global $wpdb;
        
        if (!defined('PROPELLER_API_URL')) {
            $results = $wpdb->get_results( 
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . PROPELLER_SETTINGS_TABLE. " WHERE id = %d", 1) 
            );
    
            if (sizeof($results)) {
                if (!defined('PROPELLER_API_URL')) 
                    define('PROPELLER_API_URL', $results[0]->api_url);
    
                if (!defined('PROPELLER_API_KEY')) 
                    define('PROPELLER_API_KEY', $results[0]->api_key);
    
                if (!defined('PROPELLER_ANONYMOUS_USER')) 
                    define('PROPELLER_ANONYMOUS_USER', (int) $results[0]->anonymous_user);
    
                if (!defined('PROPELLER_SITE_ID')) 
                    define('PROPELLER_SITE_ID', (int) $results[0]->site_id);
    
                if (!defined('PROPELLER_BASE_CATALOG')) 
                    define('PROPELLER_BASE_CATALOG', (int) $results[0]->catalog_root);

                if (!defined('PROPELLER_DEFAULT_CONTACT_PARENT')) 
                    define('PROPELLER_DEFAULT_CONTACT_PARENT', (int) $results[0]->contact_root);

                if (!defined('PROPELLER_DEFAULT_CUSTOMER_PARENT')) 
                    define('PROPELLER_DEFAULT_CUSTOMER_PARENT', (int) $results[0]->customer_root);
            }
        }        
    }

    public static function register_behavior() {
        global $wpdb, $propel_behavior;

        if (!defined('PROPELLER_WP_SESSIONS')) {
            $results = $wpdb->get_results( 
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . PROPELLER_BEHAVIOR_TABLE. " WHERE id = %d", 1) 
            );
    
            if (sizeof($results)) {
                $propel_behavior = $results;
                
                if (!defined('PROPELLER_WP_SESSIONS')) 
                    define('PROPELLER_WP_SESSIONS', ((int) $results[0]->wordpress_session == 1 ? true : false));
    
                if (!defined('PROPELLER_WP_CLOSED_PORTAL')) 
                    define('PROPELLER_WP_CLOSED_PORTAL', ((int) $results[0]->closed_portal == 1 ? true : false));
                
                if (!defined('PROPELLER_PAGE_EXCLUSIONS')) 
                    define('PROPELLER_PAGE_EXCLUSIONS', $results[0]->excluded_pages);
    
                if (!defined('PROPELLER_USER_TRACK_ATTR')) 
                    define('PROPELLER_USER_TRACK_ATTR', $results[0]->track_user_attr);
                
                if (!defined('PROPELLER_PRODUCT_TRACK_ATTR')) 
                    define('PROPELLER_PRODUCT_TRACK_ATTR', $results[0]->track_product_attr);

                if (!defined('PROPELLER_RELOAD_FILTERS')) 
                    define('PROPELLER_RELOAD_FILTERS', $results[0]->reload_filters);
            }
        }        
    }

    function minify() {
        $js_path = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js';
		$css_path = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css';

        $min_js = $js_path . DIRECTORY_SEPARATOR . 'propel.min.js';
        $min_css = $css_path . DIRECTORY_SEPARATOR . 'propel.min.css';

        $js_order = [
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'popper.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jquery-validator' . DIRECTORY_SEPARATOR . 'jquery.validate.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jquery-validator' . DIRECTORY_SEPARATOR . 'additional-methods.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'plain-overlay.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nouislider.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'calendar.min.js',
            $js_path . DIRECTORY_SEPARATOR . 'propeller-frontend.js'
        ];
    
        $css_order = [
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jqueryui-1.13.0' . DIRECTORY_SEPARATOR . 'jquery-ui.min.css',
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.min.css',
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nouislider.min.css',
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick-theme.css',
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick.css',
            $css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'calendartheme.css',
            $css_path . DIRECTORY_SEPARATOR . 'propeller-frontend.css',
            $css_path . DIRECTORY_SEPARATOR . 'propeller-responsive.css',
        ];

        ResourceHandler::init();

        try {
            if (ResourceHandler::$javascript_modified) {
                $this->get_assets($js_path, 'js', $js_files);
				
				$js_minifier = new Minify\JS();

				foreach ($js_order as $js)
					$js_minifier->add($js);

				// minify js
				$js_minifier->minify($min_js);     
			}
		}
		catch (Exception $ex) {
            error_log($ex->getMessage());
        }
		
		try {
            if (ResourceHandler::$styles_modified) {    
                $this->get_assets($css_path, 'css', $css_files);

				$css_minifier = new Minify\CSS();

				foreach ($css_order as $css)
					$css_minifier->add($css);

				// minify css
				$css_minifier->minify($min_css);     
			}
		}
		catch (Exception $ex) {
            error_log($ex->getMessage());
        }

        if (ResourceHandler::$javascript_modified || ResourceHandler::$styles_modified)
            ResourceHandler::set_resource_last_modification();
	}

    private function get_assets($dir, $ext, &$results = array()) {
        if (is_dir($dir)) {
            $files = @scandir($dir);
	
            if (is_array($files)) {
                foreach ($files as $key => $value) {
                    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
        
                    if (!is_dir($path) && $extension == $ext) {
                        $results[] = $path;
                    } else if ($value != "." && $value != "..") {
                        $this->get_assets($path, $results, $ext);
                        if (!is_dir($path) && $extension == $ext)
                            $results[] = $path;
                    }
                }
            }
        }
		
		return $results;
	}
}