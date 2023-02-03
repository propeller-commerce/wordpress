<?php 

namespace Propeller\Includes\Controller;

use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Cluster;
use Propeller\Includes\Object\Product;
use Propeller\Propeller;
use stdClass;

class ProductAjaxController extends BaseAjaxController {
    protected $product;

    public function __construct() {
        $this->product = new ProductController();
    }

    public function search() {
        $_REQUEST = $this->sanitize($_REQUEST);

        unset($_REQUEST['action']); // remove action from the params

        $response = $this->product->get_products($_REQUEST, true);
        $response->status = true;
        $response->error = null;
        
        die(json_encode($response));
    }    

    public function do_search() {
        $_POST = $this->sanitize($_POST);

        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = $this->product->search($_POST, true);

        die(json_encode($response));
    }

    public function do_brand() {
        $_POST = $this->sanitize($_POST);

        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = $this->product->brand($_POST, true);

        die(json_encode($response));
    }

    public function global_search() {
        $_POST = $this->sanitize($_POST);

        unset($_POST['action']); // remove action from the params
        
        $response = $this->product->global_product_search($_POST, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++) {
            if ($response->items[$i]->class == 'product') {
                $response->items[$i] = new Product($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);

                $response->items[$i]->image = $response->items[$i]->has_images() 
                    ? $response->items[$i]->images[0]->images[0]->url 
                    : $this->product->assets_url . '/img/no-image-card.webp';
            }
            else if ($response->items[$i]->class == 'cluster') {
                $response->items[$i] = new Cluster($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);

                $response->items[$i]->defaultProduct = !$response->items[$i]->defaultProduct 
                    ? new Product($response->items[$i]->products[0])
                    : new Product($response->items[$i]->defaultProduct);

                $response->items[$i]->image = $response->items[$i]->defaultProduct->has_images() 
                    ? $response->items[$i]->defaultProduct->images[0]->images[0]->url 
                    : $this->product->assets_url . '/img/no-image-card.webp';
            }
        }
        
        die(json_encode($response));
    }   

    public function quick_product_search() {
        $_POST = $this->sanitize($_POST);

        unset($_POST['action']); // remove action from the params

        $response = $this->product->get_products($_POST, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++) {
            if ($response->items[$i]->class == 'product') {
                $response->items[$i] = new Product($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);

                $response->items[$i]->image = $response->items[$i]->has_images() 
                    ? $response->items[$i]->images[0]->images[0]->url 
                    : $this->product->assets_url . '/img/no-image-card.webp';
            }
            else if ($response->items[$i]->class == 'cluster') {
                $response->items[$i] = new Cluster($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);

                $response->items[$i]->defaultProduct = !$response->items[$i]->defaultProduct 
                    ? new Product($response->items[$i]->products[0])
                    : new Product($response->items[$i]->defaultProduct);

                $response->items[$i]->image = $response->items[$i]->defaultProduct->has_images() 
                    ? $response->items[$i]->defaultProduct->images[0]->images[0]->url 
                    : $this->product->assets_url . '/img/no-image-card.webp';
            }
        }

        die(json_encode($response));
    }

    public function get_product() {
        $_POST = $this->sanitize($_POST);

        if (!isset($_POST['id']))
            die;

        unset($_POST['action']); // remove action from the params

        $response = $this->product->get_product(null, $_POST['id'], true);
        $response->status = true;
        $response->error = null;

        $response->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->slug[0]->value);
        
        die(json_encode($response));
    }

    public function update_cluster_content() {
        $_POST = $this->sanitize($_POST);

        if (!isset($_POST['slug']))
            die;

        $prop = new Propeller();
        $prop->reinit_filters();

        $cluster_id = isset($_POST['cluster_id']) && !empty($_POST['cluster_id']) && is_numeric($_POST['cluster_id']) ? $_POST['cluster_id'] : 0;

        $response = new stdClass();
        $response->content = $this->product->cluster_details($_POST['slug'], $cluster_id);
        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function get_recently_viewed_products() {
        $_POST = $this->sanitize($_POST);

        $response = new stdClass();
        $response->content = $this->product->get_recently_viewed_products(['id' => implode(',', $_POST['ids'])]);
        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_crossupsells() {
        $_POST = $this->sanitize($_POST);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = new stdClass();
        $response->content = $this->product->load_crossupsells($_POST['slug'], $_POST['class'], $_POST['crossupsell_type']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_specifications() {
        $_POST = $this->sanitize($_POST);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = new stdClass();
        $response->content = $this->product->load_specifications($_POST['id'], isset($_POST['page']) ? $_POST['page'] : 1, isset($_POST['offset']) ? $_POST['offset'] : 12);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_downloads() {
        $_POST = $this->sanitize($_POST);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = new stdClass();
        $response->content = $this->product->load_downloads($_POST['id']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_videos() {
        $_POST = $this->sanitize($_POST);
        
        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = new stdClass();
        $response->content = $this->product->load_videos($_POST['id']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }
}