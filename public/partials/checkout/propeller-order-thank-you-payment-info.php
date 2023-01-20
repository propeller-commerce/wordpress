<div class="col-12 col-lg-4 pl-lg-0 order-user-detail-wrapper">
    <div class="order-user-details">
        <div class="row align-items-start">
            <div class="col-12">
                <div class="checkout-title"><?php echo __('Payment method', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-12">                         
                <div class="paymethod-details">
                <?php echo $order->paymentData->method; ?>
                </div>
            </div>
        </div>
    </div>
</div>