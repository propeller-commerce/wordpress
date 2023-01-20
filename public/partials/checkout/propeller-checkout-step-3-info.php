<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

?>
<div class="row align-items-start">
    <div class="col-10 col-md-3 col-lg-3">
        <div class="checkout-title"><?php echo __('Payment details', 'propeller-ecommerce'); ?></div>
    </div>
    <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2">                         
        <div class="paymethod-details">
            <span><?php echo $cart->paymentData->method; ?></span> <span class="price">+ <span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($cart->paymentData->grossAmount); ?></span>
        </div>
    </div>
    <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
        <div class="edit-checkout">
            <a href="<?php echo $obj->buildUrl(PageController::get_slug(PageType::CHECKOUT_PAGE), '3'); ?>">
                <svg class="icon icon-edit" aria-hidden="true">
                    <use xlink:href="#shape-checkout-edit"></use>
                </svg>    
            </a>
        </div>
    </div>
</div>