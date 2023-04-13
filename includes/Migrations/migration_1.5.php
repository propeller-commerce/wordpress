<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
            ADD COLUMN `use_datepicker` TINYINT(1) NULL DEFAULT 0 AFTER `selectable_carriers`;";

    return @$wpdb->query($sql);