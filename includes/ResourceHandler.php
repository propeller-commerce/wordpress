<?php
namespace Propeller;

use Exception;

class ResourceHandler {
    public static $javascript_modified = false;
    public static $styles_modified = false;

    private static $js_path;
    private static $css_path;

    public static function init() {
        self::$js_path = is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js') 
            ? PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js'
            : PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js';

        self::$css_path = is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css') 
            ? PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css'
            : PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css';

        self::get_status();
    }

    private static function get_status() {
        try {
            $original_js_file = self::$js_path . DIRECTORY_SEPARATOR . 'propeller-frontend.js';
            $original_css_file = self::$css_path . DIRECTORY_SEPARATOR . 'propeller-frontend.css';

            if (file_exists($original_js_file) && file_exists($original_css_file)) {
                $last_mod = [0, 0];
                $file_mod = [filemtime($original_js_file), filemtime($original_css_file)];
                
                if (isset($_COOKIE[PROPELLER_RESOURCE_LAST_MOD]))
                    $last_mod = explode(',', $_COOKIE[PROPELLER_RESOURCE_LAST_MOD]);
        
                if ((int)$last_mod[0] != $file_mod[0])
                    self::$javascript_modified = true;
        
                if ((int)$last_mod[1] != $file_mod[1])
                    self::$styles_modified = true;
            }
        }
        catch (Exception $ex) {
            error_log($ex->getMessage());
        }        
    }

    // track changes in propeller-frontend js/css files for later minimization of the FE code
    public static function set_resource_last_modification() {        
        try {
            $js_file = self::$js_path . DIRECTORY_SEPARATOR . 'propeller-frontend.js';
            $css_file = self::$css_path . DIRECTORY_SEPARATOR . 'propeller-frontend.css';

            if (file_exists($js_file) && file_exists($css_file)) {
                if (isset($_COOKIE[PROPELLER_RESOURCE_LAST_MOD])) {
                    $new_vals = [];
                    $changed = false;
    
                    $cookie_vals = explode(',', $_COOKIE[PROPELLER_RESOURCE_LAST_MOD]);
                    $file_vals = [filemtime($js_file), filemtime($css_file)];
    
                    if ((int)$cookie_vals[0] != $file_vals[0] || (int)$cookie_vals[1] != $file_vals[1]) {
                        $new_vals = $file_vals;
                        $changed = true;
                    }
    
                    if ($changed) {
                        setcookie(PROPELLER_RESOURCE_LAST_MOD, implode(',', $new_vals), [
                            'expires' => PROPELLER_COOKIE_EXPIRATION,
                            'path' => '/', 
                            'domain' => $_SERVER['HTTP_HOST'],
                            'secure' => true, 
                            'httponly' => true
                        ]);
                    }
                }
                else {
                    $new_vals = implode(',', [filemtime($js_file), filemtime($css_file)]);
    
                    setcookie(PROPELLER_RESOURCE_LAST_MOD, $new_vals, [
                        'expires' => PROPELLER_COOKIE_EXPIRATION,
                        'path' => '/', 
                        'domain' => $_SERVER['HTTP_HOST'],
                        'secure' => true, 
                        'httponly' => true
                    ]);
                }  
            }
        }
        catch (Exception $ex) {
            error_log($ex->getMessage());
        } 
    }
}