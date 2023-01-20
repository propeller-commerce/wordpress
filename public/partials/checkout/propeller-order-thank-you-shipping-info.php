<?php

use Propeller\PropellerHelper;

?>
<div class="order-shipping-details">
    <?php echo __('Shipping costs', 'propeller-ecommerce'); ?>: 
    <span class="symbol">&euro;&nbsp;</span><span class="shipping-costs"><?php echo PropellerHelper::formatPrice($order->postageData->gross); ?></span><br>
    <!--  <?php echo __('Expected delivery', 'propeller-ecommerce'); ?>: <span class="order-delivery-date">Wednesday July 28</span> -->
</div>  