<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;
use Propeller\Includes\Enum\MediaSort;

class MediaDocuments {
    static function setDefaultQueryData() {

        MediaDocument::setDefaultQueryData();
        
        return [
            (new Query('items'))
                ->setSelectionSet(
                    MediaDocument::$query
                ),
            "itemsFound",
            "offset",
            "page",
            "pages",
            "start",
            "end"
        ];
    }

    static function get_media_documents_query($args) {
        $search_params = [
            'sort' => (isset($args['sort']) ? $args['sort'] : MediaSort::ASC), 
            'page' => (isset($args['page']) ? $args['page'] : 1),
            'offset' => (isset($args['offset']) ? $args['offset'] : 12) 
        ];

        $search_args = MediaDocument::setSearchOptions($search_params);

        return (new Query('documents'))
            ->setArguments([
                "search" => $search_args
            ])   
            ->setSelectionSet(
                MediaDocuments::setDefaultQueryData()
            );
    }
}