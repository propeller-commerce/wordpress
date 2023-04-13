<?php foreach ($products as $product) { 
    if (!count($product->slug)) // skip products without slug, probably not translated
        continue;
?>     

    <div class="propeller-list-item col-12 col-sm-6 col-xl-4">
        <?php echo apply_filters('propel_' . $product->class . '_card', $product, $this); ?>
    </div>
<?php } ?>