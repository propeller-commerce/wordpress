<?php

use Propeller\PropellerHelper;

?>
<fieldset>
    <legend class="checkout-header">
        <?php echo __('Shipping method', 'propeller-ecommerce'); ?>
    </legend>
    <div class="row form-group">
        <div class="col-form-fields col-12">
            <div class="row px-2 form-row form-check">           
                <div class="col-12">
                    <div class="shipping-cost-wrapper justify-content-between d-flex align-items-center">
                        <div class="carrier-name col-6"><?php echo __('Shipping costs','propeller-ecommerce'); ?></div> 
                        <div class="carrier-cost col-4 text-right">&euro; <?php echo PropellerHelper::formatPrice($cart->postageData->postage); ?></div>     
                    </div>                                                                                                 
                </div>
            </div>
        </div>
    </div>
</fieldset>