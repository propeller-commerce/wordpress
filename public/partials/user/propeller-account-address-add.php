<a class="btn-address-edit address-edit open-edit-modal-form" 
    data-form-id="edit_address<?= $type; ?>" 
    data-title="<?= $title ?>"
    data-target="#edit_address_modal_<?= $type; ?>"
    data-toggle="modal"
    role="button">
    <?= $title ?>
</a>

<?= apply_filters('propel_address_popup', $obj->get_address_obj($type), $type); ?>