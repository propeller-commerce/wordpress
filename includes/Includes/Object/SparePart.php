<?php
namespace Propeller\Includes\Object;

class SparePart extends BaseObject {
    public $images = [];
    public $product;

    public function __construct($part) {
        parent::__construct($part);

        if (isset($part->product))
            $this->product = new Product($part->product);
    }
}