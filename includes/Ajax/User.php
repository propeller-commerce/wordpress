<?php

$ref = 'Propeller\Custom\Includes\Controller\UserAjaxController';

$AjaxUser = class_exists($ref, true) 
                    ? new $ref()
                    : new Propeller\Includes\Controller\UserAjaxController();

add_filter('query_vars', 'user_query_vars');

add_action('wp_ajax_do_login', array($AjaxUser, 'do_login'));
add_action('wp_ajax_nopriv_do_login', array($AjaxUser, 'do_login'));

add_action('wp_ajax_do_register', array($AjaxUser, 'do_register'));
add_action('wp_ajax_nopriv_do_register', array($AjaxUser, 'do_register'));

add_action('wp_ajax_user_prices', array($AjaxUser, 'user_prices'));
add_action('wp_ajax_nopriv_user_prices', array($AjaxUser, 'user_prices'));

add_action('wp_ajax_forgot_password', array($AjaxUser, 'forgot_password'));
add_action('wp_ajax_nopriv_forgot_password', array($AjaxUser, 'forgot_password'));

function user_query_vars($qvars) {
    $qvars[] = 'action';
    
    return $qvars;
}