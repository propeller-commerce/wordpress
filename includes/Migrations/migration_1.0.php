<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `semiclosed_portal` TINYINT(1) NULL DEFAULT 0 AFTER `closed_portal`;";

    return @$wpdb->query($sql);