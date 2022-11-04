<?php

    /**
     * 
     * Create behavior when installing/updating the plugin. We do need to preserve
     * the "Custom" folder in any case for custom approach and extending the Propeller
     * API in that folder by third parties. Wordpress by default deletes the whole
     * plugin directory, but with this filter we are just updating/overwritting the core files
     * of the plugin.
     * 
     */
    function avoid_propeller_deletion($options) {
        
        if(isset($options['hook_extra']['plugin']) && $options['hook_extra']['plugin'] == basename(__DIR__) . '/propeller-ecommerce.php' && $options['hook_extra']['type'] == 'plugin') {
            // preserve plugin dir if update
            if($options['hook_extra']['plugin'] == basename(__DIR__) . '/propeller-ecommerce.php') {
                $options["clear_destination"] = false;
                $options["abort_if_destination_exists"] = false;
            }
    
            // preserve plugin dir if install
            if($options['hook_extra']['type'] == 'plugin' && $options['hook_extra']['action'] == 'install' 
                && strpos($options['package'], 'propeller-ecommerce') !== false) {
                $options["clear_destination"] = false;
                $options["abort_if_destination_exists"] = false;
            }
        }

        return $options;
    }

    add_filter('upgrader_package_options', 'avoid_propeller_deletion', 999, 1);


    function load_propel_textdomain() {
        $plugin_rel_path = dirname(plugin_basename( __FILE__ )) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;

        if (is_dir(dirname( __FILE__ ) .DIRECTORY_SEPARATOR .'custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR))
            $plugin_rel_path = dirname(plugin_basename( __FILE__ )) . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;

        load_plugin_textdomain('propeller-ecommerce', false, $plugin_rel_path);
    }

    add_action('init', 'load_propel_textdomain');


    function set_propel_locale() {
        $locale = get_locale();
        // $locale = 'nl_NL';
        if (strpos($locale, '_')) 
            $locale = explode('_', $locale)[0];
        
        define('PROPELLER_LANG', strtoupper($locale));
    }

    
    function print_backtrace() {
        echo '<pre>';
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo '</pre>';
    }