<?php

namespace Propeller\Admin;

use Exception;
use Propeller\Includes\Controller\PageController;
use Propeller\Propeller;

class PropellerAdmin {
        protected $propeller;
        protected $version;
        
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

            $translator = new PropellerTranslations();
            
            require 'views/propeller-admin-panel.php';
        }

        public function scripts() {
            wp_enqueue_script('propel_admin_bootstrap', plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap.min.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_loader', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-validator/jquery.validate.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_loader', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-validator/additional-methods.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script('propel_admin_validator', plugin_dir_url( __FILE__ ) . 'assets/js/plain-overlay.min.js', array( 'jquery' ), $this->version, true);
            
            // custom propeller admin js
            wp_enqueue_script('propel_admin_js', plugin_dir_url( __FILE__ ) . 'assets/js/propeller-admin.js', array( 'jquery' ), $this->version, true);

            wp_localize_script('propel_admin_js', 'propeller_admin_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
        }

        public function styles() {
            wp_enqueue_style( $this->propeller.'_propel_admin_bootstrap', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap.min.css', array(), $this->version, 'all' );

            // custom propeller admin css
            wp_enqueue_style( $this->propeller.'_propel_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/propeller-admin.css', array(), $this->version, 'all' );
        }

        public function menu() {
            $this->plugin_screen_hook_suffix = add_menu_page('Propeller', 'Propeller', 'manage_options', 'propeller', array( $this, 'dashboard' ));
        }

        public function save_settings() {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            try {
                $data = $_POST;
            
                $vals_arr = array(
                    'api_url' => $data['api_url'],
                    'api_key' => $data['api_key'],
                    'anonymous_user' => $data['anonymous_user'],
                    'catalog_root' => $data['catalog_root'],
                    'site_id' => $data['site_id'],
                    'contact_root' => $data['contact_root'],
                    'customer_root' => $data['customer_root'],
                );
    
                if ($data['setting_id'] == '0')
                    $wpdb->insert($table_prefix . PROPELLER_SETTINGS_TABLE, $vals_arr);
                else
                    $wpdb->update($table_prefix . PROPELLER_SETTINGS_TABLE, $vals_arr,
                        array(
                            'id' => $data['setting_id']
                        ));
    
                // Destroy any caches in case the API key is changed
                $this->destroy_caches();
    
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

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function save_pages() {
            global $table_prefix, $wpdb;

            $data = $_POST;

            $success = true;
            $message = '';
            
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
                    
                    $result = null;

                    if ($page['id'] == '0')
                        $result = $wpdb->insert($table_prefix . PROPELLER_PAGES_TABLE, $vals_arr);
                    else 
                        $result = $wpdb->update($table_prefix . PROPELLER_PAGES_TABLE, $vals_arr,
                            array(
                                'id' => $page['id']
                            ));
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

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function save_behavior() {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            try {
                $data = $_POST;

                $vals_arr = array(
                    'wordpress_session' => isset($data['wordpress_session']) ? 1 : 0,
                    'closed_portal' => isset($data['closed_portal']) ? 1 : 0,
                    'excluded_pages' => $data['excluded_pages'],
                    'track_user_attr' => $data['track_user_attr'],
                    'track_product_attr' => $data['track_product_attr'],
                    'reload_filters' => 0
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

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function destroy_caches() {
            global $table_prefix, $wpdb;

            $success = true;
            $message = '';

            try {
                $delSql = " DELETE FROM " . $table_prefix . "options 
                            WHERE option_name LIKE '_transient_propeller%' 
                                OR option_name LIKE '_transient_timeout_propeller%' ";

                $wpdb->query($delSql);

                $message = __('Caches cleared', 'propeller-ecommerce');
            }
            catch (Exception $ex) { 
                $success = false;
                $message = $ex->getMessage();
            }

            die(json_encode(['success' => $success, 'message' => $message]));
        }

        public function register_actions() { 
            $translator = new PropellerTranslations();

            add_action('wp_ajax_save_translations', array($translator, 'save_translations'));
            add_action('wp_ajax_nopriv_save_translations', array($translator, 'save_translations'));

            add_action('wp_ajax_scan_translations', array($translator, 'scan_translations'));
            add_action('wp_ajax_nopriv_scan_translations', array($translator, 'scan_translations'));

            add_action('wp_ajax_generate_translations', array($translator, 'generate_translations'));
            add_action('wp_ajax_nopriv_generate_translations', array($translator, 'generate_translations'));

            add_action('wp_ajax_create_translations_file', array($translator, 'create_translations_file'));
            add_action('wp_ajax_nopriv_create_translations_file', array($translator, 'create_translations_file'));

            add_action('wp_ajax_save_propel_settings', array($this, 'save_settings'));
            add_action('wp_ajax_nopriv_save_propel_settings', array($this, 'save_settings'));

            add_action('wp_ajax_save_propel_pages', array($this, 'save_pages'));
            add_action('wp_ajax_nopriv_save_propel_pages', array($this, 'save_pages'));

            add_action('wp_ajax_save_propel_behavior', array($this, 'save_behavior'));
            add_action('wp_ajax_nopriv_save_propel_behavior', array($this, 'save_behavior'));

            add_action('wp_ajax_propel_destroy_caches', array($this, 'destroy_caches'));
            add_action('wp_ajax_nopriv_propel_destroy_caches', array($this, 'destroy_caches'));
        }
    }