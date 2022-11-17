<?php
namespace Propeller;

use Propeller\Includes\Controller\BaseController;
use Propeller\Includes\Controller\CategoryController;
use Propeller\Includes\Controller\OrderController;
use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\ProductController;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\ShoppingCartController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Enum\PaymentStatuses;

class RequestHandler {
    public function process($query_vars) {
        global $propel, $wp_query;

        if (isset($query_vars['pagename'])) {
            switch ($query_vars['pagename']) {
                case PageController::get_slug(PageType::CATEGORY_PAGE): 
                    $ref = 'Propeller\Custom\Includes\Controller\CategoryController';

                    $categoryObj = class_exists($ref, true) 
                        ? new $ref() 
                        : new CategoryController();
    
                    $applied_filters = $_REQUEST;
    
                    $filters_applied = $categoryObj->process_filters($applied_filters);
                    $qry_params = $categoryObj->build_search_arguments($filters_applied);
    
                    $slug = isset($applied_filters['slug']) ? $applied_filters['slug'] : $query_vars['slug'];
                    
                    $data = $categoryObj->get_catalog($slug, $qry_params);
    
                    if (!is_object($data)) {
                        error_log(print_r($data, true));
                        $propel['error_404'] = 'Category not found';
                    }
                    else {
                        $propel['url_slugs'] = $data->slugs;
                    
                        $propel['data'] = $data;
                        $propel['title'] = $data->name[0]->value;
                        $propel['description'] = $data->description[0]->value;
    
                        $propel['breadcrumbs'] = $this->pack_breadcrumbs($data, PageController::get_slug(PageType::CATEGORY_PAGE));
    
                        $propel['meta'] = [
                            'title' => $data->name[0]->value,
                            'description' => strip_tags(trim($data->description[0]->value)), 
                            'type' => 'category', 
                            'url' => $categoryObj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $data->slug[0]->value),
                            'locale' => get_locale()
                        ];
        
                        if (isset($data->images) && is_array($data->images) && count($data->images) > 0) 
                            $propel['meta']['image'] = $data->images[0]->url;
                    }
    
                    break;
                case PageController::get_slug(PageType::PRODUCT_PAGE): 
                    if (!isset($query_vars['slug']) || empty($query_vars['slug'])) {
                        $propel['error_404'] = 'Product not found';
                    }
                    else {
                        $ref = 'Propeller\Custom\Includes\Controller\ProductController';

                        $productObj = class_exists($ref, true) 
                            ? new $ref() 
                            : new ProductController();

                        $data = $productObj->get_product($query_vars['slug'], null, [
                            'attribute' => '{isPublic: true}'
                        ]);

                        if (!is_object($data)) {
                            error_log(print_r($data, true));
                            $propel['error_404'] = 'Product not found';
                        }
                        else {
                            if ($data->class == 'product' && $data->status == 'N')
                                $propel['error_404'] = 'Product not found';
                            else {
                                $productObj->preserve_recently_viewed($data->id);

                                if ($data->class == 'cluster')
                                    SessionController::set(PROPELLER_VIEWING_CLUSTER, $data);
                        
                                $propel['url_slugs'] = $data->slugs;
                                
                                $propel['data'] = $data;
                                
                                $propel['title'] = $data->name[0]->value;
                                $propel['description'] = $data->description[0]->value;
            
                                $propel['breadcrumbs'] = $this->pack_breadcrumbs($data, PageController::get_slug(PageType::PRODUCT_PAGE));
                
                                $propel['meta'] = [
                                    'title' => $data->name[0]->value,
                                    'description' => strip_tags(trim($data->description[0]->value)), 
                                    'type' => 'product', 
                                    'url' => $productObj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $data->slug[0]->value),
                                    'locale' => get_locale()
                                ];
                
                                if (isset($data->mediaImages->items) && is_array($data->mediaImages->items) && count($data->mediaImages->items) > 0 && 
                                    isset($data->mediaImages->items->imageVariants) && is_array($data->mediaImages->items->imageVariants) && count($data->mediaImages->items->imageVariants) > 0) 
                                    $propel['meta']['image'] = $data->mediaImages->items[0]->imageVariants[0]->url;
                            }
                        }
                    }
                    
                    break;
                case PageController::get_slug(PageType::SEARCH_PAGE): 
                    $ref = 'Propeller\Custom\Includes\Controller\ProductController';

                    $productObj = class_exists($ref, true) 
                        ? new $ref() 
                        : new ProductController();
    
                    $applied_filters = $_REQUEST;
    
                    $filters_applied = $productObj->process_filters($applied_filters);
                    $qry_params = $productObj->build_search_arguments($filters_applied);
    
                    $term = isset($applied_filters['term']) ? $applied_filters['term'] : $query_vars['term'];
                    $term = urldecode($term);
    
                    if (!empty($term))
                        $qry_params['term'] = $term;
    
                    $data = $productObj->get_products($qry_params);

                    if (!is_object($data)) {
                        error_log(print_r($data, true));
                        $propel['error_404'] = 'Page not found';
                    }
                    else {
                        $propel['meta'] = [
                            'url' => $productObj->buildUrl(PageController::get_slug(PageType::SEARCH_PAGE), $term)
                        ];
                            
                        $propel['data'] = $data;
                        $propel['title'] = 'Search "' . $term . '"';
                        $propel['description'] = 'Search "' . $term . '"';
                    }
                    
                    break;
                case PageController::get_slug(PageType::BRAND_PAGE): 
                    $ref = 'Propeller\Custom\Includes\Controller\ProductController';

                    $productObj = class_exists($ref, true) 
                        ? new $ref() 
                        : new ProductController();
    
                    $applied_filters = $_REQUEST;
    
                    $filters_applied = $productObj->process_filters($applied_filters);
                    $qry_params = $productObj->build_search_arguments($filters_applied);
    
                    $term = isset($applied_filters['manufacturer']) ? $applied_filters['manufacturer'] : $query_vars['manufacturer'];
                    $term = urldecode($term);
    
                    if (!empty($term))
                        $qry_params['manufacturer'] = $term;
    
                    $data = $productObj->get_products($qry_params);
    
                    if (!is_object($data)) {
                        error_log(print_r($data, true));
                        $propel['error_404'] = 'Page not found';
                    }
                    else {
                        $propel['meta'] = [
                            'url' => $productObj->buildUrl(PageController::get_slug(PageType::BRAND_PAGE), $term)
                        ];
    
                        $propel['data'] = $data;
                        $propel['title'] = 'Brand "' . $term . '"';
                        $propel['description'] = 'Brand "' . $term . '"';
                    }
    
                    break;
                case PageController::get_slug(PageType::THANK_YOU_PAGE): 
                    if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
                        $propel['error_404'] = 'Page not found';
                    }
                    else if (!UserController::is_propeller_logged_in()) {
                        $propel['error_403'] = 'Access forbidden';
                    }
                    else {
                        $ref = 'Propeller\Custom\Includes\Controller\ShoppingCartController';

                        $cartController = class_exists($ref, true) 
                            ? new $ref() 
                            : new ShoppingCartController();
                        
                        $orderController = new OrderController();
                        $order = $orderController->get_order((int) $_GET['order_id']);

                        if (!is_object($order)) {
                            error_log(print_r($order, true));
                            $propel['error_404'] = 'Page not found';
                        }
                        else {
                            if (SessionController::get(PROPELLER_USER_DATA)->userId != $order->userId) {
                                wp_redirect(home_url('/403/'));
                                exit();
                            }
                            else {
                                if ($order->paymentData->status != PaymentStatuses::FAILED && 
                                    $order->paymentData->status != PaymentStatuses::CANCELLED && 
                                    $order->paymentData->status != PaymentStatuses::EXPIRED) {
                                        $cartController->clear_cart();
                                        $cartController->init_cart();

                                        $cartController->change_order_type(SessionController::get(PROPELLER_ORDER_TYPE));
                                        $cartController->set_user_default_cart_address();
                                }

                                $propel['order'] = $order;
                            }
                        }
                    }
                    
