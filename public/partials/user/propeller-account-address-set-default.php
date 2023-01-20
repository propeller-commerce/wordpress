<?php

use Propeller\Includes\Enum\AddressType;

?>
<form name="set_default_address_<?php echo $address->id; ?>" id="set_default_address_<?php echo $address->id; ?>" method="post" class="validate form-handler d-inline-flex address-set-default">
    <input type="hidden" name="id" value="<?php echo $address->id; ?>">
    <input type="hidden" name="action" value="set_address_default">
    <input type="hidden" name="type" value="<?php echo $address->type; ?>">
    <input type="hidden" name="isDefault" value="Y">
    <button type="submit" class="btn-address-link" data-addressid="<?php echo $address->id; ?>"><?php echo $address->type == AddressType::DELIVERY ? __('Set as default delivery address', 'propeller-ecommerce') : __('Set as default billing address', 'propeller-ecommerce'); ?></button>
</form>