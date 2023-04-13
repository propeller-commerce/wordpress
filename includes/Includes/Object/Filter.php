<?php
namespace Propeller\Includes\Object;

class Filter extends BaseObject {
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
            case self::ATTR_ENUM:
                return $this->hasTextValue();
            case self::ATTR_LIST:
            case self::ATTR_ENUMLIST:
                return $this->hasListValue();
            case self::ATTR_INTEGER:
                return $this->hasIntValue();
            case self::ATTR_DECIMAL:
                return $this->hasDecimalValue();
        }
        
        return false;
    }

    public function get_type() {
        return $this->type;
    }

    public function is_searchable() {
        return $this->isSearchable;
    }

    public function is_public() {
        return $this->isPublic;
    }

    public function is_hidden() {
        return $this->isHidden;
    }

    public function get_value() {
        switch ($this->get_type()) {
            case self::ATTR_TEXT: 
            case self::ATTR_ENUM:
                return $this->getTextValue();
            case self::ATTR_LIST:
            case self::ATTR_ENUMLIST:
                return $this->getListValue();
            case self::ATTR_INTEGER:
                return $this->getIntValue();
            case self::ATTR_DECIMAL:
                return $this->getDecimalValue();
        }
        
        return null;
    }

    // int attr
    private function hasIntValue() {
        return $this->integerRangeFilter != null && 
               is_numeric($this->integerRangeFilter->min) && 
               is_numeric($this->integerRangeFilter->max);
    }

    private function getIntValue() {
        return [
            'min' => $this->integerRangeFilter->min,
            'max' => $this->integerRangeFilter->max
        ];
    }


    // decimal attr
    private function hasDecimalValue() {
        return $this->decimalRangeFilter != null && 
               is_numeric($this->decimalRangeFilter->min) && 
               is_numeric($this->decimalRangeFilter->max);
    }

    private function getDecimalValue() {
        return [
            'min' => $this->decimalRangeFilter->min,
            'max' => $this->decimalRangeFilter->max
        ];
    }


    // text attr
    private function hasTextValue() {
        $has_value = false;

        if (is_array($this->textFilter) && sizeof($this->textFilter)) {
            foreach ($this->textFilter as $text_filter) {
                if (strlen($text_filter->value) && ($text_filter->count > 0 || $text_filter->countActive > 0)) {
                    $has_value = true;
                    break;
                }
            }
        }
        
        return $has_value;
    }

    private function getTextValue() {
        return trim($this->textFilter);
    }

    // list attr
    private function hasListValue() {
        if (is_array($this->textFilter) && count($this->textFilter))
            return (count($this->textFilter) > 0);

        return false;
    }

    private function getListValue() {
        $vals = [];

        foreach ($this->textFilter as $values) {
            $vals[] = [
                'value' => trim($values->value),
                'count' => $values->count
            ];
        }


        return $vals;
    }
}