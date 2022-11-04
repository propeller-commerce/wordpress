<div class="user-details">
    <?= $invoice_address->company; ?><br>
    <?= $invoice_address->firstName; ?> <?= $invoice_address->middleName; ?> <?= $invoice_address->lastName; ?><br>
    <?= $invoice_address->street; ?> <?= $invoice_address->number; ?> <?= $invoice_address->numberExtension; ?><br>
    <?= $invoice_address->postalCode; ?> <?= $invoice_address->city; ?><br>
    
    <?php echo !$countries[$invoice_address->country] ? $invoice_address->country : $countries[$invoice_address->country] . '<br>'; ?>
    
    <?= $invoice_address->email; ?>
</div>
<a class="btn-address-edit address-edit open-edit-modal-form" 
    data-form-id="edit_address<?= $invoice_address->id; ?>" 
    data-title="<?php echo __('Edit invoice address', 'propeller-ecommerce'); ?>"
    data-target="#edit_address_modal_<?= $invoice_address->id; ?>"
    data-toggle="modal"
    role="button">
    <?php echo __('Edit', 'propeller-ecommerce'); ?>
</a>