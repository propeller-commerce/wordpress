<?php
    defined( 'ABSPATH' ) || exit; 

    $results = []; 

    $sql_contact_root = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `contact_root` VARCHAR(200) NOT NULL AFTER `site_id`;";

    $results[] = @$wpdb->query($sql_contact_root);

    $sql_customer_root = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `customer_root` VARCHAR(200) NOT NULL AFTER `contact_root`;";

    $results[] = @$wpdb->query($sql_customer_root);
   
    $sql_default_locale = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `default_locale` VARCHAR(10) NULL AFTER `customer_root`;";

    $results[] = @$wpdb->query($sql_default_locale);

    $sql_product_attrs = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `track_product_attr` TEXT NULL AFTER `track_user_attr`;";

    $results[] = @$wpdb->query($sql_product_attrs);

    return $results;