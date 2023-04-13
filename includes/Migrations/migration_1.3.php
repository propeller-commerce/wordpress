<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `partial_delivery` TINYINT(1) NULL DEFAULT 0 AFTER `ids_in_urls`;";

    return @$wpdb->query($sql);