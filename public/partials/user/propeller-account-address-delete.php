<a class="address-delete open-modal-form" 
    data-form-id="delete_address<?= $address->id; ?>" 
    data-title="<?php echo __('Delete', 'propeller-ecommerce'); ?>"
    data-target="#delete_address_modal_<?= $address->id; ?>"
    data-toggle="modal">
    <?php echo __('Delete', 'propeller-ecommerce'); ?>
</a>

<?= apply_filters('propel_address_delete_popup', $address); ?>