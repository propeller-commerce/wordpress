<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;
use Propeller\Includes\Enum\ImageFit;
use Propeller\Includes\Enum\ImageFormat;
use Propeller\Includes\Enum\MediaImagesType;
use Propeller\Includes\Enum\MediaSort;

class MediaImages {
    static function setDefaultQueryData($params) {

        MediaImage::setDefaultQueryData($params);
        
        return [
            (new Query('items'))
                ->setSelectionSet(
                    MediaImage::$query
                ),
            "itemsFound",
            "offset",
            "page",
            "pages",
            "start",
            "end"
        ];
    }

    static function get_media_images_query($args) {
        $search_params = [
            'sort' => (isset($args['sort']) ? sanitize_text_field( $args['sort']) : MediaSort::ASC),
            'page' => (isset($args['page']) ? (int) $args['page'] : 1),
            'offset' => (isset($args['offset']) ? (int) $args['offset'] : 12)
        ];

        $width = PROPELLER_PRODUCT_IMG_MEDIUM_WIDTH;
        $height = PROPELLER_PRODUCT_IMG_MEDIUM_HEIGHT;

        if (isset($args['name'])) {
            switch ($args['name']) {
                case MediaImagesType::SMALL: 
                    $width = PROPELLER_PRODUCT_IMG_SMALL_WIDTH;
                    $height = PROPELLER_PRODUCT_IMG_SMALL_HEIGHT;

                    break;
                case MediaImagesType::MEDIUM: 
                    $width = PROPELLER_PRODUCT_IMG_MEDIUM_WIDTH;
                    $height = PROPELLER_PRODUCT_IMG_MEDIUM_HEIGHT;

                    break;
                case MediaImagesType::LARGE: 
                    $width = PROPELLER_PRODUCT_IMG_LARGE_WIDTH;
                    $height = PROPELLER_PRODUCT_IMG_LARGE_HEIGHT;

                    break;
                default: 
                    $width = PROPELLER_PRODUCT_IMG_MEDIUM_WIDTH;
                    $height = PROPELLER_PRODUCT_IMG_MEDIUM_HEIGHT;

                    break;
            }
        }

        if (isset($args['width'])) 
            $width = $args['width'];

        if (isset($args['height'])) 
            $height = $args['height'];

        $transformation_params = [
            'format' => (isset($args['format']) ? $args['format'] : ImageFormat::WEBP),
            'height' => $height,
            'width' => $width,
            'name' => (isset($args['name']) ? $args['name'] : MediaImagesType::MEDIUM),
            'fit' => ImageFit::BOUNDS
        ];

        $search_args = MediaImage::setSearchOptions($search_params);
        $transform_args = ImageVariant::setTransformations([ImageVariant::setTransformationOptions($transformation_params)]);

        return (new Query('images'))
            ->setArguments([
                "search" => $search_args
            ])   
            ->setSelectionSet(
                MediaImages::setDefaultQueryData($transform_args)
            );
    }
}