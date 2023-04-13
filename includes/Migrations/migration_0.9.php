<?php
	defined( 'ABSPATH' ) || exit;

	if($wpdb->get_var("SHOW TABLES LIKE '$tbl_slugs'") != $tbl_slugs) {
		$sql = "CREATE TABLE `$tbl_slugs` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`page_id` INT NOT NULL,
			`language` VARCHAR(45) NOT NULL,
			`slug` VARCHAR(255) NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate;";

		@dbDelta($sql);

		if (empty($wpdb->last_error))
			return true;

		if (function_exists('propel_log'))
			propel_log($wpdb->last_error);
		
		return false;
	}

	return false;