                    break;
                case PageController::get_slug(PageType::ORDER_DETAILS_PAGE): 
                    if (!isset($_REQUEST['order_id']) || empty($_REQUEST['order_id']) || !is_numeric($_REQUEST['order_id'])) {
                        $propel['error_404'] = 'Page not found';
                    }
                    else {
                        if (!UserController::is_propeller_logged_in()) {
                            wp_redirect(home_url('/403/'));
                            die;
                        }
                        else {
                            $ref = 'Propeller\Custom\Includes\Controller\OrderController';

                            $orderController = class_exists($ref, true) 
                                ? new $ref() 
                                : new OrderController();
                    
                            $data = $orderController->get_order((int) $_REQUEST['order_id']);
                    
                            if (!is_object($data)) {
                                @error_log(print_r($data), true);
                                $propel['error_404'] = 'Page not found';
                            }
                            else {
                                if (SessionController::get(PROPELLER_USER_DATA)->userId != $data->userId)
                                    $propel['error_404'] = 'Page not found';
                                else 
                                    $propel['order'] = $data;
                            }
                        }
                    }

                    break;
                default: 
                    break;
            }
        }
    }

    private function pack_breadcrumbs($data, $page_slug) {
        $obj = new BaseController();

        $bcrumbs = [];

        $index = 0;
        foreach ($data->categoryPath as $path) {
            if ($index > 0) {
                $bcrumbs[] = [
                    $obj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $path->slug[0]->value),
                    $path->name[0]->value
                ];
            }

            $index++;
        }

        $bcrumbs[] = [
            $obj->buildUrl($page_slug, $data->slug[0]->value),
            $data->name[0]->value            
        ];
    
        return $bcrumbs;
    }
}