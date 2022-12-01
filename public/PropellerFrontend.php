<?php
    namespace Propeller\Frontend;

use Propeller\Includes\Controller\AddressController;
use Propeller\Includes\Controller\BaseController;
use Propeller\Includes\Controller\CategoryController;
use Propeller\Includes\Controller\HomepageController;
use Propeller\Includes\Controller\LanguageController;
use Propeller\Includes\Controller\MenuController;
use Propeller\Includes\Controller\OrderController;
use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\ProductController;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\ShoppingCartController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\Meta\MetaController as MetaMetaController;
use Propeller\RequestHandler;

global $title, $description;

class PropellerFrontend {
    protected $propeller;
    protected $version;

    protected $assets_url;
    protected $assets_dir;

    protected $request_handler;
    protected $meta;

    public function __construct($propeller, $version) {
        $this->propeller = $propeller;
        $this->version = $version;  

        $this->assets_url = plugins_url('assets', __FILE__ );
        $this->assets_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';

        $this->get_assets_folder();

        $this->templates_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates';
        $this->partials_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'partials';
        $this->emails_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'email';

        $this->min_js_path = $this->assets_dir . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'propel.min.js';
        $this->min_css_path = $this->assets_dir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'propel.min.css';

        $this->min_js = plugins_url('assets/js/propel.min.js', __FILE__ );
        $this->min_css = plugins_url('assets/css/propel.min.css', __FILE__ );

        $this->request_handler = new RequestHandler();

        add_action('wp_footer', [$this, 'wp_footer_scripts']);

        add_filter('wpseo_title', [$this, 'set_title'], 1, 1);
        add_filter('pre_get_document_title', [$this, 'set_title'], 1, 1);
        add_action('parse_request', [$this, 'parse_request'], 1, 1);
    }

    private function get_assets_folder() {
        $this->assets_url = plugins_url('assets', __FILE__ );

        if (is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets')) {
            $this->assets_url = PROPELLER_PLUGIN_EXTEND_URL . '/public/assets';
            $this->assets_dir = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets';
        }
    }

