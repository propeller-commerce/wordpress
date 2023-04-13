<?php

namespace Propeller\Includes\Model;

class SitemapModel extends BaseModel {
    public function __construct() {
        
    }

    public function get_menu_structure($base_catalog_id, $language, $depth) {
        $category_gql = "
        categories {
            id
            urlId: categoryId
            name(language: \"$language\") {
                value
                language
            }
            slug(language: \"$language\") {
                value
                language
            }
        ";

        $root_gql = "
            query {
                category(id: $base_catalog_id) {
        ";

        for ($i = 0; $i < $depth; $i++) {
            $root_gql .= $category_gql;
        }

        for ($i = 0; $i < $depth; $i++) {
            $root_gql .= " } ";
        }

        $root_gql .= "}
            }
        ";
        
        return $root_gql;
    }

    public function get_products($language, $offset = 12, $page = 1) {
        $gql = <<<QUERY
            query {
                products (offset: $offset, page: $page, language: "$language") {
                    itemsFound
                    offset
                    page
                    pages
                    start
                    end
                    items {
                        class
                        slug(language: "$language") {
                            value
                            language
                        }
                        ... on Product {
                            urlId: productId
                            dateChanged
                            manufacturer
                        }
                        ... on Cluster {
                            urlId: clusterId
                        }
                    }
                }
            }        
        QUERY;

        return $gql;
    }
}