<?php

namespace Propeller\Includes\Controller;

use GraphQL\RawObject;
use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\MediaType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\AttributeArray;
use Propeller\Includes\Object\Cluster;
use Propeller\Includes\Object\FilterArray;
use Propeller\Includes\Object\Product;
use Propeller\Includes\Query\Media;
use Propeller\PropellerUtils;
use stdClass;
use TRP_Translate_Press;

class ProductController extends BaseController {
    protected $type = 'product';
    static $product_slug;
    public $product;
    public $attributes = [];
    
    public $offset_arr = [12, 24, 48];
    public $sort_arr = [];
    
    public $sort_order = [];

    protected $model;
    public $pagename;

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('product');

        $this->sort_arr = [
            "dateChanged" => __('Date changed', 'propeller-ecommerce'),
            "dateCreated" => __('Date created', 'propeller-ecommerce'),
            "name" => __('Name', 'propeller-ecommerce'),
            "price" => __('Price', 'propeller-ecommerce'),
            "relevance" => __('Relevance', 'propeller-ecommerce'),
            "sku" => __('SKU', 'propeller-ecommerce'),
            "supplierCode" => __('Supplier code', 'propeller-ecommerce'),
        ];

        $this->sort_order = [
            "asc" => __('Asc', 'propeller-ecommerce'),
            "desc" => __('Desc', 'propeller-ecommerce'),
        ];

