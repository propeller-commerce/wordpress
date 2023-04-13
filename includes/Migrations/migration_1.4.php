<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `selectable_carriers` TINYINT(1) NULL DEFAULT 0 AFTER `partial_delivery`;";

    return @$wpdb->query($sql);