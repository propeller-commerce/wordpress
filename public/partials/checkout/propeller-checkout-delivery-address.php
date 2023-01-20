<div class="col-12 col-md-6 form-group form-check delivery-address-block ">
    <label class="delivery-label form-check-label <?php echo $checked_label; ?>">
        <input type="radio" class="form-check-input delivery-addresses" name="delivery_address" value="<?php echo $delivery_address->id; ?>" <?php echo $checked; ?>> 
        
        <div class="label-delivery-address">
            <?php echo $delivery_address->company; ?><br>
            <?php echo $delivery_address->firstName; ?> <?php echo $delivery_address->middleName; ?> <?php echo $delivery_address->lastName; ?><br>
            <?php echo $delivery_address->street; ?> <?php echo $delivery_address->number; ?> <?php echo $delivery_address->numberExtension; ?><br> 
            <?php echo $delivery_address->postalCode; ?> <?php echo $delivery_address->city; ?><br>
            <?php echo $countries[$delivery_address->country]; ?><br>
            <?php echo $delivery_address->email; ?>
        </div>

        <a class="btn-address-edit address-edit open-edit-modal-form" 
            data-form-id="edit_address<?php echo $delivery_address->id; ?>" 
            data-title="<?php echo __('Edit delivery address', 'propeller-ecommerce'); ?>"
            data-target="#edit_address_modal_<?php echo $delivery_address->id; ?>"
            data-toggle="modal"
            role="button">
            <?php echo __('Edit', 'propeller-ecommerce'); ?>
        </a>
        
    </label>
</div>