<div class="col-12 col-lg-4 p-lg-0 order-user-detail-wrapper">
    <div class="order-user-details">
        <div class="row align-items-start">
            <div class="col-12">
                <div class="checkout-title"><?php echo __('Delivery address', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-12 order-invoice-details">                        
                <div class="user-invoice-details">
                    <div class="user-fullname">
                        <?php echo $obj->get_salutation($order->deliveryAddress[0]); ?>
                        <?php echo $order->deliveryAddress[0]->firstName; ?> <?php echo $order->deliveryAddress[0]->middleName; ?> <?php echo $order->deliveryAddress[0]->lastName; ?>
                    </div>
                    <?php echo $order->deliveryAddress[0]->company; ?><br>
                    <?php echo $order->deliveryAddress[0]->street; ?> <?php echo $order->deliveryAddress[0]->number; ?> <?php echo $order->deliveryAddress[0]->numberExtension; ?><br>
                    <?php echo $order->deliveryAddress[0]->postalCode; ?> <?php echo $order->deliveryAddress[0]->city; ?><br>
                    <?php echo !$countries[$order->deliveryAddress[0]->country] ? $order->deliveryAddress[0]->country : $countries[$order->deliveryAddress[0]->country]; ?>
                </div>

                <?php echo apply_filters('propel_order_thank_you_shipping_info', $order, $this); ?>
                
            </div>
        </div>
    </div>
</div>