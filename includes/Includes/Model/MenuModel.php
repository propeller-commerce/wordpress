<?php

namespace Propeller\Includes\Model;

class MenuModel {
    public function __construct() {
        
    }

    public function get_menu($base_catalog_id, $language) {
        $gql = <<<QUERY
            query {
                category(id: $base_catalog_id) {
                    id
                    categoryId
                    urlId: categoryId
                    name(language: "$language") {
                        value
                        language
                    }
                    slug(language: "$language") {
                        value
                        language
                    }
                    categories {
                        id
                        categoryId
                        urlId: categoryId
                        name(language: "$language") {
                            value
                            language
                        }
                        slug(language: "$language") {
                            value
                            language
                        }
                        categories {
                            id
                            categoryId
                            urlId: categoryId
                            name(language: "$language") {
                                value
                                language
                            }
                            slug(language: "$language") {
                                value
                                language
                            }
                            categories {
                                id
                                categoryId
                                urlId: categoryId
                                name(language: "$language") {
                                    value
                                    language
                                }
                                slug(language: "$language") {
                                    value
                                    language
                                }
                            }
                        }
                    }
                }
            }
        QUERY;

        return $gql;
    }
}