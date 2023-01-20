<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<div class="row align-items-start">
    <div class="col-10 col-md-3">
        <div class="checkout-step"><?php echo __('Step 1', 'propeller-ecommerce'); ?></div>
        <div class="checkout-title"><?php echo __('Invoice details', 'propeller-ecommerce'); ?></div>
    </div>
    <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2 user-details">
    <div class="user-fullname">
            <?php echo $obj->get_salutation($invoice_address); ?> 
            <?php echo $invoice_address->firstName; ?> <?php echo $invoice_address->middleName; ?> <?php echo $invoice_address->lastName; ?>
        </div>
        <div class="user-addr-details">
            <?php echo $invoice_address->company; ?><br>
            <?php echo $invoice_address->street; ?> <?php echo $invoice_address->number; ?> <?php echo $invoice_address->numberExtension; ?><br>
            <?php echo $invoice_address->postalCode; ?> <?php echo $invoice_address->city; ?><br>

            <?php echo !$countries[$invoice_address->country] ? $invoice_address->country : $countries[$invoice_address->country]; ?>
        </div>
    </div>
    <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
        <div class="edit-checkout">
            <a href="<?php echo $obj->buildUrl('', PageController::get_slug(PageType::CHECKOUT_PAGE)); ?>">
                <svg class="icon icon-edit" aria-hidden="true">
                    <use xlink:href="#shape-checkout-edit"></use>
                </svg>    
            </a>
        </div>
    </div>
</div>