<?php

namespace Propeller\Ajax;

use Propeller\Includes\Controller\ShoppingCartAjaxController;

$ref = 'Propeller\Custom\Includes\Controller\ShoppingCartAjaxController';

$AjaxShoppingCart = class_exists($ref, true) 
                    ? new $ref()
                    : new ShoppingCartAjaxController();

// add_filter('query_vars', 'cart_query_vars');

add_action('wp_ajax_cart_add_item', array($AjaxShoppingCart, 'cart_add_item'));
add_action('wp_ajax_nopriv_cart_add_item', array($AjaxShoppingCart, 'cart_add_item'));

add_action('wp_ajax_cart_add_bundle', array($AjaxShoppingCart, 'cart_add_bundle'));
add_action('wp_ajax_nopriv_cart_add_bundle', array($AjaxShoppingCart, 'cart_add_bundle'));

add_action('wp_ajax_cart_update_item', array($AjaxShoppingCart, 'cart_update_item'));
add_action('wp_ajax_nopriv_cart_update_item', array($AjaxShoppingCart, 'cart_update_item'));

add_action('wp_ajax_cart_delete_item', array($AjaxShoppingCart, 'cart_delete_item'));
add_action('wp_ajax_nopriv_cart_delete_item', array($AjaxShoppingCart, 'cart_delete_item'));

add_action('wp_ajax_cart_add_action_code', array($AjaxShoppingCart, 'cart_add_action_code'));
add_action('wp_ajax_nopriv_cart_add_action_code', array($AjaxShoppingCart, 'cart_add_action_code'));

add_action('wp_ajax_cart_remove_action_code', array($AjaxShoppingCart, 'cart_remove_action_code'));
add_action('wp_ajax_nopriv_cart_remove_action_code', array($AjaxShoppingCart, 'cart_remove_action_code'));

add_action('wp_ajax_cart_update_address', array($AjaxShoppingCart, 'cart_update_address'));
add_action('wp_ajax_nopriv_cart_update_address', array($AjaxShoppingCart, 'cart_update_address'));

add_action('wp_ajax_cart_change_order_type', array($AjaxShoppingCart, 'cart_change_order_type'));
add_action('wp_ajax_nopriv_cart_change_order_type', array($AjaxShoppingCart, 'cart_change_order_type'));

add_action('wp_ajax_cart_process', array($AjaxShoppingCart, 'cart_process'));
add_action('wp_ajax_nopriv_cart_process', array($AjaxShoppingCart, 'cart_process'));

add_action('wp_ajax_do_replenish', array($AjaxShoppingCart, 'do_replenish'));
add_action('wp_ajax_nopriv_do_replenish', array($AjaxShoppingCart, 'do_replenish'));

add_action('wp_ajax_cart_update', array($AjaxShoppingCart, 'cart_update'));
add_action('wp_ajax_nopriv_cart_update', array($AjaxShoppingCart, 'cart_update'));

add_action('wp_ajax_cart_step_1', array($AjaxShoppingCart, 'cart_step_1'));
add_action('wp_ajax_nopriv_cart_step_1', array($AjaxShoppingCart, 'cart_step_1'));

add_action('wp_ajax_cart_step_2', array($AjaxShoppingCart, 'cart_step_2'));
add_action('wp_ajax_nopriv_cart_step_2', array($AjaxShoppingCart, 'cart_step_2'));

add_action('wp_ajax_cart_step_3', array($AjaxShoppingCart, 'cart_step_3'));
add_action('wp_ajax_nopriv_cart_step_3', array($AjaxShoppingCart, 'cart_step_3'));

add_action('wp_ajax_cart_change_order_status', array($AjaxShoppingCart, 'cart_change_order_status'));
add_action('wp_ajax_nopriv_cart_change_order_status', array($AjaxShoppingCart, 'cart_change_order_status'));

function cart_query_vars($qvars) {
    $qvars[] = 'product_id';
    $qvars[] = 'bundle_id';
    $qvars[] = 'action';
    $qvars[] = 'quantity';

    return $qvars;
}