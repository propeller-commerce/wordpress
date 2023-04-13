<?php
    defined( 'ABSPATH' ) || exit; 

    $results = []; 

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `use_recaptcha` TINYINT(1) NULL DEFAULT 0 AFTER `reload_filters`,
            ADD COLUMN `recaptcha_site_key` VARCHAR(200) NULL DEFAULT NULL AFTER `use_recaptcha`,
            ADD COLUMN `recaptcha_secret_key` VARCHAR(200) NULL DEFAULT NULL AFTER `recaptcha_site_key`;";

    $results[] = @$wpdb->query($sql);

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `register_auto_login` TINYINT(1) NULL DEFAULT 0 AFTER `recaptcha_secret_key`;";

    $results[] = @$wpdb->query($sql);

    return $results;