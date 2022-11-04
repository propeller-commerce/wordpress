<div class="row no-gutters align-items-end product-price-details">
    <?php foreach ($cluster->formatted_options as $attr_name => $option) { 
        if (!$option->label || !count($option->values)) continue;   
    ?>
        <h5><?= $option->label; ?></h5>

        <?php if ( $option->display === 'dropdown') { ?>
            <select class="cluster-dropdown" name="<?= $attr_name; ?>">
            <?php foreach ($option->values as $val) { ?>
                <?php 
                    $selected = '';
                    
                    foreach ($cluster->selected_options as $sel_option) {
                        if ($sel_option->name == $attr_name && $sel_option->value == $val) {
                            $selected = 'selected="selected"';
                            break;
                        }
                    }
                ?>
                <option value="<?= $val; ?>" <?= $selected; ?>>
                    <?= $val; ?>
                </option>
            <?php } ?>
            </select>
        <?php } else if ( $cluster_option->display === 'radio') { ?>
            <!-- add radio options -->
        <?php } else if ( $option->display === 'image') { ?>
            <!-- add image options probably like radios -->
        <?php } else if ( $option->display === 'color') { ?>
            <!-- add color options probably like radios -->
        <?php } ?>
    <?php } ?>
</div>

<div class="row no-gutters align-items-end product-price-details">
    <?= apply_filters('propel_product_add_to_basket', $cluster_product); ?>
    <?php /*
    <div class="col pr-0 add-to-basket">                       
        <form class="add-to-basket-form d-flex" name="add-product" method="post">
            <input type="hidden" name="product_id" value="<?= $cluster_product->productId; ?>">
            <input type="hidden" name="action" value="cart_add_item">
            <div class="input-group product-quantity align-items-center">
                <label class="sr-only" for="quantity-item"><?php echo __("Quantity", 'propeller-ecommerce'); ?></label> 
                <span class="input-group-prepend incr-decr">
                    <button type="button" class="btn-quantity" 
                    data-type="minus">-</button>
                </span>
                <input
                    type="number"
                    ondrop="return false;" 
                    onpaste="return false;"
                    onkeypress="return event.charCode>=48 && event.charCode<=57"
                    id="quantity-item"
                    class="quantity large form-control input-number product-quantity-input"
                    name="quantity"
                    autocomplete="off"
                    min="<?= $cluster_product->minimumQuantity; ?>"
                    value="<?= $cluster_product->minimumQuantity; ?>"
                    data-min="<?= $cluster_product->minimumQuantity; ?>"
                    data-unit="<?= $cluster_product->unit; ?>">

                <span class="input-group-append incr-decr">
                    <button type="button" class="btn-quantity" data-type="plus">+</button>
                </span>
            </div>                       
            <button class="btn-addtobasket d-flex justify-content-center align-items-center" type="submit">
                <?php echo __('In cart', 'propeller-ecommerce'); ?>
            </button>
        </form>
        
    </div>
    */ ?>

    <div class="col-auto">
        <?= apply_filters('propel_cluster_add_favorite', $cluster); ?>
    </div>
</div>