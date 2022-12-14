<?php

/**
* Plugin Name: Propeller E-Commerce
* Plugin URI: http://tepperefka.local/propeller-ecommerce.zip
* Description: Hook for using the Propeller API in WP.
* Version: 0.0.1
* Author: Propeller
* Author URI: http://propel.us
* Text Domain: propeller-ecommerce
* Domain Path: /Custom/languages
* License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'ABSPATH' ) || exit;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

require_once(plugin_dir_path(__FILE__) . '/upgrade.php');

// wp i18n make-pot . public/languages/propeller-ecommerce.pot

global $propel_active, 
	   $propellerSluggablePages,
	   $propel_behavior,
	   $propel_slugs,
	   $propel,
	   $start_time,
	   $end_time,
	   $gql_query;

$active_plugins = (array) get_option('active_plugins', array());
$propel_active = !empty($active_plugins) && in_array(basename(__DIR__) . '/propeller-ecommerce.php', $active_plugins);
	
require_once(plugin_dir_path(__FILE__) . '/constants.php');

ini_set('display_errors', E_ALL);
ini_set('display_startup_errors', E_ALL);
error_reporting(E_ALL);

function activate_propeller() {
	Propeller\PropellerActivate::activate();

	Propeller\PropellerInstall::install();
}

function deactivate_propeller() {
	Propeller\PropellerDeactivate::deactivate();
}

function uninstall_propeller() {
	// require_once plugin_dir_path( __FILE__ ) . 'includes/class-propeller-deactivator.php';
	// Propeller_Deactivator::deactivate();
    // echo "uninstalling propeller";
}

register_activation_hook(__FILE__, 'activate_propeller');
register_deactivation_hook(__FILE__, 'deactivate_propeller');
register_uninstall_hook(__FILE__, 'uninstall_propeller');

function run_propeller() {
	global $propel_active;
	
	if ($propel_active) {
		$propeller = new Propeller\Propeller();
	
		$propeller->run();
	}
}

if ($propel_active) { 
	require_once(plugin_dir_path(__FILE__) . '/rewrite-rules.php');
	require_once(plugin_dir_path(__FILE__) . '/ajax.php');
	require_once(plugin_dir_path(__FILE__) . '/filters.php');
}

run_propeller();