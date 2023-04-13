<div class="user-details">
    <?php echo esc_html($invoice_address->company); ?><br>
    <?php echo esc_html($invoice_address->firstName); ?> <?php echo esc_html($invoice_address->middleName); ?> <?php echo esc_html($invoice_address->lastName); ?><br>
    <?php echo esc_html($invoice_address->street); ?> <?php echo esc_html($invoice_address->number); ?> <?php echo esc_html($invoice_address->numberExtension); ?><br>
    <?php echo esc_html($invoice_address->postalCode); ?> <?php echo esc_html($invoice_address->city); ?><br>
    
    <?php echo !$countries[$invoice_address->country] ? $invoice_address->country : $countries[$invoice_address->country] . '<br>'; ?>
    
    <?php echo esc_html($invoice_address->email); ?>
</div>
<a class="btn-address-edit address-edit open-edit-modal-form" 
    data-form-id="edit_address<?php echo esc_html($invoice_address->id); ?>"
    data-title="<?php echo __('Edit invoice address', 'propeller-ecommerce'); ?>"
    data-target="#edit_address_modal_<?php echo esc_html($invoice_address->id); ?>"
    data-toggle="modal"
    role="button">
    <?php echo __('Edit', 'propeller-ecommerce'); ?>
</a>