    public function scripts() {
        if (!$this->assets_url)
            $this->get_assets_folder();

        wp_enqueue_script('jquery-ui-autocomplete');
        
        if (defined('PROPELLER_DEBUG') && PROPELLER_DEBUG) {
            wp_enqueue_script('popper', $this->assets_url . '/js/lib/popper.min.js', array(), $this->version, true);
            wp_enqueue_script('bootstrap', $this->assets_url . '/js/lib/bootstrap.min.js', array(), $this->version, true);
            wp_enqueue_script('validate', $this->assets_url . '/js/lib/jquery-validator/jquery.validate.js', array(), $this->version, true);
            wp_enqueue_script('additional-methods', $this->assets_url . '/js/lib/jquery-validator/additional-methods.js', array(), $this->version, true);
            wp_enqueue_script('overlay', $this->assets_url . '/js/lib/plain-overlay.min.js', array(), $this->version, true);
            wp_enqueue_script('nouislider', $this->assets_url . '/js/lib/nouislider.min.js', array(), $this->version, true);
            wp_enqueue_script('slick', $this->assets_url . '/js/lib/slick.min.js', array(), $this->version, true);
            wp_enqueue_script('photoswipe', $this->assets_url . '/js/lib/photoswipe.min.js', array(), $this->version, true);
        }
                
        $helper_js_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'propel-helper.js';

        $this->build_js_helper();
        
        if (file_exists($helper_js_path)) {
            wp_enqueue_script('propeller_helper_js', plugins_url('assets', __FILE__ ) . '/js/propel-helper.js', array(), $this->version, true);
            wp_enqueue_script('propeller_helper_js');
        }

        if (defined('PROPELLER_DEBUG') && PROPELLER_DEBUG)
            wp_register_script('propeller_js', $this->assets_url . '/js/propeller-frontend.js', array('jquery', 'wp-i18n'), $this->version, true);
        else 
            // The minified js file
            wp_register_script('propeller_js', $this->assets_url . '/js/propel.min.js', array('jquery', 'wp-i18n'), $this->version, true);
        
        wp_enqueue_script('propeller_js');

        wp_set_script_translations('propeller_js', 'propeller-ecommerce', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR);

        wp_localize_script('propeller_js', 'propeller_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
    }

    public function styles() {
        if (!$this->assets_url)
            $this->get_assets_folder();

        wp_enqueue_style( 'propeller_css', $this->assets_url . '/css/propel.min.css', array(), $this->version, 'all');
    }

    public function parse_request($wp) {
        $this->request_handler->process($wp->query_vars);

        // used for meta tags, hreflang, etc
        $this->meta = new MetaMetaController();
    }

    public function init_propeller() {
        SessionController::start();
        
        set_propel_locale();

        // Preserve the language in session, will be needed later for the ajax calls
        SessionController::set(PROPELLER_SESSION_LANG, PROPELLER_LANG);

        new LanguageController();
    }

    public function set_title($page_title) {
        global $propel;

        if (isset($propel['title']))
            $page_title = $propel['title'];

        $page_title = str_replace('', PROPELLER_PAGE_SUFFIX, $page_title);

        return $page_title;
    }

    public function wp_head_script(){

    }

    public function wp_footer_scripts(){
        $bc = new BaseController();

        require $bc->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-popup.php');
        
        require $bc->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-pre-basket-popup.php');

        require $bc->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-toast.php');
    }

    public function handle_404() {
        global $wp_query, $propel;

        $bc = new BaseController();

        // echo '<pre>';
        // var_dump($wp_query);
        // var_dump(get_query_template( '404' ));
        // var_dump(get_query_template( '403' ));
        // var_dump($bc->load_template('error', DIRECTORY_SEPARATOR . '403.php'));
        // echo '</pre>';

        // require $bc->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-popup.php');

        if (isset($propel['error_404'])) {
            status_header(404);
            nocache_headers();
            include('' !== get_query_template( '404' ) ? get_query_template('404') : $bc->load_template('error', DIRECTORY_SEPARATOR . '404.php'));
            die();
        }

        // if (isset($propel['error_403'])) {
        //     // $wp_query->set_404();
        //     status_header(403);
        //     nocache_headers();
        //     include('' !== get_query_template( '403' ) ? get_query_template('403') : $bc->load_template('error', DIRECTORY_SEPARATOR . '403.php'));
        //     die();
        // }
    }

    function propel_error_pages() {
        global $wp_query, $propel;

        $bc = new BaseController();
    
        if (isset($propel['error_403'])) {
            $wp_query->is_404 = FALSE;
            $wp_query->is_page = TRUE;
            $wp_query->is_singular = TRUE;
            $wp_query->is_single = FALSE;
            $wp_query->is_home = FALSE;
            $wp_query->is_archive = FALSE;
            $wp_query->is_category = FALSE;
            add_filter('wp_title', [$this, 'propel_error_title'], 65000, 2);
            add_filter('body_class', [$this, 'propel_error_class']);
            status_header(403);
            nocache_headers();

            include('' !== get_query_template( '403' ) ? get_query_template('403') : $bc->load_template('error', DIRECTORY_SEPARATOR . '403.php'));

            exit;
        }
    
        if (isset($propel['error_401'])) {
            $wp_query->is_404 = FALSE;
            $wp_query->is_page = TRUE;
            $wp_query->is_singular = TRUE;
            $wp_query->is_single = FALSE;
            $wp_query->is_home = FALSE;
            $wp_query->is_archive = FALSE;
            $wp_query->is_category = FALSE;
            add_filter('wp_title', [$this, 'propel_error_title'], 65000, 2);
            add_filter('body_class', [$this, 'propel_error_class']);
            status_header(401);
            nocache_headers();

            include('' !== get_query_template( '401' ) ? get_query_template('401') : $bc->load_template('error', DIRECTORY_SEPARATOR . '401.php'));

            exit;
        }
    }
    
    function propel_error_title($title='', $sep='') {
        if (isset($propel['error_403']))
            return "Forbidden " . $sep . " " . get_bloginfo('name');
    
        if (isset($propel['error_401']))
            return "Unauthorized " . $sep . " " . get_bloginfo('name');
    }
    
    function propel_error_class($classes) {
        if (isset($propel['error_403'])) {
            $classes[]="propel-error403";
            return $classes;
        }
    
        if (isset($propel['error_401'])) {
            $classes[]="propel-error401";
            return $classes;
        }
    }
    
    public function get_slugs() {
        $page_slugs = [];

        $page_slugs['home']             = PageController::get_slug(PageType::HOMEPAGE);
        $page_slugs['category']         = PageController::get_slug(PageType::CATEGORY_PAGE);
        $page_slugs['product']          = PageController::get_slug(PageType::PRODUCT_PAGE);
        $page_slugs['search']           = PageController::get_slug(PageType::SEARCH_PAGE);
        $page_slugs['brand']            = PageController::get_slug(PageType::BRAND_PAGE);
        $page_slugs['cart']             = PageController::get_slug(PageType::SHOPPING_CART_PAGE);
        $page_slugs['checkout']         = PageController::get_slug(PageType::CHECKOUT_PAGE);
        $page_slugs['checkout_summary'] = PageController::get_slug(PageType::CHECKOUT_SUMMARY_PAGE);
        $page_slugs['thank_you']        = PageController::get_slug(PageType::CHECKOUT_THANK_YOU_PAGE);
        $page_slugs['favorites']        = PageController::get_slug(PageType::FAVORITES_PAGE);
        $page_slugs['incentives']       = PageController::get_slug(PageType::INVOICES_PAGE);
        $page_slugs['orderlist']        = PageController::get_slug(PageType::ORDERLIST_PAGE);
        $page_slugs['quotations']       = PageController::get_slug(PageType::QUOTATIONS_PAGE);
        $page_slugs['quote_details']    = PageController::get_slug(PageType::QUOTATION_DETAILS_PAGE);
        $page_slugs['orders']           = PageController::get_slug(PageType::ORDERS_PAGE);
        $page_slugs['order_details']    = PageController::get_slug(PageType::ORDER_DETAILS_PAGE);
        $page_slugs['addresses']        = PageController::get_slug(PageType::ADDRESSES_PAGE);
        $page_slugs['quick_order']      = PageController::get_slug(PageType::QUICK_ORDER_PAGE);
        $page_slugs['my_account']       = PageController::get_slug(PageType::MY_ACCOUNT_PAGE);
        $page_slugs['account_details']  = PageController::get_slug(PageType::ACCOUNT_DETAILS_PAGE);
        $page_slugs['account_mobile']  = PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE);
        $page_slugs['login']            = PageController::get_slug(PageType::LOGIN_PAGE);
        $page_slugs['register']         = PageController::get_slug(PageType::REGISTER_PAGE);
        $page_slugs['forgot_password']  = PageController::get_slug(PageType::REGISTER_PAGE);
        $page_slugs['reset_password']   = PageController::get_slug(PageType::REGISTER_PAGE);

        return $page_slugs;
    }

