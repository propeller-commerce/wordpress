<div class="row mt-4 mb-4">
    <div class="col-12 col-md-6 ml-md-auto">
        <form name="checkout-notes" class="form-handler checkout-form validate" method="post">
            <input type="hidden" name="action" value="change_order_status" />
            <input type="hidden" name="status" value="NEW" />
            <input type="hidden" name="order_id" value="<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

 echo esc_attr($order->id); ?>" />
            
            <fieldset>
                <legend class="checkout-header"><?php echo __('Notes', 'propeller-ecommerce'); ?></legend>
                <div class="row form-group">
                    <div class="col-form-fields col-12">                   
                        <label class="form-label" for="field_notes"><?php echo __('Your reference (optional)', 'propeller-ecommerce'); ?></label>
                        <input type="text" name="notes" value="" class="form-control" id="field_notes" maxlength="30">
                    </div>
                </div>
            
            </fieldset>
            <fieldset>
                <legend class="checkout-header"><?php echo __('Place your order', 'propeller-ecommerce'); ?></legend>
                <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="termsConditions" id="termsConditions" value="Y" required aria-required="true">
                                    <span><?php echo __('I agree with the ', 'propeller-ecommerce'); ?> <a href="<?php echo esc_url($this->buildUrl('',PageController::get_slug(PageType::TERMS_CONDITIONS_PAGE))); ?>" target="_blank"><?php echo __('Terms and Conditions', 'propeller-ecommerce'); ?></a></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="row form-group form-group-submit align-items-end justify-content-end mt-4 mb-4">
                <div class="col-form-fields col-12">
                    <div class="form-row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn-checkout" ><?php echo __('Place quote as order', 'propeller-ecommerce'); ?></button> 
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>