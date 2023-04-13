<div class="col-auto">
    <a class="btn-address-add address-edit open-edit-modal-form" 
        data-form-id="edit_address<?php echo esc_attr($delivery_address->id); ?>"
        data-title="<?php echo __('Add delivery address', 'propeller-ecommerce'); ?>"
        data-target="#edit_address_modal_<?php echo esc_attr($delivery_address->id); ?>"
        data-toggle="modal"
        role="button">
        <?php echo __('Add new delivery address', 'propeller-ecommerce'); ?>
    </a>
</div>