        self::$product_slug = PageController::get_slug(PageType::PRODUCT_PAGE);
    }

    /*

        Product actions

    */
    public function product_price($product, $quantity = 1, $is_bulk = false) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-price.php');
    }

    public function cluster_price($product, $quantity = 1, $is_bulk = false) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-price.php');
    }
    

    /* 
    
        Product filters 
    
    */
    public function product_gecommerce($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-gecommerce.php');
    }

    public function product_gallery($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-gallery.php');
    }

    public function product_name($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-name.php');
    }

    public function product_meta($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-meta.php');
    }

    public function product_name_mobile($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-name-mobile.php');
    }

    public function product_meta_mobile($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-meta-mobile.php');
    }

    public function product_desc_media($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-desc-media.php');
    }

    public function product_price_details($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-price-details.php');
    }

    public function product_stock($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-stock.php');
    }

    public function product_short_desc($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-short-desc.php');
    }

    public function product_bulk_prices($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-bulk-prices.php');
    }

    public function product_bundles($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-bundles.php');
    }

    public function product_crossupsells($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-crossupsells.php');
    }

    public function product_crossupsell_card($crossupsell, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-card-crossupsell.php');
    }

    public function product_crossupsells_ajax_items($product, $obj, $type) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-crossupsells-ajax-items.php');
    }

    public function product_add_favorite($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-add-favorite.php');
    }

    public function product_add_to_basket($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-add-to-basket.php');
    }

    public function product_description($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-description.php');
    }

    public function product_downloads($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-downloads.php');
    }

    public function product_videos($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-videos.php');
    }

    public function product_specifications($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-specifications.php');
    }

    public function product_downloads_content($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-downloads-content.php');
    }

    public function product_videos_content($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-videos-content.php');
    }

    public function product_specifications_content($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-specifications-content.php');
    }

    public function product_specifications_rows($product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-specifications-rows.php');
    }

    public function cluster_name($cluster) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-name.php');
    }

    public function cluster_name_mobile($cluster) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-name-mobile.php');
    }

    public function cluster_product_name($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-product-name.php');
    }

    public function cluster_product_name_mobile($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-product-name-mobile.php');
    }

    public function cluster_meta($cluster, $cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-meta.php');
    }

    public function cluster_meta_mobile($cluster, $cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-meta-mobile.php');
    }

    public function cluster_gallery($cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-gallery.php');
    }

    public function cluster_desc_media($cluster, $cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-desc-media.php');
    }

    public function cluster_description($cluster) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-description.php');
    }

    public function cluster_specifications($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-specifications.php');
    }

    public function cluster_downloads($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-downloads.php');
    }

    public function cluster_videos($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-videos.php');
    }

    public function cluster_price_details($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-price-details.php');
    }

    public function cluster_stock($cluster_product) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-stock.php');
    }

    public function cluster_gecommerce($cluster, $cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-gecommerce.php');
    }

    public function cluster_bundles($cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-bundles.php');
    }

    public function cluster_crossupsells($cluster, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-crossupsells.php');
    }

    public function cluster_crossupsell_card($crossupsell, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-card-crossupsell.php');
    }

    public function cluster_crossupsells_ajax_items($cluster, $obj, $type) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-crossupsells-ajax-items.php');
    }

    public function cluster_options($cluster, $cluster_product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-options-' . $cluster->cluster_type . '.php');
    }
    
    public function cluster_add_favorite($cluster) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-add-favorite.php');
    }

    public function product_card($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-card.php');
    }

    public function cluster_card($product, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-cluster-card.php');
    }
    
    public function crossupsells_ajax($obj, $type) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-crossupsells-ajax.php');
    }
    
    
    /* 
    
        Product shortcodes 
    
    */
    public function product_details($is_ajax = false) {
        global $propel, $wp_query;
        
        $productId = null;

        if (PROPELLER_ID_IN_URL) {
            if (isset($wp_query->query_vars) && isset($wp_query->query_vars['obid']) && is_numeric($wp_query->query_vars['obid']))
                $productId = (int) $wp_query->query_vars['obid'];
        }

        $data = isset($propel['data']) 
            ? $propel['data'] 
            : $this->get_product(get_query_var('slug'), $productId, [
                'attribute' => '{ isPublic: true }'
            ]);

        if ($data->class == 'product')
            $this->product = new Product($data);
            
        if ($data->class == 'cluster')
            $this->product = new Cluster($data);

        $this->slug = get_query_var('slug');

        ob_start();
        
        require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-' . $this->product->class . '-details.php');
        
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function cluster_details($slug, $cluster_id = 0) {
        $data = null;

        if ($cluster_id > 0) {
            $cluster_transient = PROPELLER_VIEWING_CLUSTER . '_' . $cluster_id;

            if (false === ($data = CacheController::get($cluster_transient))) {
                $data = $this->get_cluster($slug, $cluster_id, [
                    'attribute' => '{ isPublic: true }'
                ]);

                CacheController::set($cluster_transient, $data, 30 * MINUTE_IN_SECONDS);
            }
        }

        if ($data) {
            $this->product = new Cluster($data);
            $this->slug = $this->product->slug[0]->value;

            ob_start();
    
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-cluster-details.php');
            $content = ob_get_contents();
            ob_end_clean();
    
            $this->slug = $slug;
    
            return $content;
        }
        
        return '';
    }

    public function load_crossupsells($slug, $class, $crossupsell_type) {
        $data = $class == 'product' 
            ? $this->load_product_crossupsells($slug, $crossupsell_type)
            : $this->load_cluster_crossupsells($slug, $crossupsell_type);

        ob_start();

        apply_filters('propel_' . $class . '_crossupsells_ajax_items', $data, $this, $crossupsell_type);
        
        $content = ob_get_clean();
        
        ob_end_clean();
        
        return $content;
    }

    private function load_product_crossupsells($slug, $crossupsell_type) {
        $type = 'product';

        $gql = $this->model->crossupsells(
            ['slug' => $slug, 'language' => PROPELLER_LANG],
            ['filter' => new RawObject('{ isPublic: true }')],
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG,
            $crossupsell_type,
            $type
        );

        return $this->query($gql, $type);
    }

    private function load_cluster_crossupsells($slug, $crossupsell_type) {
        $type = 'cluster';

        $gql = $this->model->crossupsells(
            ['slug' => $slug, 'language' => PROPELLER_LANG],
            ['filter' => new RawObject('{ isPublic: true }')],
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG,
            $crossupsell_type,
            $type
        );

        return $this->query($gql, $type);
    }

    public function load_specifications($product_id, $page = 1, $offset = 12) {
        $type = 'product';

        $gql = $this->model->specifications($product_id, ['filter' => new RawObject('{ isPublic: true, offset: 1000 }')]);

        $product = new Product($this->query($gql, $type));

        ob_start();

        $page == 1 
            ? apply_filters('propel_product_specifications_content', $product)
            : apply_filters('propel_product_specifications_rows', $product);
        
        $content = ob_get_clean();
        
        ob_end_clean();
        
        return $content;
    }

    public function load_downloads($product_id) {
        $type = 'product';

        $gql = $this->model->downloads($product_id, Media::get(null, MediaType::DOCUMENTS));
        
        $product = new Product($this->query($gql, $type));

        ob_start();

        apply_filters('propel_product_downloads_content', $product);
        
        $content = ob_get_clean();
        
        ob_end_clean();
        
        return $content;
    }
    
    public function load_videos($product_id) {
        $type = 'product';

        $gql = $this->model->videos($product_id, Media::get(null, MediaType::VIDEOS));
        
        $product = new Product($this->query($gql, $type));

        ob_start();

        apply_filters('propel_product_videos_content', $product);
        
        $content = ob_get_clean();
        
        ob_end_clean();
        
        return $content;
    }
    
    public function search_products() {

	    static $search_id = null;
	    if(is_null($search_id)) {
		    $search_id = 0;
	    }
	    $search_id++;

        ob_start();
        
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-product-search.php');
        
        return ob_get_clean();
    }

    public function search($applied_filters = [], $is_ajax = false) {
        global $propel;

        if (!$applied_filters || !sizeof($applied_filters))
            $applied_filters = PropellerUtils::sanitize($_REQUEST);

        $applied_filters['language'] = PROPELLER_LANG;

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $term = isset($applied_filters['term']) ? $applied_filters['term'] : get_query_var('term');
        $term = urldecode($term);

        if (!empty($term))
            $qry_params['term'] = $term;

        if (!isset($qry_params['language']))
            $qry_params['language'] = PROPELLER_LANG;

        $sort_params = isset($applied_filters['sort']) && !empty($applied_filters['sort']) ? explode(',', $applied_filters['sort']) : '';
        $sort = is_array($sort_params) && !empty($sort_params[0]) ? $sort_params[0] : array_key_first($this->sort_arr);
        $order = is_array($sort_params) && !empty($sort_params[1]) ? $sort_params[1] : array_key_first($this->sort_order);
        $style = isset($applied_filters['view']) ? $applied_filters['view'] : 'blocks';

        $this->data = isset($propel['data']) 
            ? $propel['data'] 
            : $this->get_products($qry_params);

        $this->products = [];
        
        foreach ($this->data->items as $product) {
            if (!count($product->slug) || ($product->class == 'product' && $product->status == 'N')) {
                $this->data->products->itemsFound--;
                continue;
            }
            
            if ($product->class == 'product')
                $this->products[] = new Product($product);
            if ($product->class == 'cluster')
                $this->products[] = new Cluster($product);
        }

        $this->attributes = [];
        if ($this->data->filters)
            $this->attributes = new FilterArray($this->data->filters);

        $this->filters = new FilterController($this->attributes, [$this->data->minPrice, $this->data->maxPrice]);
        $this->filters->set_slug($term);
        $this->filters->set_action("do_search");
        $this->filters->set_prop('term');
        $this->filters->set_liststyle($style);

        $this->search_categories = [];
        foreach ($this->products as $product) {
            if (isset($product->category) && is_object($product->category)) {
                if (!isset($this->search_categories[$product->category->categoryId])) {
                    $cat = new stdClass();
                    $cat->id = $product->category->categoryId;
                    $cat->items = 1;
    
                    $this->search_categories[$product->category->categoryId] = $cat;
                }
                else {
                    $cat = $this->search_categories[$product->category->categoryId];
                    $cat->items++;
                }
            }
        }
        
        $this->pagename = PageController::get_slug(PageType::SEARCH_PAGE);

        $paging_data = $this->data;
        $do_action = "do_search";
        $prop_name = "term";
        $prop_value = $term;

        ob_start();

        if ($is_ajax) {
            $response = new stdClass();

            apply_filters('propel_category_grid', $this, $this->products, $paging_data, $sort, $prop_name, $prop_value, $do_action);
            $response->content = ob_get_clean();

            ob_start();
            apply_filters('propel_category_filters', $this->filters);
            $filters_content = ob_get_clean();

            $response->filters = $filters_content;

            return $response;
        }
        else {
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-search-results.php');
        }

        return ob_get_clean();
    }

    public function global_product_search($applied_filters = [], $is_ajax = false) {
        if (!$applied_filters || !sizeof($applied_filters))
            $applied_filters = PropellerUtils::sanitize($_REQUEST);

        $term = isset($applied_filters['term']) ? $applied_filters['term'] : get_query_var('term');

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $term = urldecode($term);
        $qry_params['term'] = $term;

        $results = $this->global_search_products($qry_params);

        return $results;
    }

    public function brand($applied_filters = [], $is_ajax = false) {
        global $propel;

        if (!$applied_filters || !sizeof($applied_filters))
            $applied_filters = PropellerUtils::sanitize($_REQUEST);

        $applied_filters['language'] = PROPELLER_LANG;

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $term = isset($applied_filters['manufacturer']) ? $applied_filters['manufacturer'] : get_query_var('manufacturer');
        $term = urldecode($term);

        if (!empty($term))
            $qry_params['manufacturer'] = $term;

        if (!isset($qry_params['language']))
            $qry_params['language'] = PROPELLER_LANG;

        $sort_params = isset($applied_filters['sort']) && !empty($applied_filters['sort']) ? explode(',', $applied_filters['sort']) : '';
        $sort = is_array($sort_params) && !empty($sort_params[0]) ? $sort_params[0] : array_key_first($this->sort_arr);
        $order = is_array($sort_params) && !empty($sort_params[1]) ? $sort_params[1] : array_key_first($this->sort_order);
        $style = isset($applied_filters['view']) ? $applied_filters['view'] : 'blocks';

        $this->data = isset($propel['data']) 
            ? $propel['data']
            : $this->get_products($qry_params);

        $this->products = [];

        foreach ($this->data->items as $product) {
            if (!count($product->slug) || ($product->class == 'product' && $product->status == 'N')) {
                $this->data->products->itemsFound--;
                continue;
            }
            
            if ($product->class == 'product')
                $this->products[] = new Product($product);
            if ($product->class == 'cluster')
                $this->products[] = new Cluster($product);
        }
         
        $this->attributes = [];
        if ($this->data->filters)
            $this->attributes = new FilterArray($this->data->filters);

        $this->filters = new FilterController($this->attributes, [$this->data->minPrice, $this->data->maxPrice]);
        $this->filters->set_slug($term);
        $this->filters->set_prop('manufacturer');
        $this->filters->set_action("do_brand");
        $this->filters->set_liststyle($style);

        $this->search_categories = [];
        foreach ($this->products as $product) {
            if (!isset($this->search_categories[$product->category->categoryId])) {
                $cat = new stdClass();
                $cat->id = $product->category->categoryId;
                $cat->items = 1;

                $this->search_categories[$product->category->categoryId] = $cat;
            }
            else {
                $cat = $this->search_categories[$product->category->categoryId];
                $cat->items++;
            }
        }
        
        $this->pagename = PageController::get_slug(PageType::BRAND_PAGE);

        $paging_data = $this->data;
        $do_action = "do_brand";
        $prop_name = "manufacturer";
        $prop_value = $term;

        ob_start();

        if ($is_ajax) {
            $response = new stdClass();

            apply_filters('propel_category_grid', $this, $this->products, $paging_data, $sort, $prop_name, $prop_value, $do_action);
            $response->content = ob_get_clean();

            ob_start();
            apply_filters('propel_category_filters', $this->filters);
            $filters_content = ob_get_clean();

            $response->filters = $filters_content;

            return $response;
        }
        else {
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-brand-listing.php');
        }

        return ob_get_clean();
    }

    public function brand_listing_content($applied_filters = [], $is_ajax = false) {
        ob_start();

        if (!$applied_filters || !sizeof($applied_filters)) {
	        $applied_filters = PropellerUtils::sanitize( $_REQUEST );
        }

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $term = isset($applied_filters['manufacturer']) ? $applied_filters['manufacturer'] : get_query_var('manufacturer');
        $term = urldecode($term);

        if (!empty($term))
            $qry_params['manufacturer'] = $term;
        
            require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-brand-listing-content.php');

        return ob_get_clean();
    }

    public function product_slider($atts = [], $content = null) {

        $arguments = shortcode_atts(
            [
                "type" => '',                   // custom predefined values, like "recently_viewed", etc
                "manufacturer" => '',           // [String!]
                "supplier" => '',               // [String!]
                "brand" => '',                  // [String!]
                "categoryId" => 0,              // Int
                "class" => '',                  // ProductClass: product/cluster
                "tag" => '',                    // [String!]
                "page" => 1,                    // Int = 1 = 1
                "offset" => 12,                 // Int = 12 = 12
                "languagege" => PROPELLER_LANG,
                "attribute" => '',              // [TextFilterInput!]
                "status" => new RawObject('A'), // [ProductStatus!] = [ "A" ] = [A]
                "hidden" => false,              // Boolean
                "sort" => '',                   // [SortInput!]
                "id" => 0,                      // [Int!]
            ], 
            $atts
        );
        
        $slider_template = $this->partials_dir . DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-product-slider.php';
        $do_search = true;
        if ($arguments['type'] != '') {
            switch ($arguments['type']) {
                case 'recently_viewed':
                    $slider_template = $this->partials_dir . DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-recent-slider.php';
                    
                    $do_search = false;
        
                    add_action('wp_enqueue_scripts', function() {
                        wp_enqueue_style('slick_theme_css', $this->assets_dir . '/css/lib/slick-theme.css', array(), null, 'all' );
                        wp_enqueue_style('slick_css', $this->assets_dir . '/css/lib/slick.css', array(), null, 'all' );
                    });
                    
                    break;
                default: 
                    break;
            }
        }



	    $no_results = false;
        $products = [];

        if ($do_search) {
            $qry_params = $this->build_search_arguments($arguments);
            $products_data = $this->get_slider_products($qry_params);
            

            foreach ($products_data->items as $product) {
                if (!count($product->slug) || ($product->class == 'product' && $product->status == 'N'))
                    continue;
                
                if ($product->class == 'product')
                    $products[] = new Product($product);
                if ($product->class == 'cluster')
                    $products[] = new Cluster($product);
            }
                
        }
        else 
            $no_results = true;

	    ob_start();

	    require $slider_template;

        return ob_get_clean();
    }

    public function get_recently_viewed_products($arguments) {
        $products = [];
        $qry_params = $this->build_search_arguments($arguments);

        $products_data = $this->get_slider_products($qry_params);

        foreach ($products_data->items as $product) {
            if (!count($product->slug) || ($product->class == 'product' && $product->status == 'N'))
                continue;
            
            if ($product->class == 'product')
                $products[] = new Product($product);
            if ($product->class == 'cluster')
                $products[] = new Cluster($product);
        }

        ob_start();

        include $this->partials_dir . '/other/propeller-recent-viewed-products.php';

        return ob_get_clean();
    }

    /**
     * Temporary function, checks if /product/$slug is product or cluster
     */
    private function check_product($slug, $productId = null) {
        $class = null;

        $product_gql = $this->model->check_product(
            $productId 
                ? ['productId' => (int) $productId, 'language' => PROPELLER_LANG] 
                : ['slug' => $slug, 'language' => PROPELLER_LANG]
        );
        
        $product_check_response = $this->query($product_gql, 'product', false, false);
        
        if (isset($product_check_response->class))
            $class = $product_check_response->class;
        else {
            $cluster_gql = $this->model->check_cluster(
                $productId 
                    ? ['clusterId' => (int) $productId, 'language' => PROPELLER_LANG] 
                    : ['slug' => $slug, 'language' => PROPELLER_LANG]
            );

            $cluster_check_response = $this->query($cluster_gql, 'cluster', false, false);

            if (isset($cluster_check_response->class))
                $class = $cluster_check_response->class;
        }
        
        return $class;
    }

    private function check_product_language($slug, $productId = null, $type = 'product') {
        $language = PROPELLER_LANG;

        $gql = $this->model->check_product_language($slug, $productId, $type, $language);

        $response = $this->query($gql, $type);

        $return = new stdClass();

        if (is_object($response) && count($response->slugs)) {
            $found = array_filter($response->slugs, function($obj) use ($language) { 
                return strtolower($obj->language) == strtolower($language); 
            });
    
            if (!count($found)) {
                $return->exists = false;
                

                $default_lang = PROPELLER_DEFAULT_LOCALE;

                if (strpos($default_lang, '_'))
                    $default_lang = explode('_', $default_lang)[1];

                $default_found = array_filter($response->slugs, function($obj) use ($default_lang) { 
                    return strtolower($obj->language) == strtolower($default_lang); 
                });

                if (count($default_found))
                    $return->languages = [ current($default_found) ];
                else 
                    $return->languages = $response->slugs;
            }
            else
                $return->exists = true;
        }
        else {
            $return->exists = false;
            // $return->languages = $response->slugs;
        }

        return $return;
    }

    public function get_product_default_lang_url($language, $original_url) {
        if (class_exists('TRP_Translate_Press')) {
            $trp = TRP_Translate_Press::get_trp_instance();
            $url_converter = $trp->get_component( 'url_converter' );
    
            // It's recommended to keep third parameter of the get_url_for_language() an empty string.
            $url = $url_converter->get_url_for_language( $language, $original_url, '' );
    
            return $url;
        }

        return null;
    }

    public function get_product($slug, $productId = null, $args = []) {
        $class = $this->check_product($slug, $productId);

        if ($class == 'cluster')
            return $this->get_cluster($slug, $productId, $args);

        $product_data = $this->check_product_language($slug, $productId, 'product');


        if ($product_data->exists) {
            $gql = $this->model->get_product(
                $productId 
                    ? ['productId' => (int) $productId, 'language' => PROPELLER_LANG] 
                    : ['slug' => $slug, 'language' => PROPELLER_LANG],
                ['filter' => new RawObject('{ isPublic: true }')],
                Media::get([
                    'name' => MediaImagesType::LARGE
                ], MediaType::IMAGES)->__toString(),
                PROPELLER_LANG
            );
    
            return $this->query($gql, $this->type);
        }
        else {
            return $product_data;
        }
    }

    public function get_cluster($slug, $clusterId = null, $args = []) {
        $type = 'cluster';
        $data = null;
        
        if ($clusterId) {
            $cluster_transient = PROPELLER_VIEWING_CLUSTER . '_' . $clusterId;

            if (false !== ($data = CacheController::get($cluster_transient)))
                return $data;
        }

        $product_data = $this->check_product_language($slug, $clusterId, 'cluster');

        if ($product_data->exists) {
            $attrs_gql = $this->model->get_cluster_attributes($clusterId 
                ? ['clusterId' => (int) $clusterId, 'language' => PROPELLER_LANG] 
                : ['slug' => $slug, 'language' => PROPELLER_LANG], PROPELLER_LANG);
            $attr_data = $this->query($attrs_gql, $type);

            $attr_names = [];
            $attr_offset = 12;

            if (isset($attr_data->drillDown) && count($attr_data->drillDown)) {
                foreach ($attr_data->drillDown as $dd)
                    $attr_names[] = $dd->attribute->name;
            }

            $attr_offset = count($attr_names);

            $gql = $this->model->get_cluster(
                $clusterId 
                    ? ['clusterId' => (int) $clusterId, 'language' => PROPELLER_LANG] 
                    : ['slug' => $slug, 'language' => PROPELLER_LANG],
                count($attr_names) 
                    ? ['filter' => new RawObject('{ name: ["' . implode('", "', $attr_names) . '"], offset: ' . $attr_offset . ' }')]
                    : NULL,
                Media::get([
                    'name' => MediaImagesType::LARGE
                ], MediaType::IMAGES)->__toString(),
                PROPELLER_LANG
            );

            $data = $this->query($gql, $type);

            $this->preserve_cluster($data);
            
            return $data;
        }
        else {
            return $product_data;
        }
    }

    public function get_products($qry_params, $is_ajax = false) {
        $type = 'products';

        //$qry_params['hidden'] = new RawObject('false');
        
        if ($is_ajax) 
            $qry_params = $this->build_search_arguments($qry_params);

        if (!isset($qry_params['language']))
            $qry_params['language'] = PROPELLER_LANG;

        $gql = $this->model->get_products(
            $qry_params,
            ['filter' => new RawObject('{ name: ["PRODUCT_LABEL_1", "PRODUCT_LABEL_2"]}')],
            ['filter' => new RawObject('{ isSearchable: true }')],
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );
            
        return $this->query($gql, $type);
    }

    public function get_slider_products($qry_params, $is_ajax = false) {
        $type = 'products';

        if ($is_ajax) 
            $qry_params = $this->build_search_arguments($qry_params);

        if (!isset($qry_params['language']))
            $qry_params['language'] = PROPELLER_LANG;

        $gql = $this->model->get_slider_products(
            $qry_params, 
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG);
            
        return $this->query($gql, $type);
    }

    public function global_search_products($qry_params, $is_ajax = false) {
        $type = 'products';

        $qry_params = $this->build_search_arguments($qry_params);
        
        $qry_params['offset'] = PROPELLER_SEARCH_SUGGESTIONS;
        $qry_params['page'] = 1;

        if (!isset($qry_params['language']))
            $qry_params['language'] = PROPELLER_LANG;

        $searchGql = $this->model->global_search_products(
            $qry_params,
            Media::get([
                'name' => MediaImagesType::SMALL
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );

        return $this->query($searchGql, $type);
    }

    public function preserve_recently_viewed($product_id) {
        $cookie = $this->get_cookie(PROPELLER_RECENT_PRODS_COOKIE);
        $products = [];

        if ($cookie) 
            $products = explode(',', $cookie);

        if (!in_array($product_id, $products))
            array_unshift($products, $product_id);

        if (sizeof($products) > 12)
            array_pop($products);

        $this->set_cookie(PROPELLER_RECENT_PRODS_COOKIE, implode(',', $products));
    }

    public function preserve_cluster($data) {
        $cluster_transient = PROPELLER_VIEWING_CLUSTER . '_' . $data->clusterId;

        if (false === ($cluster_data = CacheController::get($cluster_transient)))
            CacheController::set($cluster_transient, $data, 30 * MINUTE_IN_SECONDS);
    }
}