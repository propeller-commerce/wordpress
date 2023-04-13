<div class="row propeller-slider-wrapper">
    <?php if ($no_results) { ?>
        <h3><?php echo __('No results', 'propeller-ecommerce'); ?></h3>
    <?php } else { ?>
        <div class="col-12 propeller-slider slick-crossup">
            <?php 
                foreach($products as $product) {
                    if (!count($product->slug)) // skip products without slug, probably not translated
                    continue;
                    apply_filters('propel_' . $product->class . '_card', $product, $this);
                } 
            ?>
        </div>
    <?php } ?>
</div>