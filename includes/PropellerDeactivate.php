<?php

namespace Propeller;

class PropellerDeactivate {
    public static function deactivate() {
        
    }

    public static function uninstall() {
        global $table_prefix, $wpdb;

        $wpdb->query('DROP TABLE IF EXISTS ' . $table_prefix . PROPELLER_SETTINGS_TABLE);
        $wpdb->query('DROP TABLE IF EXISTS ' . $table_prefix . PROPELLER_PAGES_TABLE);
        $wpdb->query('DROP TABLE IF EXISTS ' . $table_prefix . PROPELLER_BEHAVIOR_TABLE);
        $wpdb->query('DROP TABLE IF EXISTS ' . $table_prefix . PROPELLER_SLUGS_TABLE);
    }
}