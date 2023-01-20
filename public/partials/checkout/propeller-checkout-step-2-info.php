<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

?>
<div class="row align-items-start">
    <div class="col-10 col-md-3 col-lg-3">
        <div class="checkout-step"><?php echo __('Step 2', 'propeller-ecommerce'); ?></div>
        <div class="checkout-title"><?php echo __('Delivery details', 'propeller-ecommerce'); ?></div>
    </div>
    <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2 user-details">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="addr-title"><?php echo __('Delivery address', 'propeller-ecommerce'); ?></div>
                <div class="user-addr-details">
                    <?php echo $delivery_address->company; ?><br>
                    <?php echo $obj->get_salutation($delivery_address); ?> 
                    <?php echo $delivery_address->firstName; ?> <?php echo $delivery_address->middleName; ?> <?php echo $delivery_address->lastName; ?><br>
                    <?php echo $delivery_address->street; ?> <?php echo $delivery_address->number; ?> <?php echo $delivery_address->numberExtension; ?><br>
                    <?php echo $delivery_address->postalCode; ?> <?php echo $delivery_address->city; ?><br>
                    <?php echo !$countries[$delivery_address->country] ? $delivery_address->country : $countries[$delivery_address->country]; ?>
                    
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="addr-title"><?php echo __('Shipping', 'propeller-ecommerce'); ?></div>
                <div class="user-addr-details">
                <span class="price"><span class="symbol">&euro;&nbsp;</span>
                    <?php 
                                echo PropellerHelper::formatPrice($cart->postageData->postage);
                    ?>
                    
                    </span><br>
                    <!-- Verwacht bezorgmoment:<br>
                    <span class="delivery-date">Woensdag 28 juli</span>                                         -->
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
        <div class="edit-checkout">
            <a href="<?php echo $this->buildUrl(PageController::get_slug(PageType::CHECKOUT_PAGE),  '2'); ?>">
                <svg class="icon icon-edit" aria-hidden="true">
                    <use xlink:href="#shape-checkout-edit"></use>
                </svg>    
            </a>
        </div>
    </div>
</div>