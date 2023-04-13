<?php
namespace Propeller\Includes\Object;

use stdClass;

class Product extends BaseObject {

    public $attributes = [];
    public $trackAttributes = [];
    public $attributeItems;
    public $trackAttributeItems;
    public $images = [];

    public function __construct($product) {
        parent::__construct($product);

        if (isset($product->trackAttributes) && isset($product->trackAttributes->itemsFound) && $product->trackAttributes->itemsFound > 0) {
            $attrs = new AttributeArray($product->trackAttributes->items);
            $this->trackAttributes = $attrs->get_non_empty_attrs();

            $this->trackAttributeItems = new stdClass();

            $this->trackAttributeItems->itemsFound = $product->trackAttributes->itemsFound;
            $this->trackAttributeItems->offset = $product->trackAttributes->offset;
            $this->trackAttributeItems->page = $product->trackAttributes->page;
            $this->trackAttributeItems->pages = $product->trackAttributes->pages;
            $this->trackAttributeItems->start = $product->trackAttributes->start;
            $this->trackAttributeItems->end = $product->trackAttributes->end;
        }

        if (isset($product->attributes) && !is_array($product->attributes) && $product->attributes->itemsFound > 0) {
            $attrs = new AttributeArray($product->attributes->items);
            $this->attributes = $attrs->get_non_empty_attrs();

            $this->attributeItems = new stdClass();

            $this->attributeItems->itemsFound = $product->attributes->itemsFound;
            $this->attributeItems->offset = $product->attributes->offset;
            $this->attributeItems->page = $product->attributes->page;
            $this->attributeItems->pages = $product->attributes->pages;
            $this->attributeItems->start = $product->attributes->start;
            $this->attributeItems->end = $product->attributes->end;
        }

        if ($this->has_images())
            $this->get_image_variants();

        if ($this->has_videos())
            $this->get_videos();

        if ($this->has_documents())
            $this->get_documents();
    }

    public function has_images() {        
        return isset($this->media) && isset($this->media->images) && $this->media->images->itemsFound > 0;
    }

    public function has_videos() {        
        return isset($this->media) && isset($this->media->videos) && $this->media->videos->itemsFound > 0;
    }

    public function has_documents() {        
        return isset($this->media) && isset($this->media->documents) && $this->media->documents->itemsFound > 0;
    }

    public function get_image_variants() {
        if ($this->has_images()) {
            foreach ($this->media->images->items as $image) {
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

    public function get_videos() {
        if ($this->has_videos()) {
            foreach ($this->media->videos->items as $video) {
                $vid = new stdClass();

                $vid->alt = $video->alt;
                $vid->description = $video->description;
                $vid->tags = $video->tags;
                $vid->type = $video->type;
                $vid->createdAt = $video->createdAt;
                $vid->priority = $video->priority;

                if (isset($video->videos) && count($video->videos)) {
                    $vid->videos = $video->videos;

                    $this->videos[] = $vid;
                }
            }
        }
    }

    public function get_documents() {
        if ($this->has_documents()) {
            foreach ($this->media->documents->items as $document) {
                $doc = new stdClass();

                $doc->alt = $document->alt;
                $doc->description = $document->description;
                $doc->tags = $document->tags;
                $doc->type = $document->type;
                $doc->createdAt = $document->createdAt;
                $doc->priority = $document->priority;

                if (isset($document->documents) && count($document->documents)) {
                    $doc->documents = $document->documents;

                    $this->documents[] = $doc;
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