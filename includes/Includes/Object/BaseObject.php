<?php
namespace Propeller\Includes\Object;

class BaseObject {
    public function __construct($object) {
        if (is_object($object) && !is_array($object))
            $this->merge_object($object);
    }

    protected function merge_object($object) {
        foreach($object as $key => $value) 
            $this->$key = $value;
    }
}