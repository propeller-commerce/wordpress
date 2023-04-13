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
        
        if (isset($options['hook_extra']['plugin']) && $options['hook_extra']['plugin'] == basename(__DIR__) . '/propeller-ecommerce.php' && $options['hook_extra']['type'] == 'plugin') {
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
        $plugin_rel_path = dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;
        $textdomain_loaded = load_plugin_textdomain('propeller-ecommerce', false, $plugin_rel_path);
    }

    if (!defined( 'PROPELLER_PLUGIN_EXTEND_DIR' ))
        add_action('init', 'load_propel_textdomain');

    function set_propel_locale() {
        $locale = get_locale();
        // $locale = 'nl_NL';
        if (strpos($locale, '_')) 
            $locale = explode('_', $locale)[0];
        
        define('PROPELLER_LANG', strtoupper($locale));
    }

    function get_propel_languages() {
        $plugin_langs_path = plugin_dir_path(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;
        
        if ( defined( 'PROPELLER_PLUGIN_EXTEND_DIR' ) && is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'languages')) {
			$plugin_langs_path = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;
        }

        $langs = get_available_languages($plugin_langs_path);

        $available_langs = [ PROPELLER_DEFAULT_LOCALE ];

        foreach ($langs as $mo) {
            $chunks = explode('-', $mo);

            $available_langs[] = $chunks[count($chunks) - 1];
        }

        return $available_langs;
    }

    add_filter('trp_disable_error_manager','__return_true');

    function propel_log($msg) {
        $date = '[' . date('Y-m-d H:i:s') . ']';

        @error_log($date . $msg . "\r\n", 3, PROPELLER_ERROR_LOG);
    }
