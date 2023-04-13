<?php

use Propeller\PropellerHelper;

?>
<div class="order-details">
    <div class="row align-items-end">
        <div class="col-12 col-lg-5">
            <div class="order-total-details">
                    <div class="row align-items-baseline">
                        <div class="col-6"><?php echo __('Quote date:', 'propeller-ecommerce'); ?></div>
                        <div class="col-6 order-date">
                            <?php echo date("d-m-Y", strtotime($order->date)); ?>
                        </div>
                    </div>
                    <div class="row align-items-baseline">
                        <div class="col-6"><?php echo __('Total:', 'propeller-ecommerce'); ?></div>
                        <div class="col-6 order-total">
                            <span class="symbol">&euro;&nbsp;</span><span class="order-total-subtotal"><?php echo PropellerHelper::formatPrice($order->total->net); ?></span>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>