<form name="checkout-paymethod" class="form-handler checkout-form validate" method="post">
    <input type="hidden" name="action" value="cart_step_3" />
    <input type="hidden" name="step" value="<?php echo esc_attr($slug); ?>" />
    <input type="hidden" name="next_step" value="summary" />
    <input type="hidden" name="icp" value="N" />

    <fieldset>
        <div class="row form-group">
            <div class="col-form-fields col-12">
    
                <?php echo apply_filters('propel_checkout_paymethods', $cart->payMethods, $cart, $obj); ?>

            </div>
        </div>
    </fieldset>
    
    <?php echo apply_filters('propel_checkout_regular_step_3_submit', $cart, $obj); ?>
    
</form>