    public function get_urls() {
        $page_urls = [];

        $page_urls['home']             = home_url(PageController::get_slug(PageType::HOMEPAGE) . '/');
        $page_urls['category']         = home_url(PageController::get_slug(PageType::CATEGORY_PAGE) . '/');
        $page_urls['product']          = home_url(PageController::get_slug(PageType::PRODUCT_PAGE) . '/');
        $page_urls['search']           = home_url(PageController::get_slug(PageType::SEARCH_PAGE) . '/');
        $page_urls['brand']            = home_url(PageController::get_slug(PageType::BRAND_PAGE) . '/');
        $page_urls['cart']             = home_url(PageController::get_slug(PageType::SHOPPING_CART_PAGE) . '/');
        $page_urls['checkout']         = home_url(PageController::get_slug(PageType::CHECKOUT_PAGE) . '/');
        $page_urls['checkout_summary'] = home_url(PageController::get_slug(PageType::CHECKOUT_SUMMARY_PAGE) . '/');
        $page_urls['thank_you']        = home_url(PageController::get_slug(PageType::CHECKOUT_THANK_YOU_PAGE) . '/');
        $page_urls['payment_failed']   = home_url(PageController::get_slug(PageType::PAYMENT_FAILED_PAGE) . '/');
        $page_urls['favorites']        = home_url(PageController::get_slug(PageType::FAVORITES_PAGE) . '/');
        $page_urls['incentives']       = home_url(PageController::get_slug(PageType::INVOICES_PAGE) . '/');
        $page_urls['orderlist']        = home_url(PageController::get_slug(PageType::ORDERLIST_PAGE) . '/');
        $page_urls['quotations']       = home_url(PageController::get_slug(PageType::QUOTATIONS_PAGE) . '/');
        $page_urls['quote_details']    = home_url(PageController::get_slug(PageType::QUOTATION_DETAILS_PAGE) . '/');
        $page_urls['orders']           = home_url(PageController::get_slug(PageType::ORDERS_PAGE) . '/');
        $page_urls['order_details']    = home_url(PageController::get_slug(PageType::ORDER_DETAILS_PAGE) . '/');
        $page_urls['addresses']        = home_url(PageController::get_slug(PageType::ADDRESSES_PAGE) . '/');
        $page_urls['quick_order']      = home_url(PageController::get_slug(PageType::QUICK_ORDER_PAGE) . '/');
        $page_urls['my_account']       = home_url(PageController::get_slug(PageType::MY_ACCOUNT_PAGE) . '/');
        $page_urls['account_details']  = home_url(PageController::get_slug(PageType::ACCOUNT_DETAILS_PAGE) . '/');
        $page_urls['login']            = home_url(PageController::get_slug(PageType::LOGIN_PAGE) . '/');
        $page_urls['register']         = home_url(PageController::get_slug(PageType::REGISTER_PAGE) . '/');
        $page_urls['forgot_password']  = home_url(PageController::get_slug(PageType::REGISTER_PAGE) . '/');
        $page_urls['reset_password']   = home_url(PageController::get_slug(PageType::REGISTER_PAGE) . '/');

        return $page_urls;
    }

    public function get_behavior() {
        $behaviors = [];

        $behaviors['reload_filters'] = PROPELLER_RELOAD_FILTERS;

        return $behaviors;
    }

