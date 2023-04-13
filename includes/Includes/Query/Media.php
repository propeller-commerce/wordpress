<?php

namespace Propeller\Includes\Query;

use GraphQL\Query;
use Propeller\Includes\Enum\MediaType;

class Media {
    public static $query;

    static function get($params, $type) {
        $media_query = null;

        switch ($type) {
            case MediaType::IMAGES: 
                $media_query = MediaImages::get_media_images_query($params);

                break;
            case MediaType::VIDEOS: 
                $media_query = MediaVideos::get_media_videos_query($params);
                
                break;
            case MediaType::DOCUMENTS: 
                $media_query = MediaDocuments::get_media_documents_query($params);

                break;
        }

        return 
            (new Query('media'))
                ->setSelectionSet(
                [
                    $media_query
                ]
            );
    }
}