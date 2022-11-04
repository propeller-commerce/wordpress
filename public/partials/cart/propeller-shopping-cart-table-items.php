<?php 
    foreach ($obj->get_items() as $item) {
        if(empty($item->bundle))
            apply_filters('propel_shopping_cart_table_product_item', $item, $this->cart, $this);
            // require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-product-item.php');
        else 
            apply_filters('propel_shopping_cart_table_bundle_item', $item, $this->cart, $this);
            // require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR . 'propeller-shopping-cart-bundle-item.php');
    }
?>