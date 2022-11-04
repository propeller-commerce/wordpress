<?php 
    foreach($products as $product) {
        include $this->partials_dir . '/product/propeller-' . $product->class . '-card-simple.php';
    } 
?>
