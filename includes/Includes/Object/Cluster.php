<?php
namespace Propeller\Includes\Object;

use stdClass;

class Cluster extends BaseObject {
    public $cluster_type = 'normal';

    public $_options = [];
    public $formatted_options = [];
    public $selected_options = [];
    public $drilldowns = [];

    public function __construct($cluster, $initialize = true) {
        parent::__construct($cluster);

        if (isset($this->options) && $this->options && count($this->options)) {
            $option_arr = [];

            foreach ($this->options as $option) {
                $options_product_arr = [];

                foreach ($option->products as $product) {
                    $options_product_arr[] = new Product($product);

                    // set option default product
                    if (isset($option->defaultProduct->productId) && $option->defaultProduct->productId == $product->productId)
                        $option->defaultProduct = new Product($product);
                }

                $option->products = $options_product_arr;

                $option_arr[] = $option;
            }

            $this->options = $option_arr;
        }

        if (isset($this->products) && $this->products && count($this->products)) {
            $product_arr = [];

            foreach ($this->products as $product) {
                $product_arr[] = new Product($product);

                // set option default product
                if (isset($this->defaultProduct->productId) && $this->defaultProduct->productId == $product->productId)
                    $this->defaultProduct = new Product($product);
                
            }
                
            $this->products = $product_arr;
        }
        
        if (!$this->defaultProduct)
            $this->defaultProduct = $this->products[0];            

        if ($initialize) {
            if (!$this->options && !$this->drillDown)
                $this->cluster_type = 'normal';
            else if (!$this->options && $this->drillDown)
                $this->cluster_type = 'linear';
            else if ($this->options && $this->drillDown)
                $this->cluster_type = 'configurable';

            if ($this->cluster_type != 'normal')
                $this->init_cluster();
        }
    }

    public function init_cluster() {

	    $cluster_id = !empty($_POST['cluster_id']) ? sanitize_text_field($_POST['cluster_id']) : null;

	    if (is_null($cluster_id)) {
		    $this->set_default_product_options();
	    }

        $this->collect_options_values();
        $this->get_formatted_options();
        $this->get_selected_options();
        
        $this->reformat_options();

        $this->get_configured_product();
    }

    public function get_products() {
        return $this->products;
    }

    public function get_options() {
        return $this->options;
    }

    public function has_options() {
        return $this->options && count($this->_options) > 0;
    }

    public function has_crossupsells() {
        return isset($this->crossupsells) && is_array($this->crossupsells) && count($this->crossupsells) > 0;
    }

    private function set_default_product_options() {
        if (isset($this->drillDown) && $this->drillDown) {
            foreach ($this->drillDown as $drilldown) {
                $attr_name = $drilldown->attributeId;
                $this->drilldowns[] = $attr_name;

                $attr_found = array_filter($this->defaultProduct->attributes, function($obj) use ($attr_name) { 
                    return $obj->attributeDescription->name == $attr_name; 
                });

                if (count($attr_found)) {
                    $attr = current($attr_found);

                    $_REQUEST[$attr_name] = $attr->get_value();
                    
                    // break;
                }
            }
        }
    }

    public function collect_options_values() {
        if (isset($this->drillDown) && $this->drillDown) {
            usort($this->drillDown, function($obj1, $obj2) {
                return $obj1->priority > $obj2->priority;
            });
    
            foreach ($this->drillDown as $drilldown) {
                $attr_name = $drilldown->attributeId;
                $this->drilldowns[] = $attr_name;

                foreach ($this->get_products() as $product) {
                    $attr_found = array_filter($product->attributes, function($obj) use ($attr_name) { 
                        return $obj->attributeDescription->name == $attr_name; 
                    });

                    if (count($attr_found)) {
                        $attr = current($attr_found);
        
                        $obj = new stdClass();
                        $obj->name = $attr_name;
                        $obj->value = $attr->get_value();
                        $obj->priority = $drilldown->priority;
                        $obj->display = $drilldown->displayType;
                        $obj->label = $drilldown->attribute->description[0]->value;
        
                        $this->_options[$attr_name][] = $obj; 
                    }
                }
            }
        }
    }

