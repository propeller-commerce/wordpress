<div class="row no-gutters align-items-end product-price-details">
    <?php

use Propeller\Includes\Controller\UserController;

 foreach ($this->product->get_options() as $attrName => $option) { 
        if (!$option->label || !count($option->values)) continue;   
    ?>
        <h1><?php echo esc_html($option->label); ?></h1>

        <?php if ( $option->display === 'radio') { ?>
        <table>
            <tr>
            <?php foreach ($option->values as $val) { ?>
                <td>
                    <label for="option_<?php echo esc_attr($attrName); ?>_<?php echo esc_attr( $val->product->productId); ?>"><?php echo esc_html($val->value); ?>: </label>
                    <input type="radio" 
                            class="cluster-radio" 
                            id="option_<?php echo esc_attr($attrName); ?>_<?php echo esc_attr($val->product->productId); ?>"
                            name="<?php echo esc_attr($val->id); ?>"
                            value="<?php echo esc_attr($val->value); ?>"
                            data-product_id="<?php echo esc_attr($val->product->productId); ?>"
                            data-price="<?php echo esc_attr($val->product->price->net); ?>"
                            data-attr_id="<?php echo esc_attr($val->id); ?>"
                            data-min_quantity="<?php echo esc_attr($val->product->minimumQuantity); ?>"
                            data-unit="<?php echo esc_attr($val->product->unit); ?>">
                </td>
            <?php } ?>
            </tr>
        </table>
        <?php } else if ( $option->display === 'dropdown') { ?>
            <select class="cluster-dropdown" name="<?php echo esc_attr($val->id); ?>">
                <?php foreach ($option->values as $val) { ?>
                    <option value="<?php echo esc_attr($val->value); ?>"
                            data-product_id="<?php echo esc_attr($val->product->productId); ?>"
                            data-price="<?php echo esc_attr($val->product->price); ?>"
                            data-attr_id="<?php echo esc_attr($val->id); ?>"
                            data-min_quantity="<?php echo esc_attr($val->product->minimumQuantity); ?>"
                            data-unit="<?php echo esc_attr($val->product->unit); ?>">
                        <?php echo esc_html($val->value); ?>
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
<?php if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL)) { ?> 
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
                        <input type="hidden" name="product_id" value="<?php echo esc_attr($this->product->clusterId); ?>">
                    
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
<?php } ?>