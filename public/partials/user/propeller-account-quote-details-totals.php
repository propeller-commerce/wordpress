<?php

use Propeller\PropellerHelper;

?>
<div class="row align-items-end order-total-details">
    <div class="col-12 col-md-7 order-2 order-lg-1">
        
    </div> 
    <div class="col-12 col-md-5 order-1 order-lg-2">
        <div class="order-totals">
            <div class="row align-items-baseline order-calculation">
                <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Subtotal', 'propeller-ecommerce'); ?></div>
                <div class="col-4 col-lg-4 ml-auto order-price text-right">
                    <?php 
                        $totalGross = $order->total->gross;
                        $postageGross = $order->postageData->gross;
                        $subTotal = $totalGross-$postageGross;
                    ?>
                    <div class="order-total">
                        <span class="symbol">&euro;&nbsp;</span><span class="order-total-subtotal"><?php echo PropellerHelper::formatPrice($subTotal); ?></span>
                    </div>
                </div>
            </div>
            <?php if (!empty( $order->total->discountPercentage )) {?>
                <div class="row align-items-baseline order-calculation">
                    <div class="col-8 col-lg-5"><?php echo __('Discount', 'propeller-ecommerce'); ?></div>
                    <div class="col-4 col-lg-4 ml-auto order-price text-right">
                        <div class="order-total">
                            -<span class="symbol">&euro;&nbsp;</span><span class="propel-total-voucher"><?php echo esc_html($order->total->discountPercentage); ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row align-items-baseline order-calculation">
                <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Shipping costs', 'propeller-ecommerce'); ?></div>
                <div class="col-4 col-lg-4 ml-auto order-price text-right">
                    <div class="order-total">
                        <span class="symbol">&euro;&nbsp;</span><span class="order-total-excl-btw"><?php echo PropellerHelper::formatPrice($order->postageData->gross); ?></span>
                    </div>
                </div>
            </div>
            <div class="row align-items-baseline order-calculation">
                <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total excl. VAT', 'propeller-ecommerce'); ?></div>
                <div class="col-4 col-lg-4 ml-auto order-price text-right">
                    <div class="order-total">
                        <span class="symbol">&euro;&nbsp;</span><span class="order-total-excl-btw"><?php echo PropellerHelper::formatPrice($order->total->gross); ?></span>
                    </div>
                </div>
            </div>
            <?php if (!empty($order->total->taxPercentages)) { 
                foreach ($order->total->taxPercentages as $taxPercentage) { ?>
                <div class="row align-items-baseline order-calculation">
                    <div class="col-8 col-lg-6 col-xl-5"><?php echo esc_html($taxPercentage->percentage); ?>% <?php echo __('VAT', 'propeller-ecommerce'); ?></div>
                    <div class="col-4 col-lg-4 ml-auto order-price text-right">
                        <div class="order-total-btw">
                            <?php 
                                $totalNet = $order->total->net;
                                $totalGross = $order->total->gross;
                                $totalBTW = $totalNet-$totalGross;
                            ?>
                            <span class="symbol">&euro;&nbsp;</span><span class="order-total-btw"><?php echo PropellerHelper::formatPrice($totalBTW); ?></span>
                        </div>
                    </div>
                </div>
            <?php } } ?>
            <div class="order-grand-total">
                <div class="row align-items-baseline">
                    <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total', 'propeller-ecommerce'); ?></div>
                    <div class="col-4 col-lg-4 ml-auto order-price text-right">
                        <div class="order-total">
                            <span class="symbol">&euro;&nbsp;</span><span class="order-total-price"><?php echo PropellerHelper::formatPrice($order->total->net); ?></span>
                        </div>
                    </div> 
                </div>
                
            </div>
        </div>
    </div> 
</div>