    public function get_formatted_options() {
        foreach ($this->_options as $key => $option) {
            $this->_options[$key] = array_unique($this->_options[$key], SORT_REGULAR);
    
            foreach ($this->_options[$key] as $opt) {
                if (!isset($this->formatted_options[$key])) {
                    $this->formatted_options[$key] = new stdClass();
    
                    $this->formatted_options[$key]->name = $opt->name;
                    $this->formatted_options[$key]->display = $opt->display;
                    $this->formatted_options[$key]->label = $opt->label;
                        
                    $this->formatted_options[$key]->values[] = $opt->value;
                }
                else {
                    $this->formatted_options[$key]->values[] = $opt->value;
                }
            }
        }

        return $this->formatted_options;
    }

    public function get_selected_options() {
        for ($i = 0; $i < count($this->drilldowns); $i++) {
            $sel_option = new stdClass();
    
            if (isset($_REQUEST[$this->drilldowns[$i]])) {
                $sel_option->name = $this->drilldowns[$i];
                $sel_option->value = sanitize_text_field( $_REQUEST[$this->drilldowns[$i]]);
            }
            else {
                if (isset($this->formatted_options[$this->drilldowns[$i]])) {
                    $sel_option->name = $this->formatted_options[$this->drilldowns[$i]]->name;
                    $sel_option->value = $this->formatted_options[$this->drilldowns[$i]]->values[0];
                }
            }
    
            $this->selected_options[] = $sel_option;
        }

        return $this->selected_options;
    }

    public function get_configured_product() {
        $found_product = null;

        foreach ($this->get_products() as $product) {
            $fount_attrs = [];
            
            if (count($this->selected_options) && isset($this->selected_options[0]->name)) {
                foreach ($this->selected_options as $option) {
                    $attr_name = $option->name;
                    $attr_value = $option->value;
        
                    $attr_found = array_filter($product->attributes, function($obj) use ($attr_name, $attr_value) { 
                        return $obj->attributeDescription->name == $attr_name && $obj->get_value() == $attr_value; 
                    });
        
                    if (count($attr_found))
                        $found_attrs[] = current($attr_found); 
                }
                
                if (isset($found_attrs) && count($found_attrs) == count($this->selected_options)) {
                    $found_product = $product;
                    break;
                }
        
                unset($found_attrs);
            }
        }

        $this->defaultProduct = $found_product;

        return $found_product;
    }

    public function reformat_options() {
        if (count($this->selected_options) && isset($this->selected_options[0]->name)) {
            $first_option = $this->selected_options[0];

            if (isset($_REQUEST['clicked_attr'])) {
                $first_option = new stdClass();
    
                $first_option->name = sanitize_text_field($_REQUEST['clicked_attr']);
                $first_option->value = sanitize_text_field($_REQUEST['clicked_val']);
            }
                
            $new_options = [];
            
            for ($i = 0; $i < count($this->drillDown); $i++) {
                if ($this->drillDown[$i]->attributeId == $first_option->name)
                    continue;
    
                $attr_name = $this->drillDown[$i]->attributeId;
                $this->drilldowns[] = $attr_name;
    
                foreach ($this->get_products() as $product) {
                    $first_attr_found = array_filter($product->attributes, function($obj) use ($first_option) { 
                        return $obj->attributeDescription->name == $first_option->name && $obj->get_value() == $first_option->value; 
                    });
    
                    if (count($first_attr_found)) {
                        $attr_found = array_filter($product->attributes, function($obj) use ($attr_name) { 
                            return $obj->attributeDescription->name == $attr_name; 
                        });
                
                        if (count($attr_found)) {
                            $attr = current($attr_found);
            
                            $new_options[$attr_name][] = $attr->get_value(); 
                        }
                    }
                }
            }
    
            foreach ($new_options as $key => $vals) {
                for ($i = 0; $i < count($this->formatted_options[$key]->values); $i++) {
                    if (!in_array($this->formatted_options[$key]->values[$i], $vals))
                        unset($this->formatted_options[$key]->values[$i]);
                }
            }
        }
    }
}