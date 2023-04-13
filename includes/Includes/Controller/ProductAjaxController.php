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
        parent::__construct();
        
        $this->product = new ProductController();
    }

    public function search() {
        $this->init_ajax();

        $_REQUEST = $this->sanitize($_REQUEST);

		if(isset($_REQUEST['action'])) {
			unset( $_REQUEST['action'] ); // remove action from the params
		}

        $response = $this->product->get_products($_REQUEST, true);
        $response->status = true;
        $response->error = null;
        
        die(json_encode($response));
    }    

    public function do_search() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        unset($data['action']);
        
        $response = $this->product->search($data, true);

        die(json_encode($response));
    }

    public function do_brand() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        unset($data['action']);
        
        $response = $this->product->brand($data, true);

        die(json_encode($response));
    }

    public function global_search() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        unset($data['action']); // remove action from the params
        
        $response = $this->product->global_product_search($data, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++) {
            if ($response->items[$i]->class == 'product') {
                $response->items[$i] = new Product($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value, $response->items[$i]->urlId);

                $response->items[$i]->image = $response->items[$i]->has_images() 
                    ? $response->items[$i]->images[0]->images[0]->url 
                    : $this->product->assets_url . '/img/no-image-card.webp';
            }
            else if ($response->items[$i]->class == 'cluster') {
                $response->items[$i] = new Cluster($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value, $response->items[$i]->urlId);

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
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        unset($data['action']); // remove action from the params

        if (isset($data['sku'])) {
            $data['term'] = $data['sku'];
            unset($data['sku']);
        }

        $response = $this->product->global_product_search($data, true);
        $response->status = true;
        $response->error = null;

        for ($i = 0; $i < count($response->items); $i++) {
            if ($response->items[$i]->class == 'product') {
                $response->items[$i] = new Product($response->items[$i]);

                $response->items[$i]->url = esc_url($this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value, $response->items[$i]->urlId));

                $response->items[$i]->image = esc_url($response->items[$i]->has_images() ? $response->items[$i]->images[0]->images[0]->url : $this->product->assets_url . '/img/no-image-card.webp');
            }
            else if ($response->items[$i]->class == 'cluster') {
                $response->items[$i] = new Cluster($response->items[$i]);

                $response->items[$i]->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->items[$i]->slug[0]->value, $response->items[$i]->urlId);

                $response->items[$i]->defaultProduct = !$response->items[$i]->defaultProduct 
                    ? new Product($response->items[$i]->products[0])
                    : new Product($response->items[$i]->defaultProduct);

                $response->items[$i]->image = esc_url($response->items[$i]->defaultProduct->has_images() ? $response->items[$i]->defaultProduct->images[0]->images[0]->url : $this->product->assets_url . '/img/no-image-card.webp');
            }
        }

        die(json_encode($response));
    }

    public function get_product() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        if (!isset($data['id']))
            die;

        unset($data['action']); // remove action from the params

        $response = $this->product->get_product(null, $data['id'], true);
        $response->status = true;
        $response->error = null;

        $response->url = $this->product->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $response->slug[0]->value, $response->urlId);
        
        die(json_encode($response));
    }

    public function update_cluster_content() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        if (!isset($data['slug']))
            die;

        $cluster_id = isset($data['cluster_id']) && !empty($data['cluster_id']) && is_numeric($data['cluster_id']) ? sanitize_text_field($data['cluster_id']) : 0;

        $response = new stdClass();
        $response->content = $this->product->cluster_details($data['slug'], $cluster_id);
        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function get_recently_viewed_products() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        $response = new stdClass();
        $response->content = $this->product->get_recently_viewed_products(['id' => implode(',', $data['ids'])]);
        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_crossupsells() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        $response = new stdClass();
        $response->content = $this->product->load_crossupsells($data['slug'], $data['class'], $data['crossupsell_type']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_specifications() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        $response = new stdClass();
        $response->content = $this->product->load_specifications($data['id'], isset($data['page']) ? $data['page'] : 1, isset($data['offset']) ? (int) $data['offset'] : 12);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_downloads() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);

        $response = new stdClass();
        $response->content = $this->product->load_downloads($data['id']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }

    public function load_product_videos() {
        $this->init_ajax();

	    $data = $this->sanitize($_POST);
        
        $response = new stdClass();
        $response->content = $this->product->load_videos($data['id']);

        $response->status = true;
        $response->error = null;

        die(json_encode($response));
    }
}