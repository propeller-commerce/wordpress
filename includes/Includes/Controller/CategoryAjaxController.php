<?php 
namespace Propeller\Includes\Controller;

use Propeller\Includes\Controller\CategoryController;
use Propeller\Propeller;

class CategoryAjaxController extends BaseAjaxController {
    protected $category;

    public function __construct() { 
        $this->category = new CategoryController();
    }

    public function do_filter() {
        $_POST = $this->sanitize($_POST);

        unset($_POST['action']);

        $prop = new Propeller();
        $prop->reinit_filters();

        $response = $this->category->product_listing($_POST, true);

        die(json_encode($response));
    }
}