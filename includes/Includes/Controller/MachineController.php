<?php
namespace Propeller\Includes\Controller;

use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\MediaType;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\FilterArray;
use Propeller\Includes\Object\Machine;
use Propeller\Includes\Object\SparePart;
use Propeller\Includes\Query\Media;
use Propeller\PropellerUtils;
use stdClass;

class MachineController extends BaseController {
    protected $model;
    public $data;
    public $machines;
    public $attributes;
    public $filters;
    public $parts;

    public $offset_arr = [12, 24, 48];
    public $sort_arr = [];
    public $sort_order = [];

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('machine');
    }

    public function machine_title($data) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-machine-listing-title.php');   
    }

    public function machine_description($data) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-machine-listing-description.php');   
    }

    public function machine_listing_grid($obj, $machines, $parts, $paging_data, $sort, $prop_name, $prop_value, $do_action) {
        $display_class = isset($_REQUEST['view']) && !empty($_REQUEST['view']) ? sanitize_text_field($_REQUEST['view']) : 'blocks';

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-machine-grid.php');
    }

    public function machine_card($machine, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-machine-card.php');
    }

    public function machine_listing_pre_grid($data, $obj, $sort, $prop_name, $prop_value, $do_action) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-machine-pre-grid.php');
    }

    // public function machine_gecommerce_listing($products, $obj) {
    //     require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-gecommerce-listing.php');
    // }

    public function machine_listing_machines($machines, $parts, $obj) {
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-machine-listing-machines.php');
    }

    public function machine_listing_pagination($paging_data, $prop_name, $prop_value, $do_action) {
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

    public function machine_menu($data) {
        $back_url = null;

        $slug = get_query_var('slug');

        if (is_array($slug) && count($slug) > 1) {
            unset($slug[count($slug) - 1]);
            
            $back_url = $this->buildUrl(PageController::get_slug(PageType::MACHINES_PAGE), implode('/', $slug));
        }

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'propeller-machine-listing-categories.php');
    }
    
    public function buildMachineUrl($uri, $slug) {
        return home_url($uri . $slug . '/');
    }

    public function machine_listing($applied_filters = [], $is_ajax = false) {
        global $propel;

        ini_set('xdebug.var_display_max_depth', -1);
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);

        if (!$applied_filters || !sizeof($applied_filters))
            $applied_filters = PropellerUtils::sanitize($_REQUEST);

        $filters_applied = $this->process_filters($applied_filters);
        $qry_params = $this->build_search_arguments($filters_applied);

        $sort = isset($applied_filters['sort']) && !empty($applied_filters['sort']) ? $applied_filters['sort'] : array_key_first($this->sort_arr) . ',' . array_key_first($this->sort_order);

        $slug = isset($applied_filters['slug']) ? $applied_filters['slug'] : get_query_var('slug');

        $style = isset($applied_filters['view']) ? $applied_filters['view'] : 'blocks';

        if (is_array($slug))
            $slug = $slug[count($slug) - 1];

        $this->data = isset($propel['data']) 
            ? $propel['data'] 
            : (empty($slug) ? $this->get_installations($qry_params, $is_ajax) : $this->get_machines($slug, null, $qry_params, $is_ajax));
        
        $this->machines = [];
        
        if (isset($this->data->items)) {
            foreach ($this->data->items as $machine) {
                if (!count($machine->slug)) {
                    $this->data->itemsFound--;
                    continue;
                }
                
                $this->machines[] = new Machine($machine);
            }
        }
        else if (isset($this->data->machines)) {
            foreach ($this->data->machines as $machine) {
                if (!count($machine->slug)) {
                    $this->data->itemsFound--;
                    continue;
                }
                
                $this->machines[] = new Machine($machine);
            }
        }

        $this->parts = new stdClass();
        $this->parts->itemsFound = 0;
        $part_items = [];

        if (isset($this->data->parts) && isset($this->data->parts->items)) {
            $this->parts = $this->data->parts;
            
            foreach ($this->data->parts->items as $part) {
                if (!is_object($part->product) || !count($part->product->slug)) {
                    continue;
                }
                
                $part_items[] = new SparePart($part);
            }
        }    
        
        $this->parts->items = $part_items;

        $this->attributes = [];
        if (isset($this->data->parts->filters))
            $this->attributes = new FilterArray($this->data->parts->filters);

        $this->filters = new FilterController($this->attributes, [$this->data->parts->minPrice, $this->data->parts->maxPrice]);
        $this->filters->set_slug($slug);
        $this->filters->set_action('do_machine');
        $this->filters->set_prop('slug');
        $this->filters->set_liststyle($style);

        $this->pagename = PageController::get_slug(PageType::MACHINES_PAGE);

        $paging_data = $this->data;
        $do_action = "do_machine";
        $prop_name = "slug";
        $prop_value = $slug;
        $obid = "";

        ob_start();

        if ($is_ajax) {
            $response = new stdClass();

            apply_filters('propel_machine_grid', $this, $this->machines, $this->parts, $paging_data, $sort, $prop_name, $prop_value, $do_action);
            $response->content = ob_get_clean();

            ob_start();
            apply_filters('propel_category_filters', $this->filters);
            $filters_content = ob_get_clean();

            $response->filters = $filters_content;

            return $response;
        }
        else {
            require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-machine-listing.php');
        }

        return ob_get_clean();
    }

    public function get_installations($qry_params, $is_ajax = false) {
        $type = 'machines';

        if ($is_ajax) 
            $qry_params = $this->build_search_arguments($qry_params);

        $gql = $this->model->get_installations(
            $qry_params,
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );

        return $this->query($gql, $type);
    }

    public function get_machines($slug, $machineId = null, $qry_params = null, $is_ajax = false) {
        $type = 'machine';

        // if ($is_ajax) 
        //     $qry_params = $this->build_search_arguments($qry_params);

        $gql = $this->model->get_machines(
            $machineId 
                ? ['id' => (int) $machineId, 'language' => PROPELLER_LANG] 
                : ['slug' => $slug, 'language' => PROPELLER_LANG],
            $qry_params, 
            Media::get([
                'name' => MediaImagesType::MEDIUM
            ], MediaType::IMAGES)->__toString(),
            PROPELLER_LANG
        );

        // $this->dump($gql);

        return $this->query($gql, $type);
    }
}