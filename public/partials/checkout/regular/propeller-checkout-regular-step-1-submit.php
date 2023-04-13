<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Enum\AddressType;

?>
<form name="checkout" class="form-handler checkout-form validate" method="post" action="">

    <input type="hidden" name="action" value="cart_step_1" />
    <input type="hidden" name="step" value="<?php echo esc_attr($slug); ?>" />
    <input type="hidden" name="next_step" value="2" />
  
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