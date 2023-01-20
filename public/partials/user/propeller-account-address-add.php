<a class="btn-address-edit address-edit open-edit-modal-form" 
    data-form-id="edit_address<?php echo $type; ?>" 
    data-title="<?php echo $title ?>"
    data-target="#edit_address_modal_<?php echo $type; ?>"
    data-toggle="modal"
    role="button">
    <?php echo $title ?>
</a>

<?php echo apply_filters('propel_address_popup', $obj->get_address_obj($type), $type); ?>