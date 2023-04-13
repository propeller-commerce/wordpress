<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

?>
<div class="row order-item no-gutters">
    <div class="col-md-2 col-xl-2"><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Quote number', 'propeller-ecommerce'); ?></span><span class="px-0 col-auto"><?php echo esc_html($order->id); ?></span></div>
    <div class="col-md-2"><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="px-0 col-auto"><?php echo date("d-m-Y", strtotime($order->date)); ?></span></div>
    <div class="col-md-2 col-xl-2"><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Quantity', 'propeller-ecommerce'); ?></span><span class="px-0 col-auto"><?php echo sizeof($order->items); ?></div>
    <div class="col-md-2"><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Order total', 'propeller-ecommerce'); ?></span><span class="px-0 col-auto"><span class="symbol">&euro;&nbsp;</span> <?php echo PropellerHelper::formatPrice($order->total->net); ?></span></span></div>
    <div class="col-md-2"> 
            <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span>
            <span class="px-0 col-auto">
                <?php if (isset($order->shipments) && sizeof($order->shipments)) { 
                        echo esc_html( $order->shipments[0]->status );
                    }
                ?>
            </span>
    </div>
    <div class="col-md-2 text-md-right"><a href="<?php echo esc_url($obj->buildUrl('', PageController::get_slug(PageType::QUOTATION_DETAILS_PAGE))); ?>?order_id=<?php echo (int) $order->id; ?>" class="order-details-link"><?php echo __('View quote', 'propeller-ecommerce'); ?></a></div>
</div>