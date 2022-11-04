<?php 
namespace Propeller\Includes\Controller;

use Propeller\Includes\Controller\CategoryController;
use Propeller\Propeller;

class CategoryAjaxController {
    protected $category;

    public function __construct() { 
        $this->category = new CategoryController();
    }

    public function do_filter() {
        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();

        $response = $this->category->product_listing($_POST, true);

        die(json_encode($response));
    }
}