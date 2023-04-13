<?php

namespace Propeller\Admin;

use Exception;
use Propeller\Includes\Controller\BaseAjaxController;
use Propeller\Includes\Controller\BaseController;
use Propeller\Includes\Controller\PageController;
use Propeller\Propeller;
use Propeller\PropellerSitemap;
use Propeller\PropellerUtils;
use stdClass;

class PropellerAdmin {
        protected $propeller;
        protected $version;
        protected $plugin_screen_hook_suffix;
        
        public function __construct($propeller, $version) {
            $this->propeller = $propeller;
            $this->version = $version;

            $this->register_actions();
        }

        public function dashboard() {
            global $table_prefix, $wpdb;

            $settings_result = $wpdb->get_row("SELECT * FROM " . $table_prefix . PROPELLER_SETTINGS_TABLE);
            $pages_result = $wpdb->get_results("SELECT * FROM " . $table_prefix . PROPELLER_PAGES_TABLE);
            $behavior_result = $wpdb->get_row("SELECT * FROM " . $table_prefix . PROPELLER_BEHAVIOR_TABLE);
            $slugs_result = $wpdb->get_results("SELECT * FROM " . $table_prefix . PROPELLER_SLUGS_TABLE);

            foreach ($pages_result as $index => $page) {
                $pages_result[$index]->slugs = [];

                $page_id = $page->id;

                $found = array_filter($slugs_result, function($slug) use ($page_id) {
                    return $slug->page_id == $page_id;
                });

                if (count($found)) {
                    foreach ($found as $slug) {
                        $obj = new stdClass();

                        $obj->id = $slug->id;
                        $obj->page_id = $slug->page_id;
                        $obj->language = $slug->language;
                        $obj->slug = $slug->slug;

                        $pages_result[$index]->slugs[] = $obj;
                    }
                }
            }

            $translator = new PropellerTranslations();
            $sitemap = new PropellerSitemap();

            $sitemap_files = $sitemap->get_files();
            $sitemap_valid = $sitemap->is_valid();
            
            $slug_langs = [
                'en', 'nl', 'it', 'es'
            ];
            
            require 'views/propeller-admin-panel.php';
        }

        public function scripts() {
            wp_enqueue_script('propel_admin_bootstrap', PROPELLER_ASSETS_URL . '/js/lib/bootstrap.min.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_validator', PROPELLER_ASSETS_URL . '/js/lib/jquery-validator/jquery.validate.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_validator_extras', PROPELLER_ASSETS_URL . '/js/lib/jquery-validator/additional-methods.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_loader', PROPELLER_ASSETS_URL . '/js/lib/plain-overlay.min.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_accordion', PROPELLER_ASSETS_URL . '/js/lib/accordion.min.js', [], $this->version, true);
            
            // custom propeller admin js
            wp_enqueue_script('propel_admin_js', plugin_dir_url( __FILE__ ) . 'assets/js/propeller-admin.js', array( 'jquery' ), $this->version, true);

            wp_localize_script('propel_admin_js', 'propeller_admin_ajax', 
                array(
                    'ajaxurl' => admin_url( 'admin-ajax.php'),
                    'nonce' => wp_create_nonce('propel-ajax-nonce')
                )
            );
        }

        public function styles() {
            wp_enqueue_style('propel_admin_bootstrap', PROPELLER_ASSETS_URL . '/css/lib/bootstrap.min.css', array(), $this->version, 'all' );
            wp_enqueue_style('propel_admin_accordion', PROPELLER_ASSETS_URL . '/css/lib/accordion.min.css', array(), $this->version, 'all' );

            // custom propeller admin css
            wp_enqueue_style('propel_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/propeller-admin.css', array(), $this->version, 'all' );
        }

        public function menu() {
            $this->plugin_screen_hook_suffix = add_menu_page('Propeller', 'Propeller', 'manage_options', 'propeller', array( $this, 'dashboard' ));
        }

