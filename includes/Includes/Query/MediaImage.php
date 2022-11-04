<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;
use GraphQL\RawObject;

class MediaImage {
    public static $query;

    static function setDefaultQueryData($transform_options) {
        ImageVariant::setDefaultQueryData();

        self::$query = [
            "productId",
            (new Query('alt'))
                ->setArguments(['language' => PROPELLER_LANG])
                ->setSelectionSet(
                [
                    "value",
                    "language"   
                ]
            ),
            (new Query('description'))
                ->setArguments(['language' => PROPELLER_LANG])
                ->setSelectionSet(
                [
                    "value",
                    "language"   
                ]
            ),
            (new Query('tags'))
                ->setArguments(['language' => PROPELLER_LANG])
                ->setSelectionSet(
                [
                    "values",
                    "language"   
                ]
            ),
            "type",
            "createdAt",
            "priority",
            (new Query('imageVariants'))
                ->setArguments([
                    "input" => new RawObject($transform_options)
                ])
                ->setSelectionSet(
                    ImageVariant::$query
            )
        ];
    }

    static function setSearchOptions($args) {
        $props = [];
        
        if (isset($args['description'])) $props[] = 'description: { ' . $args['description'] . ' }';
        if (isset($args['tag'])) $props[] = 'tag: { ' . $args['tag'] . ' }';
        if (isset($args['sort'])) $props[] = 'sort: ' . $args['sort'];
        if (isset($args['page'])) $props[] = 'page: ' . $args['page'];
        if (isset($args['offset'])) $props[] = 'offset: ' . $args['offset'];
        

        $search = '{ ' . implode(',', $props) . ' }';

        return new RawObject($search);
    }
}