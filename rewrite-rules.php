<?php

use Propeller\Includes\Enum\PageType;

    function propeller_rewrites_init() {
        global $propellerSluggablePages;

        if (is_array($propellerSluggablePages) && sizeof($propellerSluggablePages)) {
            foreach ($propellerSluggablePages as $type => $page) {
                $qry_var = 'slug';

                switch ($type) {
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

                add_rewrite_rule(
                    $page . '/([^/]*)/?$',
                    'index.php?pagename=' . $page . '&' . $qry_var . '=$matches[1]',
                    'top');
            }
        }
	}
	add_action('init', 'propeller_rewrites_init');

	function propel_purge_caches() {
		flush_rewrite_rules();
	}
	add_action('propel_after_activation', 'propel_purge_caches');
	add_action('propel_cache_destroyed', 'propel_purge_caches');

    function propeller_query_vars($query_vars) {
        $query_vars[] = 'slug';
        $query_vars[] = 'term';
        $query_vars[] = 'manufacturer';
        $query_vars[] = 'pagename';

        return $query_vars;
    }

    add_filter( 'query_vars', 'propeller_query_vars' );


    // Allow seding emails in html format
    function set_email_content_type(){
        return "text/html";
    }

    add_filter('wp_mail_content_type', 'set_email_content_type');


    if ( ! function_exists('debug_wpmail') ) :
        function debug_wpmail( $result = false ) {

            if ( $result )
                return;

            global $ts_mail_errors, $phpmailer;

            if ( ! isset($ts_mail_errors) )
                $ts_mail_errors = array();

            if ( isset($phpmailer) )
                $ts_mail_errors[] = $phpmailer->ErrorInfo;

            return $ts_mail_errors;
        }
    endif;

    function sort_by_priority_desc($obj1, $obj2) {
        return $obj1->priority > $obj2->priority;
    }
?>