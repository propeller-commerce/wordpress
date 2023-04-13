<?php
namespace Propeller\Includes\Controller;

use stdClass;

class FilterController extends BaseController {

    protected $type = 'filter';
    protected $slug = '';
    protected $action = '';
    protected $prop = '';
    protected $liststyle = 'blocks';
    protected $obid = '';
    protected $attr_filters;
    public $filters = [];
    
    
    public function __construct($attrs, $prices = []) {
        parent::__construct();

        $this->attr_filters = [];
        
        $tmp_filters = [];

        if (is_array($prices) && sizeof($prices)) {
            $price_filter = new stdClass();

            $price_filter->type = 'price';
            $price_filter->min = $prices[0];
            $price_filter->max = $prices[1];

            $tmp_filters[] = $price_filter;
        }

        if ($attrs)
            $this->attr_filters = array_merge($tmp_filters, $attrs->get_non_empty_attrs());
        
        $this->categorize();
    }

    public function getAttributeFilters() {
        return $this->attr_filters;
    }

    public function getFilters() {
        return $this->filters;
    }

    public function draw() {
        foreach ($this->filters as $type => $filters_arr) {

            foreach ($filters_arr as $filter) {
                require $this->load_template('partials', DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR . 'filter' . DIRECTORY_SEPARATOR . 'propeller-filter-' . $type . '.php');
            }
        }
    }

    private function categorize() {
        foreach ($this->attr_filters as $attr) {
            if (!isset($this->filters[$attr->type]))
                $this->filters[$attr->type] = [];
            
            $this->filters[$attr->type][] = $attr;
        }
    }

    public function set_slug($slug) {
        $this->slug = $slug;
    }

    public function set_action($action) {
        $this->action = $action;
    }

    public function set_prop($name) {
        $this->prop = $name;
    }

    public function set_liststyle($style) {
        $this->liststyle = $style;
    }

    public function set_obid($obid) {
        $this->obid = $obid;
    }

    public function get_slug() {
        return $this->slug;
    }

    public function get_action() {
        return $this->action;
    }

    public function get_prop() {
        return $this->prop;
    }

    public function get_liststyle() {
        return $this->liststyle;
    }

    public function get_obid() {
        return $this->obid;
    }
}