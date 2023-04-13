<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `load_specifications` TINYINT(1) NULL DEFAULT 0 AFTER `stock_check`;";

    return @$wpdb->query($sql);