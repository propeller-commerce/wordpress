<?php
namespace Propeller\Includes\Controller;

class SessionController {
    public static function start() {
        $UserController = new UserController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            $time = (int) ini_get("session.gc_maxlifetime"); // Set expire time with secends.

            if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout']) > $time) {
                $UserController->logout(false);

                // exit();
            } else {
                $_SESSION['timeout'] = time();

                // TODO: see if we should really start a session for anonymous users?!?!
                $UserController->start_session();
                
                $UserController->refresh_access_token();

                if (!self::get(PROPELLER_CART)) {
                    self::set(PROPELLER_CART_INITIALIZED, false);
                    self::set(PROPELLER_CART_USER_SET, false);
                }
            }
        }
        else {
            // echo 'session already started<br />';
        }
    }

    public static function session_id() {
        return session_id();
    }

    public static function set($var_name, $value) {
        $_SESSION[$var_name] = $value;
    }

    public static function get($var_name) {
        if (!self::has($var_name))
            return null;

        return $_SESSION[$var_name];
    }

    public static function has($var_name) {
        if (isset($_SESSION[$var_name]))
            return true;

        return false;
    }

    public static function remove($var_name) {
        if (isset($_SESSION[$var_name]))
            unset($_SESSION[$var_name]);
    }

    public static function get_user_id() {
        return $_SESSION[PROPELLER_USER_ID];
    }

    public static function get_cart_id() {
        return $_SESSION[PROPELLER_CART_ID];
    }

    public static function unset() {    
        session_unset();
    }

    public static function end() {
        self::unset();
		if(self::session_id()) {
			session_destroy();
		}
    }
}