<div class="col d-flex justify-content-end">
    <?php if (!empty($cluster_product->inventory)) { ?>
        <div class="product-stock"><span class="product-stock-val"><?php echo __('Available', 'propeller-ecommerce'); ?></span>: <?php echo $cluster_product->inventory->totalQuantity; ?></div>
    <?php }  else { ?>
        <div class="product-stock out-of-stock"><span class="product-stock-val"><?php echo __('Available as backorder', 'propeller-ecommerce'); ?></span></div>
    <?php } ?>
</div>