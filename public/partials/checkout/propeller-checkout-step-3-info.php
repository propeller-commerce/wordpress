<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

?>
<div class="row align-items-start">
    <div class="col-10 col-md-3 col-lg-3">
        <div class="checkout-step"><?php echo __('Step 3', 'propeller-ecommerce'); ?></div>
        <div class="checkout-title"><?php echo __('Payment details', 'propeller-ecommerce'); ?></div>
    </div>
    <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2 user-details">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="addr-title"><?php echo __('Payment method', 'propeller-ecommerce'); ?></div>
                <div class="user-addr-details">
                    <span><?php echo $cart->paymentData->method; ?></span> <span class="price"><?php if($cart->paymentData->grossAmount > 0) { ?>+ <span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($cart->paymentData->grossAmount); ?><?php } ?></span>
                </div>
            </div>
            <?php if(PROPELLER_PARTIAL_DELIVERY) { ?>
                <div class="col-12 col-md-6">
                    <div class="addr-title"><?php echo __('Partial delivery', 'propeller-ecommerce'); ?></div>
                    <div class="user-addr-details">
                    <span><?php if ($cart->postageData->partialDeliveryAllowed == 'N')
                                echo __("I'd like to receive all products at once.", 'propeller-ecommerce');
                                else echo __("I would like to receive the available products as soon as possible, the other products will be delivered later on.", 'propeller-ecommerce'); ?></span>
                        <br>   
                    </div>
                </div>
            <?php } ?>
            <?php if(PROPELLER_SELECTABLE_CARRIERS) { ?>
                <div class="col-12 col-md-6">
                    <div class="addr-title"><?php echo __('Carriers', 'propeller-ecommerce'); ?></div>
                    <div class="user-addr-details">
                        <span><?php echo $cart->carrier; ?></span><br>   
                    </div>
                </div>
            <?php } ?>
            <?php if(PROPELLER_USE_DATEPICKER) { ?>
                <div class="col-12 col-md-6">
                    <div class="addr-title"><?php echo __('Delivery date', 'propeller-ecommerce'); ?></div>
                    <div class="user-addr-details">
                        <span><?php  echo date("d-m-Y", strtotime($cart->postageData->requestDate)); ?></span><br>   
                    </div>
                </div>
            <?php } ?>
        </div>
        
    </div>
    <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
        <div class="edit-checkout">
            <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::CHECKOUT_PAGE),  '3')); ?>">
                <svg class="icon icon-edit" aria-hidden="true">
                    <use xlink:href="#shape-checkout-edit"></use>
                </svg>    
            </a>
        </div>
    </div>
</div>