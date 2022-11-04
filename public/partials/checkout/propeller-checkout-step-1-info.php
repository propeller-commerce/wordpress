<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<div class="row align-items-start">
    <div class="col-10 col-md-3">
        <div class="checkout-step"><?php echo __('Step 1', 'propeller-ecommerce'); ?></div>
        <div class="checkout-title"><?php echo __('Invoice details', 'propeller-ecommerce'); ?></div>
    </div>
    <div class="col-12 col-md-7 col-lg-6 ml-md-auto order-3 order-md-2 user-details">
    <div class="user-fullname">
            <?= $obj->get_salutation($invoice_address); ?> 
            <?= $invoice_address->firstName; ?> <?= $invoice_address->middleName; ?> <?= $invoice_address->lastName; ?>
        </div>
        <div class="user-addr-details">
            <?= $invoice_address->company; ?><br>
            <?= $invoice_address->street; ?> <?= $invoice_address->number; ?> <?= $invoice_address->numberExtension; ?><br>
            <?= $invoice_address->postalCode; ?> <?= $invoice_address->city; ?><br>

            <?= !$countries[$invoice_address->country] ? $invoice_address->country : $countries[$invoice_address->country]; ?>
        </div>
    </div>
    <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
        <div class="edit-checkout">
            <a href="<?= $obj->buildUrl('', PageController::get_slug(PageType::CHECKOUT_PAGE)); ?>">
                <svg class="icon icon-edit" aria-hidden="true">
                    <use xlink:href="#shape-checkout-edit"></use>
                </svg>    
            </a>
        </div>
    </div>
</div>