<a class="address-edit open-modal-form d-none d-md-flex" 
    data-form-id="edit_address<?= $address->id; ?>" 
    data-title="<?php echo __('Modify', 'propeller-ecommerce'); ?>"
    data-target="#edit_address_modal_<?= $address->id; ?>"
    data-toggle="modal"
    role="button">
    <?php echo __('Modify', 'propeller-ecommerce'); ?>
</a>
<a class="address-edit d-flex d-md-none" href="/edit-address/?address_id=<?= $address->id; ?>"
    data-form-id="edit_address<?= $address->id; ?>" 
    data-title="<?php echo __('Modify', 'propeller-ecommerce'); ?>">
    <?php echo __('Modify', 'propeller-ecommerce'); ?>
</a>

<?php apply_filters('propel_address_popup', $address, $address->type); ?>