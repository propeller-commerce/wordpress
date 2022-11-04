<div class="col-12 col-md-6 form-group form-check delivery-address-block ">
    <label class="delivery-label form-check-label <?= $checked_label; ?>">
        <input type="radio" class="form-check-input delivery-addresses" name="delivery_address" value="<?= $delivery_address->id; ?>" <?= $checked; ?>> 
        
        <div class="label-delivery-address">
            <?= $delivery_address->company; ?><br>
            <?= $delivery_address->firstName; ?> <?= $delivery_address->middleName; ?> <?= $delivery_address->lastName; ?><br>
            <?= $delivery_address->street; ?> <?= $delivery_address->number; ?> <?= $delivery_address->numberExtension; ?><br> 
            <?= $delivery_address->postalCode; ?> <?= $delivery_address->city; ?><br>
            <?php echo $countries[$delivery_address->country]; ?><br>
            <?= $delivery_address->email; ?>
        </div>

        <a class="btn-address-edit address-edit open-edit-modal-form" 
            data-form-id="edit_address<?= $delivery_address->id; ?>" 
            data-title="<?php echo __('Edit delivery address', 'propeller-ecommerce'); ?>"
            data-target="#edit_address_modal_<?= $delivery_address->id; ?>"
            data-toggle="modal"
            role="button">
            <?php echo __('Edit', 'propeller-ecommerce'); ?>
        </a>
        
    </label>
</div>