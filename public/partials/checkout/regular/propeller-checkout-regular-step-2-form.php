<?php

use Propeller\Includes\Enum\AddressType;

?>

<form name="checkout-delivery" class="form-handler checkout-form validate" method="post">
    <input type="hidden" name="action" value="cart_step_2" />
    <input type="hidden" name="step" value="<?= $slug; ?>" />
    <input type="hidden" name="next_step" value="3" />
    <input type="hidden" name="type" value="<?= AddressType::DELIVERY; ?>" />
    <input type="hidden" name="phone" value="none-provided" />
    <input type="hidden" name="carrier" value="" />
    
    <?= apply_filters('propel_checkout_delivery_addresses', $cart, $obj); ?>

    <?= apply_filters('propel_checkout_shipping_method', $cart, $obj); ?>

    <?= apply_filters('propel_checkout_regular_step_2_submit', $cart, $obj); ?>

</form>