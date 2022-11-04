<a class="btn-return return-request open-edit-modal-form" 
    data-form-id="return_order<?= $order->id; ?>" 
    data-title="<?php echo __('Return request', 'propeller-ecommerce'); ?>"
    data-target="#return_modal_<?= $order->id; ?>"
    data-toggle="modal"
    role="button">
    <?php echo __('Return request', 'propeller-ecommerce'); ?>
</a>

<?= apply_filters('propel_order_details_returns_form', $order); ?>