<div class="col-12 col-lg-4 pl-lg-0 order-user-detail-wrapper">
    <div class="order-user-details">
        <div class="row align-items-start">
            <div class="col-12">
                <div class="checkout-title"><?php echo __('Payment method', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-12">                         
                <div class="paymethod-details">
                <?php echo esc_html($order->paymentData->method); ?>
                </div>
            </div>
        </div>
        <?php if(PROPELLER_PARTIAL_DELIVERY) { ?>
            <div class="row align-items-start mt-4">
                <div class="col-12">
                    <div class="checkout-title"><?php echo __('Partial delivery', 'propeller-ecommerce'); ?></div>
                </div>
                <div class="col-12">                         
                    <div class="paymethod-details">
                        <?php if($order->postageData->partialDeliveryAllowed == 'N')
                                echo __("I'd like to receive all products at once.", 'propeller-ecommerce');
                            else 
                                echo __("I would like to receive the available products as soon as possible, the other products will be delivered later on.", 'propeller-ecommerce'); 
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(PROPELLER_USE_DATEPICKER) { ?>
            <div class="row align-items-start mt-4">
                <div class="col-12">
                    <div class="checkout-title"><?php echo __('Delivery date', 'propeller-ecommerce'); ?></div>
                </div>
                <div class="col-12">                         
                    <div class="paymethod-details">
                        <?php 
                            echo date("d-m-Y", strtotime($order->postageData->requestDate));
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>