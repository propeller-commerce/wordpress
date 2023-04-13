<div class="col d-flex justify-content-end">
    <?php if (!empty($product->inventory) && $product->inventory->totalQuantity > 0) { ?>
        <div class="product-stock"><?php echo __('Available', 'propeller-ecommerce'); ?><span class="quantity-stock">: <?php echo esc_html($product->inventory->totalQuantity); ?></span></div>
    <?php }  else { ?>
        <div class="product-stock out-of-stock"><?php echo __('Available as backorder', 'propeller-ecommerce'); ?></div>
    <?php } ?>
</div>