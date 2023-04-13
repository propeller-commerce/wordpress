<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `ids_in_urls` TINYINT(1) NULL DEFAULT 0 AFTER `load_specifications`;";

    return @$wpdb->query($sql);