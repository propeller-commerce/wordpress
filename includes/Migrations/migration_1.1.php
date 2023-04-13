<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `cc_email` VARCHAR(100) NULL DEFAULT NULL AFTER `default_locale`;";

    return @$wpdb->query($sql);