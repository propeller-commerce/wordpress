<?php
namespace Propeller\Includes\Controller;

use GraphQL\RawObject;
use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Cluster;
use Propeller\Includes\Object\FilterArray;
use Propeller\Includes\Object\Product;
use Propeller\Includes\Query\MediaImages;
use stdClass;

class CategoryController extends BaseController {
    
    protected $type = 'category';
    static $category_slug;
    protected $base_catalog_id;

    protected $data;
    protected $products;
    protected $attributes;
    protected $filters;

    protected $model;

    const FILTERS_FLASH_KEY = 'filters'; 
    
    public $offset_arr = [12, 24, 48];
    public $sort_arr = [];
    public $sort_order = [];

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('category');

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

        self::$category_slug = PageController::get_slug(PageType::CATEGORY_PAGE);

        if (defined('PROPELLER_BASE_CATALOG'))
            $this->base_catalog_id = PROPELLER_BASE_CATALOG;
    }

    /*
        Category filters
    */
    public function category_title($data) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-product-listing-title.php');
    }

    public function product_listing_pre_grid($data, $obj, $sort, $prop_name, $prop_value, $do_action) {
        global $wp;

        $pagename = PageController::get_slug(PageType::CATEGORY_PAGE); // search/brand/category

        if (isset($wp->query_vars) && isset($wp->query_vars['pagename']))
            $pagename = $wp->query_vars['pagename'];
        else
            $pagename = $this->pagename;

        switch ($pagename) {
            case PageController::get_slug(PageType::CATEGORY_PAGE): 
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-catalog-pre-grid.php');
    
                break;
            case PageController::get_slug(PageType::SEARCH_PAGE): 
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-search-pre-grid.php');
    
                break;
            case PageController::get_slug(PageType::BRAND_PAGE): 
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-brand-pre-grid.php');
    
                break;
            default: 
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-catalog-pre-grid.php');
        
                break;
        }
    }

    public function category_menu() {
        $menucontroller = new MenuController();
        $menuItems = $menucontroller->getMenu();

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-product-listing-categories.php');
    }

    public function category_filters($filters) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-filter-container.php');
    }

    public function category_listing_grid($obj, $products, $paging_data, $sort, $prop_name, $prop_value, $do_action) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-grid.php');
    }

    public function category_gecommerce_listing($products, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-gecommerce-listing.php');
    }

    public function category_listing_products($products, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-product-listing-products.php');
    }

    public function category_listing_pagination($paging_data, $prop_name, $prop_value, $do_action) {
        $prev = $paging_data->page - 1;
        $prev_disabled = false;
        
        if ($prev < 1) {
            $prev = 1;
            $prev_disabled = 'disabled';
        }
            
        $next = $paging_data->page + 1;
        $next_disabled = false;
        
        if ($next >= $paging_data->pages) {
            $next = $paging_data->pages;

            if ($paging_data->page == $next)
                $next_disabled = 'disabled';
        }

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-pagination.php');
    }

    public function category_listing_description($data) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-product-listing-description.php');
    }

    /*
        Category shortcodes
    */
    public function product_listing($applied_filters = [], $is_ajax = false) {
        global $propel;
        
        if (!$applied_filters || !sizeof($applied_filters))
            $applied_filters = $_REQUEST;

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $sort = isset($applied_filters['sort']) && !empty($applied_filters['sort']) ? $applied_filters['sort'] : array_key_first($this->sort_arr) . ',' . array_key_first($this->sort_order);
        
        $slug = isset($applied_filters['slug']) ? $applied_filters['slug'] : get_query_var('slug');
        
        $this->data = isset($propel['data']) 
            ? $propel['data'] 
            : $this->get_catalog($slug, $qry_params);

        $this->products = [];

        foreach ($this->data->products->items as $product) {
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
        if ($this->data->products->filters)
            $this->attributes = new FilterArray($this->data->products->filters);

        $this->filters = new FilterController($this->attributes, [$this->data->products->minPrice, $this->data->products->maxPrice]);
        $this->filters->set_slug($slug);
        $this->filters->set_action('do_filter');
        $this->filters->set_prop('slug');

        $paging_data = $this->data->products;
        
        $this->pagename = PageController::get_slug(PageType::CATEGORY_PAGE);

        $do_action = "do_filter";
        $prop_name = "slug";
        $prop_value = $slug;

        $this->title = $this->data->name[0]->value;

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
        else
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-product-listing.php');

        return ob_get_clean();
    }

    public function get_catalog($idOrSlug = null, $args = []) {
        $queryArgument = 'id';

        if (!$idOrSlug)
            $idOrSlug = $this->base_catalog_id;
        else
            $queryArgument = is_numeric($idOrSlug) ? 'id' : 'slug';

        $gql = $this->model->get_catalog(
            [$queryArgument => $idOrSlug], 
            $args,
            ['filter' => new RawObject('{ name: ["PRODUCT_LABEL_1", "PRODUCT_LABEL_2"]}')],
            MediaImages::get_media_images_query([
                'name' => MediaImagesType::MEDIUM
            ])->__toString(),
            PROPELLER_LANG
        );

        return $this->query($gql, $this->type);
    }

    public function get_category($id) {
        $gql = $this->model->get_category(['categoryId' => $id], PROPELLER_LANG);

        return $this->query($gql, $this->type);
    }
}