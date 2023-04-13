<?php
namespace Propeller\Includes\Object;

class Attribute extends BaseObject {
    private const ATTR_TEXT         = 'text';
    private const ATTR_LIST         = 'list';
    private const ATTR_ENUM         = 'enum';
    private const ATTR_ENUMLIST     = 'enumlist';
    private const ATTR_COLOR        = 'color';
    private const ATTR_DATE         = 'date';
    private const ATTR_DATETIME     = 'datetime';
    private const ATTR_OBJECT       = 'object';
    private const ATTR_INTEGER      = 'integer';
    private const ATTR_DECIMAL      = 'decimal';

    public function __construct($attr) {
        parent::__construct($attr);
    }

    public function has_value() {
        switch ($this->get_type()) {
            case self::ATTR_TEXT: 
                return $this->hasTextValue();
            case self::ATTR_LIST: 
                return $this->hasTextValue();
            case self::ATTR_ENUM: 
                return $this->hasEnumValue();
            case self::ATTR_ENUMLIST: 
                return $this->hasEnumValue();
            case self::ATTR_INTEGER:
                return $this->hasIntValue();
            case self::ATTR_DECIMAL:
                return $this->hasDecimalValue();
            case self::ATTR_DATE:
                return $this->hasDateValue();
        }
        
        return false;
    }

    public function get_type() {
        return $this->attributeDescription->type;
    }

    public function get_description() {
        $found = array_filter($this->attributeDescription->description, function($obj) { return strtolower($obj->language) == strtolower(PROPELLER_LANG); });

        if (!count($found)) 
            $found = array_filter($this->attributeDescription->description, function($obj) { return strtolower($obj->language) == strtolower(PROPELLER_FALLBACK_LANG); });
            
        if (count($found))
            return current($found)->value;

        return '';
    }

    public function is_searchable() {
        return $this->attributeDescription->isSearchable;
    }

    public function is_public() {
        return $this->attributeDescription->isPublic;
    }

    public function is_hidden() {
        return $this->attributeDescription->isHidden;
    }

    public function get_value() {
        switch ($this->get_type()) {
            case self::ATTR_TEXT: 
                return $this->getTextValue();
            case self::ATTR_LIST: 
                return $this->getTextValue();
            case self::ATTR_ENUM: 
                return $this->getEnumValue();
            case self::ATTR_ENUMLIST: 
                return $this->getEnumValue();
            case self::ATTR_INTEGER:
                return $this->getIntValue();
            case self::ATTR_DECIMAL:
                return $this->getDecimalValue();
            case self::ATTR_DATE:
                return $this->getDateValue();
        }
        
        return null;
    }

    // int attr
    private function hasIntValue() {
        return count($this->intValue) > 0;
    }

    private function getIntValue() {
        return implode(', ', $this->intValue);
    }


    // decimal attr
    private function hasDecimalValue() {
        return isset($this->decimalValue) && is_array($this->decimalValue) && count($this->decimalValue) > 0;
    }

    private function getDecimalValue() {
        return implode(', ', $this->decimalValue);
    }


    // date attr 
    private function hasDateValue() {
        return strlen($this->dateValue);
    }

    private function getDateValue() {
        return $this->dateValue;
    }

    // text attr
    private function hasTextValue() {
        return is_array($this->textValue) && sizeof($this->textValue) &&
               sizeof($this->textValue[0]->values) && strlen($this->textValue[0]->values[0]);
    }

    private function getTextValue() {
        $found = array_filter($this->textValue, function($obj) { 
            return strtolower($obj->language) == strtolower(PROPELLER_LANG) && 
                   count($obj->values) && $this->has_array_values($obj->values); 
        });

        if (!count($found)) 
            $found = array_filter($this->textValue, function($obj) { 
                return strtolower($obj->language) == strtolower(PROPELLER_FALLBACK_LANG) && 
                       count($obj->values) && $this->has_array_values($obj->values); 
            });
            
        if (count($found))
            return implode(', ', current($found)->values);

        return '';
    }

    private function has_array_values($vals_arr) {
        foreach ($vals_arr as $val) {
            if (!empty($val))
                return true;
        }

        return false;
    }


    // enum attr
    private function hasEnumValue() {
        return $this->enumValue && is_array($this->enumValue) && sizeof($this->enumValue) && $this->has_array_values($this->enumValue);
    }

    private function getEnumValue() {
        if ($this->hasEnumValue()) {
            $values = [];

            foreach ($this->enumValue as $val) {
                if (!empty(trim($val)))
                    $values[] = $val;
            }

            return join(', ', $values); 
        }
        
        return '';
    }
}