<form name="checkout-paymethod" class="form-handler checkout-form validate" method="post">
    <input type="hidden" name="action" value="cart_step_3" />
    <input type="hidden" name="step" value="<?php

use Propeller\PropellerHelper;

 echo esc_attr($slug); ?>" />
    <input type="hidden" name="next_step" value="summary" />
    <input type="hidden" name="icp" value="N" />

    <fieldset>
        <div class="row form-group">
            <div class="col-form-fields col-12">
    
                <?php echo apply_filters('propel_checkout_paymethods', $cart->payMethods, $cart, $obj); ?>

            </div>
        </div>
    </fieldset>
    <?php if (PROPELLER_PARTIAL_DELIVERY) { ?>
        <fieldset>
            <div class="row form-group mt-4">
                <div class="col-12">
                    <div class="checkout-title"><?php echo __('Partial delivery', 'propeller-ecommerce'); ?></div>
                </div>
            </div>
            <div class="row form-group partial-deliveries">
                <div class="col-form-fields col-12 mx-5">
                    <label class="form-check-label partialDelivery">
                        <input type="radio" name="partialDeliveryAllowed" id="partialDeliveryAllowed_all" value="N" checked /> 
                            
                        <span class="partial-name"><?php echo __("I'd like to receive all products at once.", 'propeller-ecommerce'); ?></span>
                    </label>                                                
                </div>
                <div class="col-form-fields col-12 mx-5">
                    <label class="form-check-label partialDelivery"> 
                        <input type="radio" name="partialDeliveryAllowed" id="partialDeliveryAllowed_semi" value="Y" /> 
                            
                        <span class="partial-name"><?php echo __("I would like to receive the available products as soon as possible, the other products will be delivered later on.", 'propeller-ecommerce'); ?></span> 
                    </label>                                                
                </div>
            </div>
        </fieldset>
    <?php } else { ?>
        <input type="hidden" name="partialDeliveryAllowed" value="N" />
    <?php } ?>
    <?php if (PROPELLER_SELECTABLE_CARRIERS) { ?>
        <fieldset>
            <div class="row form-group mt-4">
                <div class="col-12">
                    <div class="checkout-title"><?php echo __('Carriers', 'propeller-ecommerce'); ?></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-form-fields col-12">
                    <div class="row px-2 form-row form-check carriers">
                        <?php 
                        $selected_carrier = $this->get_carrier();
                        foreach ($cart->carriers as $carrier) { ?> 
                        <div class="col-12 col-md-8">
                            <label class="form-check-label carrier-label <?= $selected_carrier == $carrier->name ? 'selected' : ''; ?>">
                                <span class="row d-flex align-items-center">
                                    <input type="radio" name="carrier" value="<?= $carrier->name; ?>" title="Selecteer Verzendwijze." data-rule-required="true" required="required" aria-required="true" class="required" <?= $selected_carrier == $carrier->name ? 'checked="checked"' : ''; ?>> 
                                    <span class="carrier-name col-4 col-md-3"><?= $carrier->name; ?></span> 
                                    <span class="carrier-cost col-3"><span class="currency">&euro;</span> <?php echo PropellerHelper::formatPrice($carrier->price); ?></span>
                                    <?php if(!empty($carrier->logo)) { ?>
                                        <span class="col-5 col-md-6 d-flex justify-content-end"> 
                                            <img src="<?= $carrier->logo; ?>" class="carrier-logo">
                                        </span>
                                    <?php } ?>
                                </span>
                            </label>                                                
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </fieldset>
    <?php } else { ?>
        <input type="hidden" name="carrier" value="" />
    <?php } ?>
    <?php if (PROPELLER_USE_DATEPICKER) { ?>
        <fieldset>
            <div class="row form-group mt-4">
                <div class="col-12">
                    <div class="checkout-title"><?php echo __('Delivery date', 'propeller-ecommerce'); ?></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-form-fields col-12 col-md-8">
                    <div class="row px-2 d-flex form-row form-check deliveries">
                        <?php 
                            $days   = [];
                            $period = new DatePeriod(
                                new DateTime(), 
                                new DateInterval('P1D'), 
                                2
                            );

                            foreach ($period as $day)
                            {
                                $days[] = [
                                    $day->format('l'),
                                    $day->format('j F'),
                                    $day->format('Y-m-d\T00:00:00\Z')
                                ];
                            }
                        ?>
                        <?php                      
                        $selected_delivery_date = $this->get_postage_data()->requestDate;

                        foreach ($days as $delivery_day) { ?>

                            <div class="col-6 col-sm-3 mb-4">
                                <label class="form-check-label delivery <?= $selected_delivery_date == $delivery_day[2] ? 'selected' : ''; ?>">
                                    <span class="row d-flex align-items-center text-center">
                                        <input type="radio" name="delivery_select" value="<?= $delivery_day[2]; ?>" title="Select delivery date" data-rule-required="true" required="required" aria-required="true" class="required"<?= $selected_delivery_date == $delivery_day[2] ? 'checked="checked"' : ''; ?>> 
                                        <div class="delivery-day col-12"><?= $delivery_day[0]; ?></div> <div class="delivery-date col-12"><?= $delivery_day[1]; ?></div>
                                    </span>
                                </label>                                                
                            </div>
                        <?php } ?>

                        <div class="col-6 col-sm-3 mb-4">
                            <label class="form-check-label delivery">
                                <span class="row d-flex align-items-center text-center justify-content-center">
                                    <input type="radio" name="delivery_select" value="0" title="Select delivery date" data-rule-required="true" required="required" aria-required="true" class="required custom-date"> 
                                    <svg class="icon icon-calendar" aria-hidden="true">
                                        <use xlink:href="#shape-calendar"></use>
                                    </svg>  
                                    <div class="d-none delivery-day col-12"></div> <div class="d-none delivery-date col-12"></div>
                                </span>
                            </label>                                                
                        </div>
                        <div class="calendar-modal modal modal-fullscreen-sm-down fade" id="datePickerModal" tabindex="-1" role="dialog" aria-labelledby="datePickerModalContent">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="datePickerModalContent"><?php _e( 'Choose a delivery date', 'propeller-ecommerce' ); ?></h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="calendar-wrapper" id="calendar-wrapper"></div>
                                        <div id="editor"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    <?php } else { ?>
        <input type="hidden" name="delivery_select" value=" " />
    <?php } ?>
    <?php echo apply_filters('propel_checkout_regular_step_3_submit', $cart, $obj); ?>
    
</form>