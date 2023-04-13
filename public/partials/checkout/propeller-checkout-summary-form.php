<?php

    use Propeller\Includes\Controller\SessionController;
    use Propeller\Includes\Enum\PageType;
    use Propeller\Includes\Controller\PageController;
    
?>
<div class="row align-items-start">
    <div class="col-10 col-md-3 col-lg-3">
        <div class="checkout-title"><?php echo __('Notes', 'propeller-ecommerce'); ?></div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form name="checkout-notes" class="form-handler checkout-form validate" method="post">
            <input type="hidden" name="action" value="cart_process" />    
            <input type="hidden" name="status" value="<?php echo SessionController::get(PROPELLER_ORDER_STATUS_TYPE); ?>" />
            <input type="hidden" name="payMethod" value="<?php echo esc_attr($cart->paymentData->method); ?>" />

            <fieldset>
                <div class="row form-group">
                    <div class="col-form-fields col-12 col-md-8">                   
                        <label class="form-label" for="field_extra"><?php echo __('Your reference (optional)', 'propeller-ecommerce'); ?></label>
                        <input name="reference" value="" class="form-control" id="field_extra" placeholder="<?php echo __('Your reference (optional)', 'propeller-ecommerce'); ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-form-fields col-12 col-md-8">                   
                        <label class="form-label" for="field_notes"><?php echo __('Your comment (optional)', 'propeller-ecommerce'); ?></label>
                        <textarea type="text" name="notes" value="" class="form-control" id="field_notes" placeholder="<?php echo __('Your comment (optional)', 'propeller-ecommerce'); ?>"></textarea>
                    </div>
                </div>
                
            </fieldset>
            <hr class="checkout-divider"/>
            <fieldset>
                <?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == 'REQUEST') { ?>
                    <legend class="checkout-header"><?php echo __('Place your quote request', 'propeller-ecommerce'); ?></legend>
                <?php } else { ?> 
                    <legend class="checkout-header"><?php echo __('Place your order', 'propeller-ecommerce'); ?></legend>
                <?php } ?>
                <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 col-md-8">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="termsConditions" id="termsConditions" value="Y" required aria-required="true">
                                    <span><?php echo __('I agree with the', 'propeller-ecommerce'); ?> <a href="<?php echo esc_url($obj->buildUrl('',PageController::get_slug(PageType::TERMS_CONDITIONS_PAGE))); ?>" target="_blank"><?php echo __('Terms and Conditions', 'propeller-ecommerce'); ?></a></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <?php echo apply_filters('propel_checkout_summary_submit', $this->cart, $this); ?> 

        </form>
    </div>
</div>