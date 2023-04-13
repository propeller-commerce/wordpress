<?php
    defined( 'ABSPATH' ) || exit;

    $sql = "ALTER TABLE `$tbl_behavior` 
                ADD COLUMN `recaptcha_min_score` FLOAT NULL DEFAULT NULL AFTER `recaptcha_secret_key`;";

    return @$wpdb->query($sql);