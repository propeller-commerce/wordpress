<div class="col-12 col-lg-4 pr-lg-0 order-user-detail-wrapper">
    <div class="order-user-details">
        <div class="row align-items-start">
            <div class="col-12">
                <div class="checkout-title"><?php echo __('Billing address', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-12 order-address-details">
                <div class="user-delivery-details">
                    <div class="user-fullname">
                        <?php echo $obj->get_salutation($order->invoiceAddress); ?>
                        <?php echo $order->invoiceAddress[0]->firstName; ?> <?php echo $order->invoiceAddress[0]->middleName; ?> <?php echo $order->invoiceAddress[0]->lastName; ?>
                    </div>
                    <?php echo $order->invoiceAddress[0]->company; ?><br>
                    <?php echo $order->invoiceAddress[0]->street; ?> <?php echo $order->invoiceAddress[0]->number; ?> <?php echo $order->invoiceAddress[0]->numberExtension; ?><br>
                    <?php echo $order->invoiceAddress[0]->postalCode; ?> <?php echo $order->invoiceAddress[0]->city; ?><br>
                    <?php echo !$countries[$order->invoiceAddress[0]->country] ? $order->invoiceAddress[0]->country : $countries[$order->invoiceAddress[0]->country]; ?>
                </div>                         
            </div>
        </div>
    </div>
</div>