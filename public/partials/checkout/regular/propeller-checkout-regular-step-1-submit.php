<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Enum\AddressType;

?>
<form name="checkout" class="form-handler checkout-form validate" method="post" action="">
    <?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == 'REQUEST') { ?>
        <input type="hidden" name="action" value="cart_process" />    
        <input type="hidden" name="status" value="<?php echo SessionController::get(PROPELLER_ORDER_STATUS_TYPE); ?>" />
    <?php } else { ?>
        <input type="hidden" name="action" value="cart_step_1" />
        <input type="hidden" name="step" value="<?php echo $slug; ?>" />
        <input type="hidden" name="next_step" value="2" />
    <?php } ?>
    
    <input type="hidden" name="type" value="<?php echo AddressType::INVOICE; ?>" />
    
    <div class="row form-group form-group-submit">
        <div class="col-form-fields col-12">
            <div class="form-row">
                <div class="col-12 col-md-10">
                    <button type="submit" class="btn-proceed"><?php echo __('Continue', 'propeller-ecommerce'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>