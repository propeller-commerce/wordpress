<?php 

/**
 * there are three levels in the menu structure in tepper efka. 
 * define all three levels classess for the menu (0) -> submenu (1) -> subsubmenu (2)
 * in the array $classes bellow. 
 * 
 * the 0 as the third argument in the menu method is the index from which the menu classes
 * should be fetched. It always starts with 0 for drawing the initial menu structure first. 
 * the index is automatically incremented in the method itself as it cycles through
 * the category structure.
 * 
 */
    $classes = [
        0 => [  // first level UL classes
            'ul_classes' => 'propeller-main-navigation', 
            'li_classes' => 'main-item'
        ], 
        1 => [ // second level UL classes
            'ul_classes' => 'main-propeller-submenu', 
            'li_classes' => 'main-subitem'
        ], 
        2 => [ // third level UL classes
            'ul_classes' => 'main-propeller-subsubmenu', 
            'li_classes' => 'main-subsubitem'
        ], 
    ];
    
?>

<div class="propeller-menu <?php echo apply_filters('propel_menu_classes', ''); ?>">
    <a href="#" class="dropdown propeller-toggle-menu">
        <span><?php echo __('Categories', 'propeller-ecommerce'); ?></span>
    </a>
   
    <?php echo (string) $this->build_menu($this->getMenu()->categories, $classes, 0); ?>
          
    
</div>