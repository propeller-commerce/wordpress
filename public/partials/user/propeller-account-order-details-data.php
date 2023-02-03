<?php

    use Propeller\PropellerHelper;

?>

<div class="order-details">
        <div class="row align-items-start">
            <div class="col-12 col-lg-6">
                <div class="order-total-details">
                        <div class="row align-items-baseline">
                            <div class="col-6 col-lg-5 col-xl-4 label-title"><?php echo __('Order date:', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 order-date">
                                <?php echo date("d-m-Y", strtotime($order->date)); ?>
                            </div>
                        </div>
                        <div class="row align-items-baseline">
                            <div class="col-6 col-lg-5 col-xl-4 label-title"><?php echo __('Total:', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 order-total">
                                <span class="symbol">&euro;&nbsp;</span><span class="order-total-subtotal"><?php echo PropellerHelper::formatPrice($order->total->net); ?></span>
                            </div>
                        </div>
                        <div class="row align-items-baseline">
                            <div class="col-6 col-lg-5 col-xl-4 label-title"><?php echo __('Payment method:', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 order-paymethod">
                                <?php echo __('On account', 'propeller-ecommerce'); ?>
                            </div>
                        </div>
                        <?php if (!empty($order->shipments)) { 
                            foreach ($order->shipments as $shipment) { ?>
                            <div class="row align-items-baseline">
                                <div class="col-6 col-lg-5 col-xl-4 label-title"><?php echo __('Track and trace:', 'propeller-ecommerce'); ?></div>
                                <div class="col-4 order-shippingmethod">
                                    <?php echo esc_html($shipment->trackAndTrace[0]->code); ?>
                                </div>
                            </div>
                        <?php } } ?>
                        <!-- <div class="row align-items-baseline">
                            <div class="col-6 col-lg-5 col-xl-4 label-title"><?php echo __('Shipping method:', 'propeller-ecommerce'); ?></div>
                            <div class="col-4 order-shippingmethod">
                            Post-NL
                            </div>
                        </div> -->
                </div>
            </div>
            <div class="col-12 col-lg-6 order-shippment-links">

                <?php echo apply_filters('propel_order_details_shipments', $order); ?>
                
                <div class="order-links text-lg-right">
                    
                    <?php echo apply_filters('propel_order_details_pdf', $order); ?>

                    <?php echo apply_filters('propel_order_details_returns', $order); ?>

                    <?php echo apply_filters('propel_order_details_reorder', $order); ?>
                    
                </div>
            </div>
        </div>
    </div>