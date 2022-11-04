<?php
namespace Propeller\Includes\Object;

use Propeller\Includes\Object\Attribute;

class AttributeArray {
    public $attributes;

    public function __construct($attr_array) {
        $this->attributes = [];

        foreach ($attr_array as $attr)
            $this->attributes[] = new Attribute($attr);
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