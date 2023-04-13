<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_settings` 
            ADD COLUMN `bcc_email` VARCHAR(100) NULL DEFAULT NULL AFTER `cc_email`;";

    return @$wpdb->query($sql);