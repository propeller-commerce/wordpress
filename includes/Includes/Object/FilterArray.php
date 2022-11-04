<?php
namespace Propeller\Includes\Object;

use Propeller\Includes\Object\Filter;

class FilterArray {
    public $attributes;

    public function __construct($attr_array) {
        $this->attributes = [];

        foreach ($attr_array as $attr) {
            if ($attr->isSearchable)
                $this->attributes[] = new Filter($attr);
        }
    }

    public function get_non_empty_attrs() {
        $attrs = [];

        foreach ($this->attributes as $attr) {
            if ($attr->has_value())
                $attrs[] = $attr;
        }

        return $attrs;
    }
}