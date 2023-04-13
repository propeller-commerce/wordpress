<?php

namespace Propeller\Meta;

use Propeller\Propeller;

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
    protected $trp_available;

    protected $add_custom = false;

    public function __construct() {
        $this->yoast_available = (in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))) && class_exists('WPSEO_Options'));
        $this->trp_available = (in_array('translatepress-multilingual/index.php', apply_filters('active_plugins', get_option('active_plugins'))) && class_exists('TRP_Translate_Press'));
        
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
        add_action('wp_head', [$this, 'generate_alternate_metas'], 10, 2);
    }

    public function get_canonical_url($canonical_url, $post) {
        global $propel, $wp_query, $locale;

        if (isset($propel['meta']) && count($propel['meta']) > 0 && isset($propel['meta']['url']))
            $canonical_url = $propel['meta']['url'];

        return $canonical_url;
    }

    private function get_x_default() {
        global $propel, $wp_query;

        if (isset($wp_query->query_vars['pagename']) && !empty($propel['url_slugs'])) {
            $realm = $wp_query->query_vars['pagename'];

            $old_locale = get_locale();
            $new_locale = PROPELLER_DEFAULT_LOCALE;

            if ($old_locale != $new_locale) {
                $new_locale_chunk = strpos($new_locale, '_') ? explode('_', $new_locale)[0] : $new_locale;

                $found = array_filter($propel['url_slugs'], function($obj) use ($new_locale_chunk) { 
                    return strtolower($obj->language) == strtolower($new_locale_chunk); 
                });

                if (count($found)) {
                    $slug = current($found)->value;
                    
                    return site_url($new_locale_chunk . '/' . $realm . '/' . $slug . '/');
                }
            }
        }

        return '';
    }

    public function generate_alternate_metas() {
        global $propel, $wp_query;

        $default_locale = get_locale();

        $langs = get_propel_languages();

        $tags = [];
        
        if (isset($wp_query->query_vars['pagename']) && isset($propel['url_slugs']) && count($propel['url_slugs'])) {
            $realm = $wp_query->query_vars['pagename'];

            foreach ($langs as $lang) {
                $lang_code = strpos($lang, '_') ? explode('_', $lang)[0] : $lang;

                $found = array_filter($propel['url_slugs'], function($obj) use ($lang_code) { 
                    return strtolower($obj->language) == strtolower($lang_code); 
                });
    
                if (count($found)) {
                    $slug = current($found)->value;
                    
                    if ($default_locale != $lang)
                        $tags[str_replace('_', '-', $lang)] = site_url($lang_code . '/' . $realm . '/' . $slug . '/');
                    else 
                        $tags[str_replace('_', '-', $lang)] = site_url($realm . '/' . $slug . '/');
                }                    
            }
        }

        $tags['x-default'] = $this->get_x_default();

        foreach ($tags as $lang => $url) {
            if (!empty($url))
                echo '<link rel="alternate" hreflang="' . esc_attr($lang) . '" href="' . esc_url($url) . '" />' . "\r\n";
        }            
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