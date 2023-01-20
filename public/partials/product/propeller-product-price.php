<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\PropellerHelper;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

if(!empty($product->suggestedPrice)) { ?>
    <div class="product-old-price d-md-inline-flex"><span class="price"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($product->suggestedPrice); ?></span></div>
    <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($product->price->gross); ?>  <small><?php _e( 'excl. VAT', 'propeller-ecommerce' ); ?></small></span></div>
<?php } else { ?>
    <div class="product-current-price"><span class="price"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($product->price->gross); ?>  <small><?php _e( 'excl. VAT', 'propeller-ecommerce' ); ?></small></span></div>
<?php } ?>

<div class="product-package-details">
    <span class="product-package"></span>
    <?php if ($user_prices == true) { ?>
        <span class="product-price-tax"><?php _e( 'Retail suggested price', 'propeller-ecommerce' ); ?>  &euro; <?php echo PropellerHelper::formatPrice($product->price->net); ?> <?php _e( 'incl. VAT', 'propeller-ecommerce' ); ?></span>
    <?php } ?>
</div>