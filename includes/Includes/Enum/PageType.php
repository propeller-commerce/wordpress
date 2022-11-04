<?php
namespace Propeller\Includes\Enum;

use ReflectionClass;

class PageType {
    const HOMEPAGE = 'Home page';
    const CATEGORY_PAGE = 'Category page';
    const PRODUCT_PAGE = 'Product page';
    const SEARCH_PAGE = 'Search page';
    const BRAND_PAGE = 'Brand page';
    const SHOPPING_CART_PAGE = 'Shopping cart page';
    const CHECKOUT_PAGE = 'Checkout page';
    const CHECKOUT_SUMMARY_PAGE = 'Checkout summary page';
    const CHECKOUT_THANK_YOU_PAGE = 'Checkout thank you page';
    const FAVORITES_PAGE = 'Favorites page';
    const INVOICES_PAGE = 'Invoices page';
    const ORDERLIST_PAGE = 'Orderlist page';
    const QUOTATIONS_PAGE = 'Quotations page';
    const QUOTATION_DETAILS_PAGE = 'Quotation details page';
    const ORDERS_PAGE = 'Orders page';
    const ORDER_DETAILS_PAGE = 'Order details page';
    const ADDRESSES_PAGE = 'Addresses page';
    const QUICK_ORDER_PAGE = 'Quick order page';
    const MY_ACCOUNT_PAGE = 'My account page';
    const MY_ACCOUNT_MOBILE_PAGE = 'My account mobile page';
    const ACCOUNT_DETAILS_PAGE = 'Account details page';
    const LOGIN_PAGE = 'Login page';
    const REGISTER_PAGE = 'Register page';
    const FORGOT_PASSWORD_PAGE = 'Forgot password page';
    const RESET_PASSWORD_PAGE = 'Reset password page';
    const THANK_YOU_PAGE = 'Thank you page';
    const PAYMENT_FAILED_PAGE = 'Payment failed page';
    const TERMS_CONDITIONS_PAGE = 'Terms & Conditions page';
    
    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }
}