    public function build_js_helper() {
        $helper_js_path = PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'propel-helper.js';
        
        if (!file_exists($helper_js_path))
            return;

        $slugs = $this->get_slugs();
        $urls = $this->get_urls();
        $behaviors = $this->get_behavior();

        $js_content = "'use strict';\r\n";
        $js_content .= "window.PropellerHelper || (window.PropellerHelper = {});\r\n";
        
        $js_content .= "PropellerHelper.slugs = [];\r\n";
        $js_content .= "PropellerHelper.urls = [];\r\n";
        $js_content .= "PropellerHelper.behavior = [];\r\n";
        $js_content .= "\r\n\r\n";

        foreach ($slugs as $name => $slug)
            $js_content .= "PropellerHelper.slugs.$name = '$slug';\r\n";

        $js_content .= "\r\n";  
        foreach ($urls as $name => $url)
            $js_content .= "PropellerHelper.urls.$name = '$url';\r\n";
            
        $js_content .= "\r\n\r\n";

        foreach ($behaviors as $name => $behavior)
            $js_content .= "PropellerHelper.behavior.$name = $behavior;\r\n";

        $js_content .= "\r\n\r\n";

        $js_content .= "PropellerHelper.no_image = '$this->assets_url/img/no-image.webp';\r\n";
        
        $js_helper_file = fopen($helper_js_path, "w");
        
        fwrite($js_helper_file, $js_content);
        fclose($js_helper_file);
    }

    /**
     * Propeller shortcodes
     * 
     */
    public function draw_menu() {
        $menuController = new MenuController();

        return $menuController->draw_menu();
    }

    public function home_page(){
        $homePageController = new HomepageController();

        return $homePageController->home_page();
    }

    public function product_listing($applied_filters = [], $is_ajax = false) {
        $categoryController = new CategoryController();

        $content = $categoryController->product_listing($applied_filters, $is_ajax);

        return $content;
    }

    public function product_details() {
        $productController = new ProductController();

        return $productController->product_details();
    }

    public function product_slider($atts = [], $content = null) {
        $productController = new ProductController();

        return $productController->product_slider($atts, $content);
    }

    public function search_products() {
        $productController = new ProductController();

        return $productController->search_products();
    }

    public function search() {
        $productController = new ProductController();

        $_REQUEST['term'] = get_query_var('term');

        return $productController->search($_REQUEST, false);
    }

    public function brand_listing() {
        $productController = new ProductController();

        $_REQUEST['manufacturer'] = get_query_var('manufacturer');

        return $productController->brand($_REQUEST, false);
    }

    public function brand_listing_content() {
        $productController = new ProductController();

        return $productController->brand_listing_content();
    }
    
    public function quick_add_to_basket() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->quick_add_to_basket();
    }

    public function shopping_cart() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->shopping_cart();
    }

    public function checkout() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->checkout();
    }

    public function checkout_summary() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->checkout_summary();
    }

    public function checkout_thank_you() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->checkout_thank_you();
    }

    public function mini_shopping_cart() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->mini_shopping_cart();
    }

    public function mini_checkout_cart() {
        $shoppingCartController = new ShoppingCartController();

        return $shoppingCartController->mini_checkout_cart();
    }

    public function quotations() {
        $orderController = new OrderController();

        return $orderController->quotations();
    }

    public function orders() {
        $orderController = new OrderController();

        return $orderController->orders();
    }

    public function order_details() {
        $orderController = new OrderController();

        return $orderController->order_details();
    }

    public function account_favorites() {
        $userController = new UserController();

        return $userController->account_favorites();
    }

    public function account_orderlist() {
        $userController = new UserController();

        return $userController->account_orderlist();
    }

    public function account_invoices() {
        $userController = new UserController();

        return $userController->account_invoices();
    }

    public function addresses() {
        $addressController = new AddressController();

        return $addressController->addresses();
    }

    public function account_prices() {
        $userController = new UserController();

        return $userController->account_prices();
    }

    public function mini_account() {
        $userController = new UserController();

        return $userController->mini_account();
    }

    public function my_account() {
        $userController = new UserController();

        return $userController->my_account();
    }

    public function account_mobile() {
        $userController = new UserController();

        return $userController->account_mobile();
    }

    public function account_details() {
        $userController = new UserController();

        return $userController->account_details();
    }

    public function newsletter_subscription() {
        $userController = new UserController();

        return $userController->newsletter_subscription();
    }

    public function login_form() {
        $userController = new UserController();

        return $userController->login_form();
    }

    public function forgot_password_form() {
        $userController = new UserController();

        return $userController->forgot_password_form();
    }

    public function reset_password_form() {
        $userController = new UserController();

        return $userController->reset_password_form();
    }

    public function register_form() {
        $userController = new UserController();

        return $userController->register_form();
    }
}