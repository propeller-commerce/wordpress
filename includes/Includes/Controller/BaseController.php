<?php

namespace Propeller\Includes\Controller;

use Propeller\Frontend\PropellerAssets;
use stdClass;
use Exception;
use GraphQL\RawObject;
use Propeller\PropellerApi;

class BaseController extends PropellerApi {
    protected $model;

    public $default_assets_url;
    public $default_assets_dir;
    public $default_templates_dir;
    public $default_partials_dir;
    public $default_emails_dir;
    public $default_errors_dir;

    public $assets_url;
    public $assets_dir;
    public $templates_dir;
    public $partials_dir;
    public $emails_dir;
    public $errorss_dir;

    public $theme_assets_url;
    public $theme_assets_dir;
    public $theme_templates_dir;
    public $theme_partials_dir;
    public $theme_emails_dir;
    public $theme_errors_dir;

    public $pagename;

	protected $assets;

    const TEXT_FILTERS_KEY = 'textFilters';
    const RANGE_FILTERS_KEY = 'rangeFilters';

    protected $array_filters = ['textFilters', 'rangeFilters'];

    public function __construct() {
        parent::__construct();

		$this->assets = new PropellerAssets();

        $this->register_directories();
    }

    private function register_directories() {
        // initial template/assets paths
        $this->assets_url = PROPELLER_ASSETS_URL;
        $this->assets_dir = PROPELLER_ASSETS_DIR;
        $this->templates_dir = PROPELLER_TEMPLATES_DIR;
        $this->partials_dir = PROPELLER_PARTIALS_DIR;
        $this->emails_dir = PROPELLER_EMAILS_DIR;
        $this->errorss_dir = PROPELLER_ERROR_DIR;

        // default template/assets paths
        $this->default_assets_url = PROPELLER_ASSETS_URL;
        $this->default_assets_dir = PROPELLER_ASSETS_DIR;
        $this->default_templates_dir = PROPELLER_TEMPLATES_DIR;
        $this->default_partials_dir = PROPELLER_PARTIALS_DIR;
        $this->default_emails_dir = PROPELLER_EMAILS_DIR;
        $this->default_errors_dir = PROPELLER_ERROR_DIR;

        // theme template/assets paths
        $this->theme_assets_url = get_theme_file_uri() . '/propeller/assets';
        $this->theme_assets_dir = get_theme_file_path() . DIRECTORY_SEPARATOR . 'propeller' . DIRECTORY_SEPARATOR . 'assets';
        $this->theme_templates_dir = get_theme_file_path() . DIRECTORY_SEPARATOR . 'propeller' . DIRECTORY_SEPARATOR . 'templates';
        $this->theme_partials_dir = get_theme_file_path() . DIRECTORY_SEPARATOR . 'propeller' . DIRECTORY_SEPARATOR . 'partials';
        $this->theme_emails_dir = get_theme_file_path() . DIRECTORY_SEPARATOR . 'propeller' . DIRECTORY_SEPARATOR . 'email';
        $this->theme_errors_dir = get_theme_file_path() . DIRECTORY_SEPARATOR . 'propeller' . DIRECTORY_SEPARATOR . 'error';

		if(defined('PROPELLER_PLUGIN_EXTEND_DIR')) {

			$extends_dir = PROPELLER_PLUGIN_EXTEND_DIR;
			$extends_url = PROPELLER_PLUGIN_EXTEND_URL;

			if ( is_dir( $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' ) ) {
				$this->assets_url = $extends_url . '/public/assets';
				$this->assets_dir = $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets';
			}

			if ( is_dir( $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates' ) ) {
				$this->templates_dir = $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'templates';
			}

			if ( is_dir( $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'partials' ) ) {
				$this->partials_dir = $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'partials';
			}

			if ( is_dir( $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'email' ) ) {
				$this->emails_dir = $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'email';
			}

			if ( is_dir( $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'error' ) ) {
				$this->errorss_dir = $extends_dir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'error';
			}
		}
    }

    public function load_template($path, $template) {
        $dir = $this->templates_dir;
        $default_dir = $this->default_templates_dir;
        $theme_dir = $this->theme_templates_dir;

        switch ($path) {
            case 'emails':
                $theme_dir = $this->theme_emails_dir;
                $default_dir = $this->default_emails_dir;
                $dir = $this->emails_dir;
                break;
            case 'partials':
                $theme_dir = $this->theme_partials_dir;
                $default_dir = $this->default_partials_dir;
                $dir = $this->partials_dir;
                break;
            case 'templates':
                $theme_dir = $this->theme_templates_dir;
                $default_dir = $this->default_templates_dir;
                $dir = $this->templates_dir;
                break;
            case 'error':
                $theme_dir = $this->theme_errors_dir;
                $default_dir = $this->default_errors_dir;
                $dir = $this->errorss_dir;
                break;
            case 'assets':
                $theme_dir = $this->theme_assets_dir;
                $default_dir = $this->default_assets_dir;
                $dir = $this->assets_dir;
                break;
        }

        if (file_exists($theme_dir . $template))
            return $theme_dir . $template;
        else if (file_exists($dir . $template))
            return $dir . $template;
        else
            return $default_dir . $template;
    }

	/**
	 * Returns the assets instance
	 * @return PropellerAssets
	 */
	public function assets() {
		return $this->assets;
	}

    public function load_model($model) {
        $default_ref = "Propeller\Includes\Model\\" . ucfirst($model) . 'Model';
        $custom_ref = "Propeller\Custom\Includes\Model\\" . ucfirst($model) . 'Model';

        return class_exists($custom_ref, true) ? new $custom_ref() : new $default_ref();
    }

    public function buildUrl($realm_slug, $slug, $id = null) {
        if (empty($slug))
            return home_url( $realm_slug . '/' );
        else {
            if ($id && PROPELLER_ID_IN_URL)
                return home_url($realm_slug . '/' . $id . '/' . $slug . '/');
            else 
                return home_url($realm_slug . '/' . $slug . '/');
        } 
    }

    public function get_salutation($obj) {
        if (!isset($obj->gender))
            return SALUTATION_U;

        if ($obj->gender === 'M')
            return SALUTATION_M;
        else if ($obj->gender === 'F')
            return SALUTATION_F;
        else
            return SALUTATION_U;
    }

    // Cookies
    protected function set_cookie($name, $value, $expiration = PROPELLER_COOKIE_EXPIRATION) {
        $res = setcookie($name, $value, $expiration, "/");
    }

    protected function get_cookie($name) {
        if (isset($_COOKIE[$name]))
            return urldecode($_COOKIE[$name]);

        return null;
    }

    protected function remove_cookie($name) {
        if (isset($_COOKIE[$name]))
            setcookie($name, '', time() - 3600);
    }

    // Search params builder
    public function build_search_arguments($args) {
        error_reporting(E_ERROR | E_PARSE);

        $params = [];

        foreach ($args as $key => $value) {
            try {
                if (empty($value))
                    continue;

                switch ($key) {
                    case "term":    // String
                    case "path":
                        $params[$key] = '"' . $value .'"';

                        break;
                    case "sku":     //[String!]
                    case "manufacturer":
                    case "supplierCode":
                    case "SKU":
                    case "supplier":
                    case "brand":
                    case "manufacturerCode":
                    case "EANCode":
                    case "tag":
                        if (strpos($value, ','))
                            $params[$key] = explode(',', $value);
                        else
                            $params[$key] = '"' . $value .'"';

                        break;

                    case "id":      //[Int!]
                    case "classId":
                    case "ids":
                        if (strpos($value, ',')) {
                            $params[$key] = explode(',', $value);

                            // convert all strings to integers
                            foreach ($params[$key] as $index => $val)
                                $params[$key][$index] = (int) $val;
                        }
                        else
                            $params[$key] = (int) $value;

                        break;

                    case "class":   //ProductClass: product/cluster
                        $params[$key] = $value;

                        break;

                    case "language":    //String = "NL" = "NL"
                        $params[$key] = '"' . $value . '"';

                        break;

                    case "page":    //Int = 1 = 1
                    case "offset":  //Int = 12 = 12
                    case "categoryId":
                        $params[$key] = (int) $value;

                        break;

                    case "textFilters":     //[TextFilterInput!]
                        $textFilters = [];

                        if (strpos($value, '|'))
                            $textFilters = explode('|', $value);
                        else
                            $textFilters[] = $value;

                        $processed = [];
                        foreach ($textFilters as $flt) {
                            $chunks = explode('=', $flt);
                            $searchId = $chunks[0];

                            $valueChunks = explode(';', $chunks[1]);
                            $values = $valueChunks[0];
                            $type = $valueChunks[1];

                            $text_vals = [];
                            foreach (explode('^', $values) as $vals) {
								foreach(explode('+', $vals) as $item) {
									$text_vals[] = $item;
								}
                            }

                            $processed[] = [
                                'searchId' => '"' . $searchId . '"',
                                'values' => '["' . implode('","', $text_vals) . '"]',
                                'type' => $type
                            ];
                        }

                        $params[$key] = $processed;

                        break;

                    case "rangeFilters": // [RangeFilterInput!]
                        $chunks = explode('=', $value);
                        $searchId = $chunks[0];

                        $valueChunks = explode(';', $chunks[1]);
                        $values = explode(',', $valueChunks[0]);
                        $type = $valueChunks[1];

                        $params[$key] = [
                            'searchId' => '"' . $searchId . '"',
                            'from' =>  (float) $values[0],
                            'to' =>  (float) $values[1],
                            'exclude' => false,
                            'type' => $type
                        ];

                        break;

                    case "price":   // PriceFilterInput
                        $values = explode(',', $value);

                        $params[$key] = [
                            'from' => (float) $values[0],
                            'to' => (float) $values[1]
                        ];

                        break;

                    case "status":  // [ProductStatus!] = [ "A" ] = [A]
                        $params[$key] = new RawObject($value);

                        break;

                    case "hidden":  // Boolean
                        $params[$key] = (bool) $value;

                        break;

                    case "sort":    // [SortInput!]
                        $values = explode(',', $value);
                        $params[$key] = [
                            'field' => new RawObject($values[0]),
                            'order' => new RawObject($values[1])
                        ];

                        break;

                    case "searchFields":    //[SearchFieldsInput!]
                        if (strpos($value, ',')) {
                            $params[$key] = explode(',', $value);

                            // convert all values to raw objects
                            foreach ($params[$key] as $index => $val)
                                $params[$key][$index] = new RawObject($val);
                        }

                        else
                            $params[$key] = new RawObject($value);

                        break;
                    default:
                        break;
                }
            }
            catch (Exception $e) { }
        }

        return $this->build_params_array($params);
    }

    private function build_params_array($params) {
        error_reporting(E_ERROR | E_PARSE);

        $params_arr = [];

        foreach ($params as $key => $value) {
            try {
                $param_str = '';

                if (is_array($value)) {
                    if (in_array($key, $this->array_filters)) {
                        $param_str .= '[';

                        $sub_params = [];
                        foreach ($value as $val_val) {
                            $sub_param_str = '{';

                            foreach ($val_val as $v_k => $v_v) {
                                if (is_numeric($v_k))
                                    $sub_param_str .= $v_v . ' ';
                                else
                                    $sub_param_str .= $v_k . ':' . $v_v . ' ';
                            }

                            $sub_param_str .= '}';

                            $sub_params[] = $sub_param_str;
                        }

                        $param_str .= implode(',', $sub_params);

                        $param_str .= ']';
                    }
                    else {
                        $param_str_open = '{';
                        $param_str_close = '}';

                        $tmp_arr = [];
                        foreach ($value as $val_key => $val_val) {
                            if (is_numeric($val_key)) {
                                $tmp_arr[] = $val_val;
                                $param_str_open = '[';
                                $param_str_close = ']';
                            }
                            else
                                $tmp_arr[] = $val_key . ':' . $val_val;
                        }

                        $param_str .= $param_str_open;
                        $param_str .= implode(',', $tmp_arr);
                        $param_str .= $param_str_close;
                    }
                }
                else {
                    $param_str .= $value;
                }

                $params_arr[$key] = new RawObject($param_str);
            }
            catch (Exception $e) {}
        }

        return $params_arr;
    }

    public function process_filters($applied_filters) {
        error_reporting(E_ERROR | E_PARSE);

        $processed = [];
        $val = '';

        foreach ($applied_filters as $key => $value) {
            try {
                $filter = new stdClass();

                if (strpos($key, '_from')) {
                    $range_filter_from_chunks = explode('_', $key);
                    $key = $range_filter_from_chunks[0];

                    $range_filter_to = $applied_filters[$key . "_to"];

                    $temp_val = [
                        $value,
                        $range_filter_to
                    ];
                    $value =  $temp_val;

                    if ($key == 'price')
                        $filter->type = 'price';

                    $val = implode(',', $temp_val);
                }
                else {
                    if (strpos($key, '_from') || (strpos($key, '_to')))
                        continue;


                    if (is_array($value) && sizeof($value)) {
                        $flt_vals = [];
                        foreach ($value as $vals) {

                            if (strpos($vals, '~')) {
                                $tmp_vals = explode('~', $vals);
                                $filter->type = $tmp_vals[1];
                                $flt_vals[] = urldecode($tmp_vals[0]);
                            }
                        }

                        $val = implode(',', $flt_vals);
                    }
                    else if (strpos($value, '~')) {
                        if (strpos($value, '^')) {
                            $tmp_array = explode('^', $value);
                            $tmp_val_arr = [];

                            foreach ($tmp_array as $tmp_filters) {
                                $tmp_vals = explode('~', $tmp_filters);

                                $filter->type = $tmp_vals[1];
                                $tmp_val_arr[] = urldecode($tmp_vals[0]);
                            }

                            $val = implode('+', $tmp_val_arr);
                        }
                        else {
                            $tmp_vals = explode('~', $value);
                            $filter->type = $tmp_vals[1];
                            $val = urldecode($tmp_vals[0]);
                        }
                    }
                    else {
                        $val = $value;
                    }
                }

                $param = $val;

                if (isset($filter->type) && $filter->type == 'price')
                    $param = $val;
                else {
                    if (isset($filter->type) && !empty($filter->type)) {
                        if (is_array($val)) {
                            $processed[$key] = $val;
                        }
                        else {
                            $param = "$key=$val;$filter->type";
                        }
                    }
                    else {
                        $processed[$key] = "$val";
                    }
                }


                $param_type = '';

                if (isset($filter->type) && !empty($filter->type)) {
                    switch ($filter->type) {
                        case 'text':
                        case 'list':
                        case 'enum':
                        case 'enumlist':
                        case 'color':
                        case 'date':
                        case 'datetime':
                        case 'object':
                            $param_type = self::TEXT_FILTERS_KEY;
                            break;

                        case 'price':
                            $param_type = 'price';
                            break;

                        case 'integer':
                        case 'decimal':
                            $param_type = self::RANGE_FILTERS_KEY;
                            break;
                    }
                }

                if (isset($processed[$param_type]))
                    $processed[$param_type] .= "|$param";
                else
                    $processed[$param_type] = "$param";
            }
            catch (Exception $e) { }
        }

        return $processed;
    }

    protected function get_selected_filters($all_filters) {
        $selected_filters = [];

        foreach ($all_filters as $type => $type_filters) {
            foreach ($type_filters as $filter) {

                if (isset($filter->searchId) && isset($_REQUEST[$filter->searchId])) {
                    $filter_vals = explode('^', sanitize_text_field( $_REQUEST[$filter->searchId] ));
					$filter_vals = wp_unslash($filter_vals);

                    $available_vals = [];

                    switch ($filter->type) {
                        case 'text':
                        case 'list':
                        case 'enum':
                        case 'enumlist':
                            $available_vals = $filter->textFilter;

                            break;
                        default:
                            break;
                    }

                    foreach ($available_vals as $val_obj) {
                        if (in_array(wp_unslash($val_obj->value) . '~' . $type, $filter_vals)) {
                            $sel_filter = new stdClass();
                            $sel_filter->filter = $filter;
                            $sel_filter->value = wp_unslash($val_obj->value);

                            $selected_filters[] = $sel_filter;
                        }
                    }

                }
            }
        }

        return $selected_filters;
    }

	/**
	 * Check the front-end request
	 *
	 * @param $nonce_data_key
	 * @param string $nonce_action_key
	 *
	 * @return false|int
	 */
	protected function validate_form_request( $nonce_data_key, $nonce_action_key = PROPELLER_NONCE_KEY_FRONTEND ) {
		$nonce = isset( $_REQUEST[ $nonce_data_key ] ) ? sanitize_text_field($_REQUEST[ $nonce_data_key ]) : false;

		if ( false === $nonce ) {
			return false;
		}

		return wp_verify_nonce( $nonce, $nonce_action_key );
	}

	/**
	 * Validates the ajax request
	 * @param string $nonce_data_key
	 * @param string $nonce_action_key
	 *
	 * @return bool
	 */
	protected function validate_ajax_request( $nonce_data_key, $nonce_action_key = PROPELLER_NONCE_KEY_FRONTEND ) {
		return (bool) check_ajax_referer($nonce_action_key, $nonce_data_key, false);
	}

	/**
	 * Check if request is post
	 * @return bool
	 */
	protected function is_post_request() {
		return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
	}

}