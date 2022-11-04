<?php foreach ($products as $product) { 
    if (!count($product->slug)) // skip products without slug, probably not translated
        continue;
?>     

    <div class="propeller-list-item col-12 col-sm-6 col-xl-4">
        <?php require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-' . $product->class . '-card.php'); ?>
    </div>
<?php } ?>