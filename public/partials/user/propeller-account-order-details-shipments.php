<div class="order-shipment-status d-lg-flex justify-content-lg-end">
    <span class="shipment-<?php echo isset($order->shipments) && count($order->shipments) ? $order->shipments[0]->status : __('Unknown', 'propeller-ecommerce'); ?>">
        <?php echo isset($order->shipments) && count($order->shipments) ? $order->shipments[0]->status : __('Unknown', 'propeller-ecommerce'); ?>
    </span>
</div>