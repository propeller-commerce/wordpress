<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

function propeller_rewrites_init() {
	global $propellerSluggablePages;

	if ( is_array( $propellerSluggablePages ) && sizeof( $propellerSluggablePages ) ) {
		foreach ( $propellerSluggablePages as $type => $page ) {
			$qry_var = 'slug';

			switch ( $type ) {
				case PageType::SEARCH_PAGE:
					$qry_var = 'term';
					break;
				case PageType::BRAND_PAGE:
					$qry_var = 'manufacturer';
					break;
				default:
					$qry_var = 'slug';
					break;
			}

			if ($page == PageController::get_slug(PageType::MACHINES_PAGE)) {
				$qry_var = 'slug[]';
				$slug_pattern = '/([^/]*)';
				$slug_end_patern = '/?$';
				
				$rule_pattern = $page;
				
				$url_patern = 'index.php?pagename=' . $page;
				$url_end_pattern = '&' . $qry_var . '=$matches[{ptrn}]';

				for ($i = 0; $i < PROPELLER_MACHINES_DEPTH; $i++) {
					$rule_pattern .= $slug_pattern;
					$url_patern .= str_replace('{ptrn}', $i + 1, $url_end_pattern);

					add_rewrite_rule(
						$rule_pattern . $slug_end_patern,
							$url_patern,
							'top' );
				}
			}
			else {
				if ($qry_var == 'slug' && ($type == PageType::PRODUCT_PAGE || $type == PageType::CATEGORY_PAGE)) {
					if (PROPELLER_ID_IN_URL) {
						add_rewrite_rule(
							$page . '/(\d+)/([^/]*)/?$',
							'index.php?pagename=' . $page . '&obid=$matches[1]&' . $qry_var . '=$matches[2]',
							'top' );	
					}
					else {
						add_rewrite_rule(
								$page . '/([^/]*)/?$',
									'index.php?pagename=' . $page . '&' . $qry_var . '=$matches[1]',
									'top' );
						}
					}
				else {
					add_rewrite_rule(
						$page . '/([^/]*)/?$',
						'index.php?pagename=' . $page . '&' . $qry_var . '=$matches[1]',
						'top' );
				}
			
			}
		}
	}
}

add_action( 'init', 'propeller_rewrites_init' );
add_action( 'init', 'propel_purge_caches' );

function propel_purge_caches() {
	flush_rewrite_rules();
}

add_action( 'propel_after_activation', 'propel_purge_caches' );
add_action( 'propel_cache_destroyed', 'propel_purge_caches' );

function propeller_query_vars( $query_vars ) {
	$query_vars[] = 'obid';
	$query_vars[] = 'slug';
	$query_vars[] = 'term';
	$query_vars[] = 'manufacturer';
	$query_vars[] = 'pagename';

	return $query_vars;
}

add_filter( 'query_vars', 'propeller_query_vars' );

if ( ! function_exists( 'debug_wpmail' ) ) :
	function debug_wpmail( $result = false ) {

		if ( $result ) {
			return;
		}

		global $ts_mail_errors, $phpmailer;

		if ( ! isset( $ts_mail_errors ) ) {
			$ts_mail_errors = array();
		}

		if ( isset( $phpmailer ) ) {
			$ts_mail_errors[] = $phpmailer->ErrorInfo;
		}

		return $ts_mail_errors;
	}
endif;

