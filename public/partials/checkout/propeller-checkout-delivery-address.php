<div class="col-12 col-md-6 form-group form-check delivery-address-block ">
    <label class="delivery-label form-check-label <?php echo esc_attr($checked_label); ?>">
        <input type="radio" class="form-check-input delivery-addresses" name="delivery_address" value="<?php echo esc_attr($delivery_address->id); ?>" <?php echo (string) $checked; ?>>
        
        <div class="label-delivery-address">
            <?php echo esc_html( $delivery_address->company ); ?><br>
            <?php echo esc_html($delivery_address->firstName); ?> <?php echo esc_html($delivery_address->middleName); ?> <?php echo esc_html($delivery_address->lastName); ?><br>
            <?php echo esc_html($delivery_address->street); ?> <?php echo esc_html($delivery_address->number); ?> <?php echo esc_html($delivery_address->numberExtension); ?><br>
            <?php echo esc_html($delivery_address->postalCode); ?> <?php echo esc_html($delivery_address->city); ?><br>
            <?php echo esc_html($countries[$delivery_address->country]); ?><br>
            <?php echo esc_html($delivery_address->email); ?>
        </div>

        <a class="btn-address-edit address-edit open-edit-modal-form" 
            data-form-id="edit_address<?php echo esc_attr($delivery_address->id); ?>"
            data-title="<?php echo __('Edit delivery address', 'propeller-ecommerce'); ?>"
            data-target="#edit_address_modal_<?php echo esc_attr($delivery_address->id); ?>"
            data-toggle="modal"
            role="button">
            <?php echo __('Edit', 'propeller-ecommerce'); ?>
        </a>
        
    </label>
</div>