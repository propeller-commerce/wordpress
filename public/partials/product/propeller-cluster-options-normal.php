<?php

use Propeller\Includes\Controller\UserController;
use Propeller\PropellerHelper;
?>

<div class="row no-gutters align-items-end product-price-details">
    <?php if (count($this->product->products)) { ?>
        <?php foreach ($this->product->products as $product_option) { ?>
            <div class="row no-gutters align-items-end">
                <h1 class="d-md-flex">
                    <?php echo esc_html($product_option->name[0]->value); ?>
                </h1>
                <?php if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL)) { ?> 
                <div class="product-price">
                    <?php if(!empty($product_option->suggestedPrice)) { ?>
                        <?php if ($user_prices == false) { ?>
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($product_option->price->gross); ?></span></div>
                            <div class="product-old-price d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($product_option->suggestedPrice); ?></span></div>
                        <?php } else { ?> 
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($product_option->price->net); ?></span></div>    
                        <?php } ?>                                   
                    
                        <?php } else if ($user_prices == false) { ?>
                            <div class="product-current-price"><span class="price"><?php echo PropellerHelper::formatPrice($product_option->price->gross); ?></span></div>
                        <?php } else { ?>
                            <div class="product-current-price"><span class="price"><?php echo PropellerHelper::formatPrice($product_option->price->net); ?></span></div>
                        <?php }?>
                    
                    <div class="product-package-details">
                        <span class="product-package"></span>
                        <?php if ($user_prices == false) { ?>
                            <span class="product-price-tax"> <?php echo PropellerHelper::formatPrice($product_option->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span>
                        <?php } else { ?>
                            <span class="product-price-tax"> <?php echo PropellerHelper::formatPrice($product_option->price->gross); ?> <?php echo __('excl. vat', 'propeller-ecommerce'); ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="col d-flex justify-content-end">
                    <?php if (!empty($product_option->inventory)) { ?>
                        <div class="product-stock"><?php echo __('Available', 'propeller-ecommerce'); ?>: <?php echo esc_html($product_option->inventory->totalQuantity); ?></div>
                    <?php }  else { ?>
                        <div class="product-stock out-of-stock"><?php echo __('Available as backorder', 'propeller-ecommerce'); ?></div>
                    <?php } ?>
                </div>
                
                <?php if ( $product_option->isOrderable === 'Y') { ?>
                    <div class="col add-to-basket">                       
                        <form class="add-to-basket-form d-flex" name="add-product" method="post">
                            <input type="hidden" name="product_id" value="<?php echo esc_attr($product_option->productId); ?>">
                            <input type="hidden" name="action" value="cart_add_item">
                            <div class="input-group product-quantity align-items-center">
                                <label class="sr-only" for="quantity-item-<?php echo esc_attr($product_option->productId); ?>"><?php echo __("Quantity", 'propeller-ecommerce'); ?></label>
                                <span class="input-group-prepend incr-decr">
                                    <button type="button" class="btn-quantity" 
                                    data-type="minus">-</button>
                                </span>
                                <input
                                    type="number"
                                    ondrop="return false;" 
                                    onpaste="return false;"
                                    onkeypress="return event.charCode>=48 && event.charCode<=57"
                                    id="quantity-item-<?php echo esc_attr($product_option->productId); ?>"
                                    class="quantity large form-control input-number product-quantity-input"
                                    name="quantity"
                                    autocomplete="off"
                                    min="<?php echo esc_attr($product_option->minimumQuantity); ?>"
                                    value="<?php echo esc_attr($product_option->minimumQuantity); ?>"
                                    data-min="<?php echo esc_attr($product_option->minimumQuantity); ?>"
                                    data-unit="<?php echo esc_attr($product_option->unit); ?>"
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
                <?php /*
                    <div class="col-auto">
                        <div class="favorites">
                            <div class="favorite-add-form">
                                <form name="add_favorite" class="validate form-handler favorite" method="post" novalidate="novalidate">
                                    <input type="hidden" name="action" value="favorites_add_item">
                                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product_option->productId); ?>">
                                
                                    <button type="submit" class="btn-favorite" rel="nofollow">
                                        <svg class="icon icon-product-favorite icon-heart">
                                            <use class="header-shape-heart" xlink:href="#shape-favorites"></use>
                                        </svg>
                                    </button>
                                </form>				
                            </div>
                        </div>
                    </div> */ ?>
                <?php } else { ?>
                    <div class="col-12">
                        <div class="alert alert-dark alert-not-available"><?php echo __('Product is no longer available', 'propeller-ecommerce'); ?></div>
                    </div>
                <?php } }?>
            </div>
        <?php } ?>
    <?php } ?>
</div>