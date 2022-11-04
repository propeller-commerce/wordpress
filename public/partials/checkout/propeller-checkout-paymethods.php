<div class="row px-2 d-flex form-row form-check paymethods radios-container">
    <?php 
        foreach($pay_methods as $payMethod)
            apply_filters('propel_checkout_paymethod', $payMethod, $cart, $obj);
    ?>
</div>