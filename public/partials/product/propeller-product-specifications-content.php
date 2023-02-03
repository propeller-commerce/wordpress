<div class="row">
    <div class="col-12">
        <h3><?php echo __('Specifications', 'propeller-ecommerce'); ?></h3>
    </div>
    <div class="col-12 product-specs-rows">
        <?php if(!empty($product->eanCode)) { ?> 
            <div class="row no-gutters product-specs">
                <div class="col col-sm-6">
                <?php echo __('EAN code', 'propeller-ecommerce'); ?>
                </div>
                <div class="col-auto">
                    <?php echo esc_html__($product->eanCode); ?>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($product->manufacturer)) { ?> 
            <div class="row no-gutters product-specs">
                <div class="col col-sm-6">
                <?php echo __('Brand', 'propeller-ecommerce'); ?>
                </div>
                <div class="col-auto">
                    <?php echo esc_html__($product->manufacturer); ?>
                </div>
            </div>
        <?php } ?>
        <?php apply_filters('propel_product_specifications_rows', $product); ?>
    </div>
</div>