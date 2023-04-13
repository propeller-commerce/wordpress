<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

    if (!function_exists('buildCatalogListingMenu')) {
        function buildCatalogListingMenu($categories, $classes = [], $index = 0, $ul_aria_id = '', $search_categories = []) {
            $menuObj = new Propeller\Includes\Controller\MenuController();
            $slug = get_query_var('slug');
            
            $ul_class = isset($classes[$index]) && isset($classes[$index]['ul_classes']) ? $classes[$index]['ul_classes'] : '';
            $li_class = isset($classes[$index]) && isset($classes[$index]['li_classes']) ? $classes[$index]['li_classes'] : '';
            $ul_id = 'submenu_' . $ul_aria_id . '';
            $str = '<ul class="' . $ul_class . '" id="' . $ul_id . '">';
            
            foreach ($categories as $cat) {
                $subMenuIndex = $cat->id;
               
                $str .= '<li class="' . $li_class . '">';
                $aClass = (isset($cat->categories) && sizeof($cat->categories)) ? 'has-submenu dropdown-toggle' : '';
                $aLabel = (isset($cat->categories) && sizeof($cat->categories)) ? 'data-toggle="collapse" href="#submenu_' . $subMenuIndex . '" aria-expanded="false" data-target="#submenu_' . $subMenuIndex . '"' : '';
                
                $menu_url = $menuObj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $cat->slug[0]->value, $cat->urlId);
        
                if (sizeof($search_categories) && isset($search_categories[$cat->categoryId])) {
                    if (!empty(get_query_var('term')))
                        $menu_url .= '?term=' . get_query_var('term');
                    
                    // if (!empty(get_query_var('plate')))
                    //     $menu_url .= '?plate=' . get_query_var('plate');
                }
                //var_dump(!empty($slug) && $slug == $cat->slug[0]->value);
                if (!empty($slug) && $slug == $cat->slug[0]->value)
                    $aClass .= ' active';                
        
                $str .= '<a href="' . $menu_url . '" class=" w-100 ' . $aClass . '" ' . $aLabel . '>';
                $str .= $cat->name[0]->value;
        
                if (sizeof($search_categories)) {
                    if (isset($search_categories[$cat->categoryId]))
                        $str .= " (" . $search_categories[$cat->categoryId]->items . ")";
                }
        
                $str .= (isset($cat->categories) && sizeof($cat->categories)) ? '</a>' : '</a>';
        
                if (isset($cat->categories) && sizeof($cat->categories)) {
                    $subindex = $index + 1;
                    $str .= buildCatalogListingMenu($cat->categories, $classes, $subindex, $subMenuIndex, $search_categories);
                }
        
                $str .= '</li>';
            }
        
            $str .= '</ul>';
        
            return $str;
        }
    }

    $menucontroller = new Propeller\Includes\Controller\MenuController();
    $menuItems = $menucontroller->getMenu();
?>

<nav class="catalog-menu d-none d-md-block">
    <div class="filter categories-nav">
        <button class="btn-filter" type="button" href="#filterForm_catalog_menu" data-toggle="collapse" aria-expanded="true" aria-controls="filterForm_catalog_menu">
            <?php echo __('Categories', 'propeller-ecommerce'); ?>
        </button>   
        <div class="catalog-filter-content collapse show" id="filterForm_catalog_menu">
            <ul>
                <?php 
                    
                    $classes = [
                        0 => [  // first level UL classes
                            'ul_classes' => 'main-propeller-category', 
                            'li_classes' => 'main-item'
                        ], 
                        1 => [ // second level UL classes
                            'ul_classes' => 'main-propeller-category-submenu collapse', 
                            'li_classes' => 'main-subitem'
                        ], 
                        2 => [ // third level UL classes
                            'ul_classes' => 'main-propeller-category-subsubmenu collapse', 
                            'li_classes' => 'main-subsubitem'
                        ], 
                    ];
                    //var_dump($menucontroller);
                    echo buildCatalogListingMenu($menuItems->categories, $classes, 0); 
                    
                ?>
            </ul>                       
        </div>
    </div>
</nav>