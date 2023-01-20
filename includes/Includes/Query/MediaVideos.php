<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;
use Propeller\Includes\Enum\MediaSort;

class MediaVideos {
    static function setDefaultQueryData() {

        MediaVideo::setDefaultQueryData();
        
        return [
            (new Query('items'))
                ->setSelectionSet(
                    MediaVideo::$query
                ),
            "itemsFound",
            "offset",
            "page",
            "pages",
            "start",
            "end"
        ];
    }

    static function get_media_videos_query($args) {
        $search_params = [
            'sort' => (isset($args['sort']) ? $args['sort'] : MediaSort::ASC), 
            'page' => (isset($args['page']) ? $args['page'] : 1),
            'offset' => (isset($args['offset']) ? $args['offset'] : 12) 
        ];

        $search_args = MediaVideo::setSearchOptions($search_params);

        return (new Query('videos'))
            ->setArguments([
                "search" => $search_args
            ])   
            ->setSelectionSet(
                MediaVideos::setDefaultQueryData()
            );
    }
}