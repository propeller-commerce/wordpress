<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\PropellerHelper;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

?>
<div class="row no-gutters align-items-end product-price-details">
    <div class="col-auto">
        <div class="product-price">
            <?php if(!empty($cluster_product->storePrice)) { ?>
                <?php if ($user_prices == false) { ?>
                    <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->gross); ?> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span></div>
                    <div class="product-old-price d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->storePrice); ?> </span></div>
                <?php } else { ?> 
                    <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span></div>    
                <?php } ?>                                   
            
            <?php } else if ($user_prices == false) { ?>
                <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->gross); ?> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span></div>
            <?php } else { ?>
                <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span></div>
            <?php }?>
            
            <div class="product-package-details">
                <span class="product-package"></span>
                <?php if ($user_prices == false) { ?>
                    <span class="product-price-tax"><span class="symbol">&euro;&nbsp;</span><span class="product-price-tax-val"><?php echo PropellerHelper::formatPrice($cluster_product->price->net); ?></span> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span>
                <?php } else { ?>
                    <span class="product-price-tax"><span class="symbol">&euro;&nbsp;</span><span class="product-price-tax-val"><?php echo PropellerHelper::formatPrice($cluster_product->price->gross); ?></span> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php echo apply_filters('propel_cluster_stock', $cluster_product); ?>

</div>