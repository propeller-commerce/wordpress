<?php

namespace Propeller\Includes\Controller;

class HomepageController extends BaseController {
    
    public function __construct() {
        parent::__construct();   
    }

    public function home_page(){
        ob_start();
        
        require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-home-page.php');
        
        return ob_get_clean();
    }

    public function breadcrumbs($paths) {
        global $propel;

        if (isset($propel['breadcrumbs']))
            $paths = $propel['breadcrumbs'];
            
        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-breadcrumbs.php');
    }
}