<?php

use Propeller\Includes\Controller\SessionController;

?>

    <div class="checkout-wrapper-steps">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="checkout-step"><?php echo __('Step 2', 'propeller-ecommerce'); ?></div>
                <div class="checkout-title"><?php echo __('Delivery details', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <div class="checkout-step-nr">2/3</div>
            </div>
        </div>
    </div>
    <?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) != 'REQUEST') { ?>
        <div class="checkout-wrapper-steps">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="checkout-step"><?php echo __('Step 3', 'propeller-ecommerce'); ?></div>
                    <div class="checkout-title"><?php echo __('Payment details', 'propeller-ecommerce'); ?></div>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="checkout-step-nr">3/3</div>
                </div>
            </div>
        </div>
    <?php } ?>