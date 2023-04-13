<?php

    use Propeller\PropellerHelper;

?>
<div class="col-6 col-md-3 mb-4">
    <label class="form-check-label paymethod">
        <span class="row d-flex align-items-center text-center">        
            <input type="radio" name="payMethod" id="payMethod_<?php echo esc_attr($payMethod->code); ?>" value="<?php echo esc_attr($payMethod->code); ?>" title="Select paymethod" required="required" data-rule-required="true" required="required" aria-required="true" class="required" />
            <div class="paymethod-img col-12">
                <svg class="icon icon-paymethod-logo" aria-hidden="true">
                    <use xlink:href="#shape-<?php echo esc_attr($payMethod->description); ?>"></use>
                </svg> 
            </div> 
            <div class="paymethod-name col-12"><?php echo esc_html($payMethod->description); ?></div>
            <div class="paymethod-cost col-12"><span class="currency">&euro;</span> <?php echo PropellerHelper::formatPrice($payMethod->price); ?></div>
        </span>
    </label>                                                
</div>