<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `default_locale` VARCHAR(10) NULL DEFAULT NULL AFTER `customer_root`;";

    return @$wpdb->query($sql);