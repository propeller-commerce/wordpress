<?php foreach($product->$type as $crossupsell) { ?>
    <div>
        <?php echo apply_filters('propel_product_crossupsell_card', $crossupsell, $obj); ?>
    </div>
<?php } ?>