<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `stock_check` TINYINT(1) NULL DEFAULT 0 AFTER `assets_type`;";

    return @$wpdb->query($sql);