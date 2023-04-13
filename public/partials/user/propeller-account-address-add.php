<a class="btn-address-edit address-edit open-edit-modal-form" 
    data-form-id="edit_address<?php echo $address->id; ?>" 
    data-title="<?php echo esc_attr($title); ?>"
    data-target="#edit_address_modal_<?php echo $address->id; ?>"
    data-toggle="modal"
    role="button">
    <?php echo esc_html($title); ?>
</a>

<?php echo apply_filters('propel_address_popup', $address, $type); ?>