        public function save_settings() {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            if (!wp_verify_nonce($_POST['nonce'], 'propel-ajax-nonce' ) )
                die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

            if (current_user_can('manage_options')) {
                try {
                    $data = $this->sanitize($_POST);
                
                    $vals_arr = array(
                        'api_url' => $data['api_url'],
                        'api_key' => $data['api_key'],
                        'anonymous_user' => $data['anonymous_user'],
                        'catalog_root' => $data['catalog_root'],
                        'site_id' => $data['site_id'],
                        'contact_root' => $data['contact_root'],
                        'customer_root' => $data['customer_root'],
                        'default_locale' => $data['default_locale'],
                        'cc_email' => $data['cc_email'],
                        'bcc_email' => $data['bcc_email'],
                    );
        
                    if ($data['setting_id'] == '0')
                        $wpdb->insert($table_prefix . PROPELLER_SETTINGS_TABLE, $vals_arr);
                    else
                        $wpdb->update($table_prefix . PROPELLER_SETTINGS_TABLE, $vals_arr,
                            array(
                                'id' => $data['setting_id']
                            ));
        
                    // Destroy any caches in case the API key is changed
                    $this->destroy_caches(false);
        
                    Propeller::register_pages();
                    Propeller::register_settings();
                    Propeller::register_behavior();
                    
                    PageController::create_pages();
    
                    $message = __('Settings saved', 'propeller-ecommerce');
                }
                catch (Exception $ex) { 
                    $success = false;
                    $message = $ex->getMessage();
                }
            }
            else {
                $success = false;
                $message = __('Not enought rights to modify plugin settings', 'propeller-ecommerce');
            }

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function save_pages() {
            global $table_prefix, $wpdb;

            $data = $this->sanitize($_POST);

            $success = true;
            $message = '';

            if (!wp_verify_nonce($_POST['nonce'], 'propel-ajax-nonce' ) )
                die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));
            
            if (current_user_can('manage_options')) {
                try {
                    // delete any pages for deletion
                    if (!empty($data['delete_pages'])) {
                        $del_pages = explode(',', $data['delete_pages']);

                        foreach($del_pages as $p) {
                            if (!empty($p)) {
                                $page_result = $wpdb->get_row("SELECT * FROM " . $table_prefix . PROPELLER_PAGES_TABLE . " WHERE id = $p");
                                
                                // delete the page from Wordpress
                                $wpdb->delete($table_prefix . 'posts', [
                                    'post_name' => $page_result->page_slug, 
                                    'post_type' => 'page'
                                ]);
                                
                                $wpdb->delete($table_prefix . PROPELLER_PAGES_TABLE, ['id' => $p]);

                                // $wpdb->delete($table_prefix . PROPELLER_SLUGS_TABLE, ['page_id' => $p]);
                            }   
                        }
                    }

                    // insert new page definitions (or update)
                    foreach ($data['page'] as $index => $page) {
                        $vals_arr = [];
                        
                        $vals_arr['page_name']              = $page['page_name'];
                        $vals_arr['page_slug']              = $page['page_slug'];
                        $vals_arr['page_shortcode']         = $page['page_shortcode'];
                        $vals_arr['page_type']              = $page['page_type'];
                        
                        $vals_arr['page_sluggable']         = isset($page['page_sluggable']) ? 1 : 0;
                        $vals_arr['is_my_account_page']     = isset($page['is_my_account_page']) ? 1 : 0;
                        $vals_arr['account_page_is_parent'] = isset($page['account_page_is_parent']) ? 1 : 0;
                        
                        $page_id = null;

                        if ($page['id'] == '0') {
                            $wpdb->insert($table_prefix . PROPELLER_PAGES_TABLE, $vals_arr);
                            $page_id = $wpdb->insert_id;
                        }
                        else {
                            $wpdb->update($table_prefix . PROPELLER_PAGES_TABLE, $vals_arr,
                            array(
                                'id' => $page['id']
                            ));

                            $page_id = $page['id'];
                        }


                        // if ($page_id && isset($page['slugs']) && count($page['slugs'])) {
                        //     foreach ($page['slugs']['slug_id'] as $index => $slug_id) {
                        //         if (!empty($page['slugs']['slug'][$index])) {
                        //             $slug_vals = [
                        //                 'page_id' => $page_id,
                        //                 'language' => $page['slugs']['slug_lang'][$index],
                        //                 'slug' => $page['slugs']['slug'][$index]
                        //             ];

                        //             if ($page['slugs']['slug_exists'][$index] == '0')
                        //                 $wpdb->insert($table_prefix . PROPELLER_SLUGS_TABLE, $slug_vals);
                        //             else
                        //                 $wpdb->update($table_prefix . PROPELLER_SLUGS_TABLE, $slug_vals,
                        //                 array(
                        //                     'id' => $slug_id
                        //                 ));
                        //         }
                        //     }
                        // }
                    }

                    Propeller::register_pages();
                    Propeller::register_settings();
                    Propeller::register_behavior();

                    PageController::create_pages();

                    $message = __('Pages saved', 'propeller-ecommerce');
                }
                catch (Exception $ex) { 
                    $success = false;
                    $message = $ex->getMessage();
                }
            }
            else {
                $success = false;
                $message = __('Not enought rights to modify plugin pages', 'propeller-ecommerce');
            }

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function save_behavior() {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            if (!wp_verify_nonce($_POST['nonce'], 'propel-ajax-nonce' ) )
                die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

            if (current_user_can('manage_options')) {
                try {
                    $data = $this->sanitize($_POST);
    
                    $vals_arr = array(
                        'wordpress_session' => isset($data['wordpress_session']) ? 1 : 0,
                        'closed_portal' => isset($data['closed_portal']) ? 1 : 0,
                        'semiclosed_portal' => isset($data['semiclosed_portal']) ? 1 : 0,
                        'excluded_pages' => $data['excluded_pages'],
                        'track_user_attr' => $data['track_user_attr'],
                        'track_product_attr' => $data['track_product_attr'],
                        'reload_filters' => 0,
                        'use_recaptcha' => isset($data['use_recaptcha']) ? 1 : 0,
                        'recaptcha_site_key' => $data['recaptcha_site_key'],
                        'recaptcha_secret_key' => $data['recaptcha_secret_key'],
                        'recaptcha_min_score' => $data['recaptcha_min_score'],
                        'register_auto_login' => isset($data['register_auto_login']) ? 1 : 0,
	                    'assets_type' => isset($data['assets_type']) ? 1 : 0,
	                    'stock_check' => isset($data['stock_check']) ? 1 : 0,
	                    'load_specifications' => isset($data['load_specifications']) ? 1 : 0,
	                    'ids_in_urls' => isset($data['ids_in_urls']) ? 1 : 0,
	                    'partial_delivery' => isset($data['partial_delivery']) ? 1 : 0,
	                    'selectable_carriers' => isset($data['selectable_carriers']) ? 1 : 0,
	                    'use_datepicker' => isset($data['use_datepicker']) ? 1 : 0,
                    );
                    
                    if ($data['setting_id'] == '0')
                        $wpdb->insert($table_prefix . PROPELLER_BEHAVIOR_TABLE, $vals_arr);
                    else 
                        $wpdb->update($table_prefix . PROPELLER_BEHAVIOR_TABLE, $vals_arr,
                            array(
                                'id' => $data['setting_id']
                            ));

                    Propeller::register_pages();
                    Propeller::register_settings();
                    Propeller::register_behavior();
                    
                    PageController::create_pages();
    
                    $message = __('Behavior saved', 'propeller-ecommerce');
                }
                catch (Exception $ex) { 
                    $success = false;
                    $message = $ex->getMessage();
                }
            }
            else {
                $success = false;
                $message = __('Not enought rights to modify plugin behavior', 'propeller-ecommerce');
            }
            
            die(json_encode(['success' => $success, 'message' => $message]));
        }

