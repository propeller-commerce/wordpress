<?php
namespace Propeller;

use Propeller\Includes\Controller\PageController;

class PropellerActivate {
    public static function activate() {
        global $table_prefix, $wpdb;

        $tbl_settings   = $table_prefix . PROPELLER_SETTINGS_TABLE;
        $tbl_pages      = $table_prefix . PROPELLER_PAGES_TABLE;
        $tbl_behavior   = $table_prefix . PROPELLER_BEHAVIOR_TABLE;

        $charset_collate = $wpdb->get_charset_collate();

        // Check to see if the table exists already, if not, then create it
        if($wpdb->get_var("SHOW TABLES LIKE '$tbl_settings'") != $tbl_settings) {

            $sql = "CREATE TABLE $tbl_settings (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    api_url VARCHAR(200) NOT NULL,
                    api_key VARCHAR(200) NOT NULL,
                    anonymous_user VARCHAR(200) NOT NULL,
                    catalog_root VARCHAR(200) NOT NULL,
                    site_id VARCHAR(200) NOT NULL,
                    contact_root VARCHAR(200) NOT NULL,
                    customer_root VARCHAR(200) NOT NULL,
                    UNIQUE KEY id (id)
            ) $charset_collate;";


            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tbl_pages'") != $tbl_pages) {
            $sql = "CREATE TABLE $tbl_pages (
                        id INT(11) NOT NULL AUTO_INCREMENT,
                        page_name VARCHAR(200) NOT NULL,
                        page_slug VARCHAR(200) NOT NULL,
                        page_sluggable TINYINT(1) DEFAULT 0,
                        page_shortcode VARCHAR(200) NOT NULL,
                        page_type VARCHAR(200) NOT NULL,
                        is_my_account_page TINYINT(1) DEFAULT 0,
                        account_page_is_parent TINYINT(1) DEFAULT 0,
                        UNIQUE KEY id (id)
                ) $charset_collate;";

            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);

            PageController::insert_default_pages();
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tbl_behavior'") != $tbl_behavior) {

            $sql = "CREATE TABLE $tbl_behavior (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    wordpress_session TINYINT(1) DEFAULT 0,
                    closed_portal TINYINT(1) DEFAULT 0,
                    excluded_pages TEXT DEFAULT NULL,
                    track_user_attr VARCHAR(200) DEFAULT NULL,
                    track_product_attr TEXT DEFAULT NULL,
                    reload_filters TINYINT(1) DEFAULT 0,
                    UNIQUE KEY id (id)
            ) $charset_collate;";


            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);

            $wpdb->insert($tbl_behavior, array(
                'wordpress_session' => 0, 
                'closed_portal' => 0, 
                'excluded_pages' => '',
                'track_user_attr' => '',
                'track_product_attr' => '',
                'reload_filters' => 0
            ));
        }
    }
}