<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior`
            ADD COLUMN `assets_type`  TINYINT(1) NULL DEFAULT 1 AFTER `register_auto_login`;";

    return @$wpdb->query($sql);