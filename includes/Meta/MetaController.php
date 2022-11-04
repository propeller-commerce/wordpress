<?php

namespace Propeller\Meta;

// <meta name="twitter:card" content="product" />
// <meta name="twitter:site" content="" />
// <meta name="twitter:title" content="" />
// <meta name="twitter:description" content="" />
// <meta name="twitter:image" content="" />

// <meta property="og:type" content="og:product" />
// <meta property="og:url" content="" />
// <meta property="og:title" content="" />
// <meta property="og:description" content="" />
// <meta property="og:image" content="" />

class MetaController {
    protected $yoast_available;
    protected $add_custom = false;

    public function __construct() {
        $this->yoast_available = (in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))) && class_exists('WPSEO_Options'));
        
        if ($this->yoast_available) {
            if ($this->add_custom) {
                add_filter('wpseo_frontend_presenters', [$this, 'meta_presenters']);
            }
            else {
                $this->apply_yoast_filters();
            }   
        }
        else {
            new MetaPresenter();
        }

        add_filter('get_canonical_url', [$this, 'get_canonical_url'], 10, 2);
    }

    public function get_canonical_url($canonical_url, $post) {
        global $propel;
        

        if (isset($propel['meta']) && count($propel['meta']) > 0 && isset($propel['meta']['url']))
            $canonical_url = $propel['meta']['url'];

        return $canonical_url;
    }
    
    public function meta_presenters($presenters) {
        $presenters[] = new MetaYoastPresenter();
        
        return $presenters;
    } 

    public function apply_yoast_filters() {
        $this->apply_yoast_og_filters();

        $this->apply_yoast_twitter_filters();
    }

    private function apply_yoast_og_filters() {
        add_filter('wpseo_opengraph_title', function($value){
            global $propel;

            if (isset($propel['meta']) && isset($propel['meta']['title']))
                return $propel['meta']['title'];

            return $value;
        });

        add_filter('wpseo_opengraph_desc', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['description']))
                return $propel['meta']['description'];

            return $value;
        });

        add_filter('wpseo_opengraph_url', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['url']))
                return $propel['meta']['url'];

            return $value;
        });

        add_filter('wpseo_opengraph_type', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['type']))
                return $propel['meta']['type'];

            return $value;
        });

        add_filter('wpseo_og_locale', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['locale']))
                return $propel['meta']['locale'];

            return $value;
        });

        add_filter('wpseo_opengraph_site_name', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['locale']))
                return $propel['meta']['locale'];

            return $value;
        });

        add_filter('wpseo_opengraph_image', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['image']))
                return $propel['meta']['image'];

            return $value;
        });

        add_filter('wpseo_opengraph_site_name', function($value){
            return get_bloginfo('name');
        });
    }

    private function apply_yoast_twitter_filters() {
        add_filter('wpseo_twitter_title', function($value){
            global $propel;

            if (isset($propel['meta']) && isset($propel['meta']['title']))
                return $propel['meta']['title'];

            return $value;
        });

        add_filter('wpseo_twitter_description', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['description']))
                return $propel['meta']['description'];

            return $value;
        });

        add_filter('wpseo_twitter_site', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['url']))
                return $propel['meta']['url'];

            return $value;
        });

        add_filter('wpseo_twitter_card_type', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['type']))
                return $propel['meta']['type'];

            return $value;
        });

        add_filter('wpseo_twitter_image', function($value){
            global $propel;
            
            if (isset($propel['meta']) && isset($propel['meta']['image']))
                return $propel['meta']['image'];

            return $value;
        });
    }
}