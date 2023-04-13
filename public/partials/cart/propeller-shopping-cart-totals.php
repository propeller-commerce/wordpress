<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\PropellerHelper;
?>
<div class="col-12 col-lg-4">
    <div class="shopping-cart-totals">
        <div class="row align-items-baseline">
            <div class="col-12">
                <div class="sc-items"><?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == 'REQUEST') echo __('Quote overview', 'propeller-ecommerce'); else echo __('Order overview', 'propeller-ecommerce'); ?> (<span class="propel-total-items"><?php echo $this->get_items_count();?></span> <?php echo __('items', 'propeller-ecommerce'); ?>)</div>
                <hr>
            </div>
        </div>
        <div class="row align-items-baseline sc-calculation">
            <div class="col-6 col-lg-6 col-xl-5"><?php echo __('Subtotal', 'propeller-ecommerce'); ?></div>
            <div class="col-6 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-subtotal"><?php echo PropellerHelper::formatPrice($cart->total->subTotal); ?></span>
                </div>
            </div>
        </div>
        <?php if(!empty($cart->total->discountGross)) { ?>
            <div class="row align-items-baseline sc-calculation propel-discount">
                <div class="col-6 col-lg-5"><?php echo __('Discount', 'propeller-ecommerce'); ?></div>
                <div class="col-6 ml-auto sc-price text-right">
                    <div class="sc-total">
                        <span class="symbol">&euro;&nbsp;</span><span class="propel-total-voucher"><?php echo PropellerHelper::formatPrice($cart->total->discountGross); ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    
        <div class="row align-items-baseline sc-calculation">
            <div class="col-6 col-xl-5"><?php echo __('Shipping costs', 'propeller-ecommerce'); ?></div>
            <div class="col-6 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-shipping"><?php echo PropellerHelper::formatPrice($cart->postageData->postage) ?></span>
                </div>
            </div>
        </div>
        <div class="row align-items-baseline sc-calculation">
            <div class="col-6 col-lg-6 col-xl-5"><?php echo __('Total excl. VAT', 'propeller-ecommerce'); ?></div>
            <div class="col-6 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-excl-btw"><?php echo PropellerHelper::formatPrice($cart->total->totalGross); ?></span>
                </div>
            </div>
        </div>
        <div class="row align-items-baseline sc-calculation">
            <?php 
                $taxPercentage = '0';
                if(!empty($cart->taxLevels)) { 
                    foreach($cart->taxLevels as $taxLevel) {
                        if ($taxLevel->taxCode ==='H') {
                            $taxPercentage = '21';
                        } else {
                            $taxPercentage = '9';
                        }
                    }
                }
            ?>
            <div class="col-6 col-xl-5"><?php echo esc_html($taxPercentage); ?>% <?php echo __('VAT', 'propeller-ecommerce'); ?></div>
            <div class="col-6 ml-auto sc-price text-right">
                <div class="sc-total-btw">
                    <?php 
                        $totalNet = $cart->total->totalNet;
                        $totalGross = $cart->total->totalGross;
                        $totalBTW = $totalNet-$totalGross;
                    ?>
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-btw"><?php echo PropellerHelper::formatPrice($totalBTW); ?></span>
                </div>
            </div>
        </div>
        <div class="sc-grand-total">
            <div class="row align-items-baseline">
                <div class="col-6 col-xl-5"><?php echo __('Total', 'propeller-ecommerce'); ?></div>
                <div class="col-6 ml-auto sc-price text-right">
                    <div class="sc-total">
                        <span class="symbol">&euro;&nbsp;</span><span class="propel-total-price"><?php echo PropellerHelper::formatPrice($cart->total->totalNet); ?></span>
                    </div>
                </div> 
            </div>
            
        </div>
    </div>
</div>