<?php 

namespace Propeller\Includes\Controller;

use Propeller\Includes\Enum\PageType;
use Propeller\Propeller;
use stdClass;

class ProductAjaxController {
    protected $product;

    public function __construct() {
        $this->product = new ProductController();
    }

    public function search() {
        unset($_POST['action']); // remove action from the params

        $response = $this->product->get_products($_REQUEST, true);
        $response->status = true;
        $response->error = null;
        
        die(json_encode($response));
    }    

    public function do_search() {
        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = $this->product->search($_POST, true);

        die(json_encode($response));
    }

    public function do_brand() {
        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = $this->product->brand($_POST, true);

        die(json_encode($response));
    }

    public function global_search() {        
        unset($_POST['action']); // remove action from the params

        $response = $this->product->global_product_search($_POST, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++)
            $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);
        
        die(json_encode($response));
    }   

    public function quick_product_search() {
        unset($_POST['action']); // remove action from the params

        $response = $this->product->get_products($_POST, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++)
            $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value);
        
        die(json_encode($response));
    }

    public function get_product() {
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
        $response = new stdClass();
        $response->content = $this->product->get_recently_viewed_products(['id' => implode(',', $_POST['ids'])]);
        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_crossupsells() {
        $prop = new Propeller();
        $prop->reinit_filters();
        
        $response = new stdClass();
        $response->content = $this->product->load_crossupsells($_POST['slug'], $_POST['class']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }
}