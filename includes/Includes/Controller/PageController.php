<?php
namespace Propeller\Includes\Controller;

class PageController extends BaseController {
    public function __construct() { }

    public static function create_pages() {
        global $wpdb, $propellerSluggablePages;

        $pages_result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . PROPELLER_PAGES_TABLE);

        $my_account_page_id = null;

        foreach ($pages_result as $index => $page) {
            if ($page->account_page_is_parent == 1)
                continue;

            if ($page->page_sluggable == 1)
                $propellerSluggablePages[$page->page_type] = $page->page_slug;

            // Check if the page already exists
            if(!self::pageExists($page->page_slug)) {
                $guid = site_url() . "/" . $page->page_slug . '/';

                $page_data  = array( 'post_title'     => $page->page_name . PROPELLER_PAGE_SUFFIX,
                                'post_type'      => 'page',
                                'post_name'      => $page->page_slug,
                                'post_content'   => "[$page->page_shortcode]",
                                'post_status'    => 'publish',
                                'comment_status' => 'closed',
                                'ping_status'    => 'closed',
                                'post_author'    => 1,
                                'menu_order'     => 0,
                                'guid'           => $guid);

                $page_id = wp_insert_post($page_data, FALSE);
                
                if ($page->is_my_account_page && !is_wp_error($page_id))
                    $my_account_page_id = $page_id;
            }
        }

        foreach ($pages_result as $page) {
            if ($page->account_page_is_parent == 0)
                continue;

            if ($page->page_sluggable == 1)
                $propellerSluggablePages[$page->page_type] = $page->page_slug;

            // Check if the page already exists
            if(!self::pageExists($page->page_slug)) {
                $guid = site_url() . "/" . $page->page_slug . '/';

                $page_data  = array( 'post_title'     => $page->page_name . PROPELLER_PAGE_SUFFIX,
                                'post_type'      => 'page',
                                'post_name'      => $page->page_slug,
                                'post_content'   => "[$page->page_shortcode]",
                                'post_status'    => 'publish',
                                'comment_status' => 'closed',
                                'ping_status'    => 'closed',
                                'post_author'    => 1,
                                'menu_order'     => 0,
                                'guid'           => $guid,
                                'posts_per_page'    => 1,
                                'no_found_rows'     => true);

                if ($page->account_page_is_parent == 1 && !empty($my_account_page_id))
                    $page_data['post_parent'] = $my_account_page_id;

                $page_id = wp_insert_post($page_data, FALSE);
            }
        }
    }

    private static function pageExists($page_slug) {
        $page = get_page_by_path( $page_slug , OBJECT );
   
        if (isset($page))
            return true;
            
        return false;
    }

    public static function get_slug($page_type) {
        $page = self::get_page($page_type);

        if (!empty($page))
            return $page->page_slug;

        return '';
    }

    private static function get_page($page_type) {
        global $propellerPages;

        if (is_array($propellerPages) && count($propellerPages)) {
            foreach ($propellerPages as $index => $page) {
                if ($page->page_type == $page_type)
                    return $page;
            }
        }

        return null;
    }

    public static function insert_default_pages() {
        global $table_prefix, $wpdb;

        $tbl_pages      = $table_prefix . PROPELLER_PAGES_TABLE;

        $pages_sql = "INSERT INTO `$tbl_pages` (`page_name`, `page_slug`, `page_sluggable`, `page_shortcode`, `page_type`, `is_my_account_page`, `account_page_is_parent`) VALUES ";
        
        $pages_queries = [];

        $pages_queries[] = "('Catalog listing', 'category', 1, 'product-listing', 'Category page', 0, 0)";
        $pages_queries[] = "('Product details', 'product', 1, 'product-details', 'Product page', 0, 0)";
        $pages_queries[] = "('Brands page', 'brand', 1, 'brand-listing', 'Brand page', 0, 0)";
        $pages_queries[] = "('Search', 'search', 1, 'product-search', 'Search page', 0, 0)";
        $pages_queries[] = "('My Account', 'my-account-details', 0, 'account-details', 'My account page', 1, 0)";
        $pages_queries[] = "('My Account Mobile', 'my-account', 0, 'account-mobile', 'My account mobile page', 1, 0)";
        $pages_queries[] = "('My Orders', 'my-orders', 0, 'account-orders', 'Orders page', 0, 1)";
        $pages_queries[] = "('My Order details', 'order-details', 0, 'account-order-details', 'Order details page', 0, 1)";
        $pages_queries[] = "('My Quotations', 'my-quotations', 0, 'account-quotations', 'Quotations page', 0, 1)";
        $pages_queries[] = "('My Quotation details', 'quote-details', 0, 'account-order-details', 'Quotation details page', 0, 1)";
        $pages_queries[] = "('My Addresses', 'my-addresses', 0, 'account-addresses', 'Addresses page', 0, 1)";
        // $pages_queries[] = "('My Favorites', 'my-favorites', 0, 'account-favorites', 'Favorites page', 0, 1)";
        // $pages_queries[] = "('My Ordelist', 'my-orderlist', 0, 'account-orderlist', 'Orderlist page', 0, 1)";
        // $pages_queries[] = "('My Invoices', 'my-invoices', 0, 'account-invoices', 'Invoices page', 0, 1)";
        $pages_queries[] = "('Register', 'register', 0, 'registration-form', 'Register page', 0, 0)";
        $pages_queries[] = "('Login', 'login', 0, 'login-form', 'Login page', 0, 0)";
        $pages_queries[] = "('Forgot password', 'forgot-password', 0, 'forgot-password-form', 'Forgot password page', 0, 0)";
        $pages_queries[] = "('Reset password', 'reset-password', 0, 'reset-password-form', 'Reset password page', 0, 0)";
        $pages_queries[] = "('Quick Order', 'quick-order', 0, 'quick-add-to-basket', 'Quick order page', 0, 0)";
        $pages_queries[] = "('Shopping Cart', 'shopping-cart', 0, 'shopping-cart', 'Shopping cart page', 0, 0)";
        $pages_queries[] = "('Checkout', 'checkout', 1, 'checkout', 'Checkout page', 0, 0)";
        $pages_queries[] = "('Checkout summary', 'checkout-summary', 1, 'checkout-summary', 'Checkout summary page', 0, 0)";
        $pages_queries[] = "('Thank you', 'thank-you', 0, 'checkout-thank-you', 'Thank you page', 0, 0)";
        $pages_queries[] = "('Payment failed', 'payment-failed', 0, 'payment-failed', 'Payment failed page', 0, 0)";
        $pages_queries[] = "('Terms and conditions page', 'terms-conditions', 0, 'menu', 'Terms & Conditions page', 0, 0)";
        $pages_queries[] = "('Machines', 'my-installations', 1, 'machines', 'Machines page', 0, 0)";

        $pages_sql .= implode(', ', $pages_queries);

        $wpdb->get_results($pages_sql);
    }
}