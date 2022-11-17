<?php if (!empty($product->crossupsells)) { ?>  
    <div class="row no-gutters propeller-crossup <?= apply_filters('propel_crossupsell_classes', ''); ?>">
        <div class="col-12">
            <h2 class="product-info-title mt-5 mb-4"><?php echo __('"Gerelateerde" producten?', 'propeller-ecommerce'); ?></h2>
            <div class="row propeller-slider-wrapper">
                <div class="col-12 propeller-slider slick-crossup" id="product-related-slider">
                    <?php foreach($product->crossupsells as $crossupsell) { ?>
                        <div>
                            <?= apply_filters('propel_product_crossupsell_card', $crossupsell, $obj); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>