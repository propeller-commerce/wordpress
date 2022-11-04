<?php

$ref = 'Propeller\Custom\Includes\Controller\OrderAjaxController';

$AjaxOrder = class_exists($ref, true) 
                    ? new $ref()
                    : new Propeller\Includes\Controller\OrderAjaxController();

add_action('wp_ajax_change_order_status', array($AjaxOrder, 'change_order_status'));
add_action('wp_ajax_nopriv_change_order_status', array($AjaxOrder, 'change_order_status'));

add_action('wp_ajax_get_orders', array($AjaxOrder, 'get_orders'));
add_action('wp_ajax_nopriv_get_orders', array($AjaxOrder, 'get_orders'));

add_action('wp_ajax_get_quotes', array($AjaxOrder, 'get_quotes'));
add_action('wp_ajax_nopriv_get_quotes', array($AjaxOrder, 'get_quotes'));

add_action('wp_ajax_return_request', array($AjaxOrder, 'return_request'));
add_action('wp_ajax_nopriv_return_request', array($AjaxOrder, 'return_request'));