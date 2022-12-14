<?php
    // var_dump($this->product->get_options());
?>  
<div class="row no-gutters align-items-end product-price-details">
    <?php foreach ($this->product->get_options() as $attrName => $option) { 
        if (!$option->label || !count($option->values)) continue;   
    ?>
        <h1><?= $option->label ?></h1>

        <?php if ( $option->display === 'radio') { ?>
        <table>
            <tr>
            <?php foreach ($option->values as $val) { ?>
                <td>
                    <label for="option_<?= $attrName; ?>_<?= $val->product->productId; ?>"><?= $val->value; ?>: </label>
                    <input type="radio" 
                            class="cluster-radio" 
                            id="option_<?= $attrName; ?>_<?= $val->product->productId; ?>" 
                            name="<?= $val->id; ?>" 
                            value="<?= $val->value; ?>" 
                            data-product_id="<?= $val->product->productId; ?>" 
                            data-price="<?= $val->product->price->net; ?>" 
                            data-attr_id="<?= $val->id; ?>"
                            data-min_quantity="<?= $val->product->minimumQuantity; ?>"
                            data-unit="<?= $val->product->unit; ?>">
                </td>
            <?php } ?>
            </tr>
        </table>
        <?php } else if ( $option->display === 'dropdown') { ?>
            <select class="cluster-dropdown" name="<?= $val->id; ?>">
                <?php foreach ($option->values as $val) { ?>
                    <option value="<?= $val->value; ?>" 
                            data-product_id="<?= $val->product->productId; ?>" 
                            data-price="<?= $val->product->price; ?>" 
                            data-attr_id="<?= $val->id; ?>"
                            data-min_quantity="<?= $val->product->minimumQuantity; ?>"
                            data-unit="<?= $val->product->unit; ?>">
                        <?= $val->value; ?>
                    </option>
                    </td>
                <?php } ?>
            </select>
        <?php } else if ( $option->display === 'image') { ?>
            <!-- add image options probably like radios -->
        <?php } else if ( $option->display === 'color') { ?>
            <!-- add color options probably like radios -->
        <?php } ?>
    <?php } ?>
</div>

<div class="row no-gutters align-items-end product-price-details">
    <div class="col pr-0 add-to-basket">                       
        <form class="add-to-basket-form d-flex" name="add-product" method="post">
            <input type="hidden" name="product_id" value="">
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
                    min=""
                    value=""
                    data-min=""
                    data-unit="">
                <span class="input-group-append incr-decr">
                    <button type="button" class="btn-quantity" data-type="plus">+</button>
                </span>
            </div>                       
            <button class="btn-addtobasket d-flex justify-content-center align-items-center" type="submit">
                <?php echo __('In cart', 'propeller-ecommerce'); ?>
            </button>
        </form>
        
    </div>

    <div class="col-auto">
        <div class="favorites">
            <div class="favorite-add-form">
                <form name="add_favorite" class="validate form-handler favorite" method="post" novalidate="novalidate">
                    <input type="hidden" name="action" value="favorites_add_item">
                    <input type="hidden" name="product_id" value="<?= $this->product->clusterId; ?>">
                
                    <button type="submit" class="btn-favorite" rel="nofollow">
                        <svg class="icon icon-product-favorite icon-heart">
                            <use class="header-shape-heart" xlink:href="#shape-favorites"></use>
                        </svg>
                    </button>
                </form>				
            </div>
        </div>
    </div>
</div>