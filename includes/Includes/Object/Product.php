<?php
namespace Propeller\Includes\Object;

use stdClass;

class Product extends BaseObject {

    public $attributes = [];
    public $images = [];

    public function __construct($product) {
        parent::__construct($product);

        if (isset($product->attributes) && count($product->attributes)) {
            $attrs = new AttributeArray($product->attributes);
            $this->attributes = $attrs->get_non_empty_attrs();
        }

        if ($this->has_images())
            $this->get_image_variants();
    }

    public function has_images() {        
        return isset($this->mediaImages) && $this->mediaImages->itemsFound > 0;
    }

    public function get_image_variants() {
        if ($this->has_images()) {
            foreach ($this->mediaImages->items as $image) {
                $img = new stdClass();

                $img->alt = $image->alt;
                $img->description = $image->description;
                $img->tags = $image->tags;
                $img->type = $image->type;
                $img->createdAt = $image->createdAt;
                $img->priority = $image->priority;

                if (isset($image->imageVariants) && count($image->imageVariants)) {
                    $img->images = $image->imageVariants;

                    $this->images[] = $img;
                }
            }
        }
    }

    public function has_attributes() {
        return count($this->attributes) > 0;
    }

    public function get_attributes() {
        return $this->attributes;
    }
}