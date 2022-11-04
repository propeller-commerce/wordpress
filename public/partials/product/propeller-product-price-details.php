<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\PropellerHelper;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

?>
<div class="row no-gutters align-items-end product-price-details">
    <div class="col-auto">
        <div class="product-price">
            <?php if(!empty($product->suggestedPrice)) { ?>
                <?php if ($user_prices == false) { ?>
                    <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->price->gross); ?></span></div>
                    <div class="product-old-price d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->suggestedPrice); ?></span></div>
                <?php } else { ?> 
                    <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->price->net); ?></span></div>    
                <?php } ?>                                   
            
            <?php } else if ($user_prices == false) { ?>
                <div class="product-current-price"><span class="price"><?= PropellerHelper::formatPrice($product->price->gross); ?></span></div>
            <?php } else { ?>
                <div class="product-current-price"><span class="price"><?= PropellerHelper::formatPrice($product->price->net); ?></span></div>
            <?php }?>
            
            <div class="product-package-details">
                <span class="product-package"></span>
                <?php if ($user_prices == false) { ?>
                    <span class="product-price-tax"> <?= PropellerHelper::formatPrice($product->price->net); ?> <?php echo __('incl. btw', ''); ?></span>
                <?php } else { ?>
                    <span class="product-price-tax"> <?= PropellerHelper::formatPrice($product->price->gross); ?> <?php echo __('excl. btw', ''); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <?= apply_filters('propel_product_stock', $product); ?>

</div>