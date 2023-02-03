<?php 
    $stock_show = false;
    if(!empty($product->inventory) AND $product->inventory->totalQuantity > 0) 
        $stock_show = true;
    
?>
<div class="col pr-0 add-to-basket pl-30">
    <form class="add-to-basket-form d-flex" name="add-product" action="#" method="post">
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product->productId); ?>">
        <input type="hidden" name="action" value="cart_add_item">
        <div class="input-group product-quantity align-items-center">
            <label class="sr-only" for="quantity-item-<?php echo esc_html($product->productId); ?>"> <?php echo __('Quantity', 'propeller-ecommerce'); ?></label>
            <span class="input-group-prepend incr-decr">
                <button type="button" class="btn-quantity" 
                data-type="minus">-</button>
            </span>
            <input
                type="number"
                ondrop="return false;" 
                onpaste="return false;"
                onkeypress="return event.charCode>=48 && event.charCode<=57"
                id="quantity-item-<?php echo esc_html($product->productId); ?>"
                class="quantity large form-control input-number product-quantity-input"
                name="quantity"
                autocomplete="off"
                min="<?php echo esc_attr($product->minimumQuantity); ?>"
                value="<?php echo esc_attr($product->minimumQuantity); ?>"
                data-min="<?php echo esc_attr($product->minimumQuantity); ?>"
                data-unit="<?php echo esc_attr($product->unit); ?>"
                <?php if($stock_show) { ?>
                    data-stock="<?php echo esc_attr($product->inventory->totalQuantity); ?>"   
                <?php } ?>
                >
            <span class="input-group-append incr-decr">
                <button type="button" class="btn-quantity" data-type="plus">+</button>
            </span>
        </div>                       
        <button class="btn-addtobasket d-flex justify-content-center align-items-center" type="submit">
            <?php echo __('In cart', 'propeller-ecommerce'); ?>
        </button>
    </form>
</div>