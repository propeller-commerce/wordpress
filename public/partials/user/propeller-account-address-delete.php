<a class="address-delete open-modal-form" 
    data-form-id="delete_address<?php echo esc_attr($address->id); ?>"
    data-title="<?php echo __('Delete', 'propeller-ecommerce'); ?>"
    data-target="#delete_address_modal_<?php echo esc_attr($address->id); ?>"
    data-toggle="modal">
    <?php echo __('Delete', 'propeller-ecommerce'); ?>
</a>

<?php echo apply_filters('propel_address_delete_popup', $address); ?>