		public function ajax_destroy_caches() {
			$this->destroy_caches(true);
		}

        public function destroy_caches($return_json = true) {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            if (!wp_verify_nonce($_POST['nonce'], 'propel-ajax-nonce' ) )
                die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

            if (current_user_can('manage_options')) {
                try {
                    $delSql = " DELETE FROM " . $table_prefix . "options 
                                WHERE option_name LIKE '_transient_propeller%' 
                                    OR option_name LIKE '_transient_timeout_propeller%' ";
    
                    $wpdb->query($delSql);
    
                    do_action('propel_cache_destroyed');
    
                    $message = __('Caches cleared', 'propeller-ecommerce');
                }
                catch (Exception $ex) { 
                    $success = false;
                    $message = $ex->getMessage();
                }
            }
            else {
                $success = false;
                $message = __('Not enought rights to destroy plugin caches', 'propeller-ecommerce');
            }
            
            if ($return_json)
                die(json_encode(['success' => $success, 'message' => $message]));
		}

        public function sanitize($data) {
            return PropellerUtils::sanitize($data);
        }

        public function register_actions() { 
            $translator = new PropellerTranslations();
            $sitemap = new PropellerSitemap();

            add_action('wp_ajax_save_translations', array($translator, 'save_translations'));
            
            add_action('wp_ajax_scan_translations', array($translator, 'scan_translations'));
            
            add_action('wp_ajax_generate_translations', array($translator, 'generate_translations'));
            
            add_action('wp_ajax_create_translations_file', array($translator, 'create_translations_file'));

            add_action('wp_ajax_restore_translations', array($translator, 'restore_translations'));

            add_action('wp_ajax_load_translations_backups', array($translator, 'load_translations_backups'));
            
            add_action('init', array($translator, 'download_translations'), 10);

            add_action('wp_ajax_save_propel_settings', array($this, 'save_settings'));
            
            add_action('wp_ajax_save_propel_pages', array($this, 'save_pages'));
            
            add_action('wp_ajax_save_propel_behavior', array($this, 'save_behavior'));
            
            add_action('wp_ajax_propel_destroy_caches', array($this, 'ajax_destroy_caches'));

            add_action('wp_ajax_propel_generate_sitemap', array($sitemap, 'build_sitemap'));
        }
    }