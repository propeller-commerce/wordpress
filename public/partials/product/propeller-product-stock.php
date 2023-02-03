<div class="col d-flex justify-content-end">
    <?php if (!empty($product->inventory)) { ?>
        <div class="product-stock"><?php echo __('Available', 'propeller-ecommerce'); ?>: <?php echo esc_html($product->inventory->totalQuantity); ?></div>
    <?php }  else { ?>
        <div class="product-stock out-of-stock"><?php echo __('Available as backorder', 'propeller-ecommerce'); ?></div>
    <?php } ?>
</div>