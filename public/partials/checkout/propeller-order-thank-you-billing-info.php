<div class="col-12 col-lg-4 pr-lg-0 order-user-detail-wrapper">
    <div class="order-user-details">
        <div class="row align-items-start">
            <div class="col-12">
                <div class="checkout-title"><?php echo __('Billing address', 'propeller-ecommerce'); ?></div>
            </div>
            <div class="col-12 order-address-details">
                <div class="user-delivery-details">
                    <div class="user-fullname">
                        <?= $obj->get_salutation($order->invoiceAddress); ?>
                        <?= $order->invoiceAddress[0]->firstName; ?> <?= $order->invoiceAddress[0]->middleName; ?> <?= $order->invoiceAddress[0]->lastName; ?>
                    </div>
                    <?= $order->invoiceAddress[0]->company; ?><br>
                    <?= $order->invoiceAddress[0]->street; ?> <?= $order->invoiceAddress[0]->number; ?> <?= $order->invoiceAddress[0]->numberExtension; ?><br>
                    <?= $order->invoiceAddress[0]->postalCode; ?> <?= $order->invoiceAddress[0]->city; ?><br>
                    <?= !$countries[$order->invoiceAddress[0]->country] ? $order->invoiceAddress[0]->country : $countries[$order->invoiceAddress[0]->country]; ?>
                </div>                         
            </div>
        </div>
    </div>
</div>