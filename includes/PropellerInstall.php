<?php

namespace Propeller;

class PropellerInstall {

    public static function install() {
        self::copy_frontend();

        self::extend();
    }

    private static function copy_frontend() {
        global $install_err_message;

        $install_err_message = '';

        if (!is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public')) {
            if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public')) {
                if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates')) 
                    self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates');
                else 
                    $install_err_message .= __( 'Templates were not copied into your default theme. Please do it manually.', 'propeller-ecommerce' ) . '<br />';
                
                if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'partials'))
                    self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'partials', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'partials');
                else
                    $install_err_message .= __( 'Partial templates were not copied into your default theme. Please do it manually.', 'propeller-ecommerce' ) . '<br />'; 

                if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'email'))
                    self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'email', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'email');
                else
                    $install_err_message .= __( 'Partial templates were not copied into your default theme. Please do it manually.', 'propeller-ecommerce' ) . '<br />'; 

                if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets'))
                    self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets');
                else
                    $install_err_message .= __( 'Assets were not copied into your default theme. Please do it manually.', 'propeller-ecommerce' ) . '<br />'; 
            }
            else {
                $install_err_message .= __( 'Propeller templates folder was not created into your default theme. Please do it manually.', 'propeller-ecommerce' ) . '<br />';
            }
        }

        if (!empty($install_err_message)) {
            add_action( 'admin_notices', function() {
                global $install_err_message;

                $class = 'notice notice-error is-dismissible';
                $message = $install_err_message;
            
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
            } );
        }
    }

    private static function extend() {
        global $install_err_message;

        $install_err_message = '';

        if (!is_dir(PROPELLER_PLUGIN_EXTEND_DIR)) {
            if (@mkdir(PROPELLER_PLUGIN_EXTEND_DIR)) {
                self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'Custom', PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Custom');

                self::rrmdir(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'Custom');

                if (!is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets'))
                    self::recurse_copy(PROPELLER_PLUGIN_DIR . 'src' . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets', PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets');
        
                if (!is_dir(PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'Frontend' . DIRECTORY_SEPARATOR . 'assets'))
                    $install_err_message .= __( 'Propeller assets folder was not copied into your Custom/Frontend folder. Please do it manually.', 'propeller-ecommerce' ) . '<br />';
            }
            else {
                $install_err_message .= __( 'Custom folder was not copied into the root of your plugin. Please do it manually.', 'propeller-ecommerce' ) . '<br />';
            }
        }

        if (!empty($install_err_message)) {
            add_action( 'admin_notices', function() {
                global $install_err_message;

                $class = 'notice notice-error is-dismissible';
                $message = $install_err_message;
            
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
            } );
        }
    }

    private static function recurse_copy($src, $dst) { 
        $dir = @opendir($src); 
        @mkdir($dst);
		if(false === $dir) {
			return;
		}
        while(false !== ($file = @readdir($dir)) ) { 
            if (($file != '.') && ($file != '..')) { 
                if (@is_dir($src . DIRECTORY_SEPARATOR . $file)) { 
                    self::recurse_copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file); 
                } 
                else { 
                    @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file); 
                } 
            } 
        } 

        @closedir($dir); 
    }

    private static function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir);
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        self::rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object); 
                } 
            }
            rmdir($dir); 
        } 
    }
}