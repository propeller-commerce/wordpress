<?php
namespace Propeller\Includes\Controller;

use GraphQL\Query;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Model\MenuModel;
use Propeller\Includes\Query\Category as CategoryQuery;

class MenuController extends BaseController {
    protected $type = 'category';
    protected $category_slug;
    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = new MenuModel();

        $this->category_slug = PageController::get_slug(PageType::CATEGORY_PAGE);
    }

    public function draw_menu() {
        ob_start();
        
        require $this->load_template('templates', DIRECTORY_SEPARATOR . 'propeller-menu.php');

        return ob_get_clean();
    }

    public function build_menu_raw($categories) {
        $cats = [];

        foreach ($categories as $cat) {
            if (sizeof($cat->name)) {
                $cat->url = $this->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $cat->slug[0]->value);
            
                array_push($cats, $cat);
            }
            
            if (isset($cat->categories) && sizeof($cat->categories))
                $cats = array_merge($cats, $this->build_menu_raw($cat->categories));
        }

        return $cats;
    }

    function build_menu($categories, $classes = [], $index = 0) {
        $slug = isset($_REQUEST['slug']) ? $_REQUEST['slug'] : "";

        $ul_class = isset($classes[$index]) && isset($classes[$index]['ul_classes']) ? $classes[$index]['ul_classes'] : '';
        $li_class = isset($classes[$index]) && isset($classes[$index]['li_classes']) ? $classes[$index]['li_classes'] : '';
        
        $str = '<ul class="menu-list ' . $ul_class . '">';

        foreach ($categories as $cat) {
            
            // skip empty categories, probably not translated
            if (!isset($cat->slug) || !count($cat->slug))
                continue;
          
            $li_childClass = (isset($cat->categories) && sizeof($cat->categories)) ? 'menu-item-has-children' : '';
            $str .= '<li class="' . $li_class .' '. $li_childClass . '">';
            $aClass = (isset($cat->categories) && sizeof($cat->categories)) ? 'has-submenu' : '';

            if (!empty($slug) && $slug == $cat->slug[0]->value)
                $aClass .= ' active';
            
            $str .= '<a href="' . $this->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $cat->slug[0]->value) . '" class="' . $aClass . '">';
            $str .= $cat->name[0]->value;
            $str .= '</a>';

            if (isset($cat->categories) && sizeof($cat->categories)) {
                $subindex = $index + 1;
                $str .= $this->build_menu($cat->categories, $classes, $subindex);
            }

            $str .= '</li>';
        }

        $str .= '</ul>';

        return $str;
    }

    public function getMenu($elementor_call = false) {
        $gql = $this->model->get_menu(PROPELLER_BASE_CATALOG, PROPELLER_LANG);
        
        if ($elementor_call) {
            $menu_structure = $this->query($gql, $this->type);    
        }
        else {
            $menu_transient = CacheController::PROPELLER_MENU_TRANSIENT;

            if (defined('PROPELLER_LANG'))
                $menu_transient .=  '_' . PROPELLER_LANG;
                
            if (false === ($menu_structure = CacheController::get($menu_transient))) {
                $menu_structure = $this->query($gql, $this->type);
                CacheController::set($menu_transient, $menu_structure);
            }
        }

        // var_dump($menu_structure);

        return $menu_structure;
    }
}