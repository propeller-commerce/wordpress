<?php

use Propeller\PropellerHelper;

?>
<div class="shopping-cart-totals">
    <div class="row align-items-baseline">
        <?php 
            $count = 0;
            foreach ($order->items as $item) {
                if($item->class === 'product') {
                    $count++;
                }
            }
        ?>
        <div class="col-12">
            <div class="sc-items">
                <?php 
                if($order->status == 'REQUEST') 
                    echo __('Quote overview', 'propeller-ecommerce');
                else 
                    echo __('Order overview', 'propeller-ecommerce');
                ?> (<span class="propel-total-items"><?php echo (int) $count;?></span> <?php echo __('products', 'propeller-ecommerce'); ?>)
            </div> 
            <hr>
        </div>
    </div>
 
    <div class="row align-items-baseline sc-calculation">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Subtotal', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-subtotal"><?php echo PropellerHelper::formatPrice($order->total->gross); ?></span>
            </div>
        </div>
    </div>
    <?php if(!empty($order->total->discountPercentage)) { ?>
        <div class="row align-items-baseline sc-calculation propel-discount">
            <div class="col-8 col-lg-5"><?php echo __('Discount', 'propeller-ecommerce'); ?></div>
            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-voucher"><?php echo PropellerHelper::formatPrice($order->total->discountPercentage); ?></span>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row align-items-baseline sc-calculation">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Shipping costs', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-shipping"><?php echo PropellerHelper::formatPrice($order->postageData->gross); ?></span>
            </div>
        </div>
    </div>
    <div class="row align-items-baseline sc-calculation">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total excl. VAT', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-excl-btw"><?php echo PropellerHelper::formatPrice($order->total->gross); ?></span>
            </div>
        </div>
    </div>
    <div class="row align-items-baseline sc-calculation">
    
       <?php 
            $taxPercentage = '';
            if(!empty($order->total->taxPercentages)) { 
                foreach($order->total->taxPercentages as $taxPercentages) {
                    $taxPercentage = $taxPercentages->percentage;
                }
            }
           
        ?>
        <div class="col-8 col-lg-6 col-xl-5"><?php echo esc_html($taxPercentage); ?>% <?php echo __('VAT', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total-btw">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-btw"><?php echo PropellerHelper::formatPrice($order->total->tax); ?></span>
            </div>
        </div>
    </div>
    <div class="sc-grand-total">
        <div class="row align-items-baseline">
            <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total', 'propeller-ecommerce'); ?></div>
            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-price"><?php echo PropellerHelper::formatPrice($order->total->net); ?></span>
                </div>
            </div> 
        </div>
        
    </div>
</div>