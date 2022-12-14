<?php

    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
    use Propeller\PropellerHelper;

?>

<div class="order-product-item">
    <div class="row no-gutters align-items-start">        
        <div class="col-2 col-md-2 col-lg-1 px-4 product-image order-1">
            <?php if(is_object($item->product->cluster)) { ?> 
                <a href="<?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->cluster->slug[0]->value); ?>">
            <?php } else { ?>
                <a href="<?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">
            <?php } ?> 	          												 
                <img class="img-fluid"
                loading="lazy"
                src="<?php echo $item->product->has_images() ? $item->product->images[0]->images[0]->url : $obj->assets_url . '/img/no-image-card.webp'; ?>" 
                alt="<?= $item->product->name[0]->value; ?>">
            </a>
        </div>
        <div class="col-10 col-md-4 col-lg-5 pr-5 product-description order-2">            
            <?php if(is_object($item->product->cluster)) { ?> 
                <a class="product-name" href="<?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->cluster->slug[0]->value); ?>">
            <?php } else { ?>
                <a class="product-name" href="<?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">
            <?php } ?> 	 
                <?= $item->name; ?>
            </a>
            <div class="product-sku">
                <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?= $item->sku; ?>
            </div>
            
        </div>
            <!-- <div class="offset-2 offset-md-0 col-10 col-md-2 reference order-md-3 order-6 ">
            <?php if(!empty($item->notes)) { ?>
                <div class="d-block d-md-none label-title"><?php echo __('Reference', 'propeller-ecommerce'); ?></div>
            <?php } ?>
            <?= $item->notes; ?> 
        </div>-->
        <div class="offset-2 offset-md-0 col-2 col-md-2 order-4">
            <div class="d-block d-md-none label-title"><?php echo __('Quantity', 'propeller-ecommerce'); ?></div>
            <span class="product-quantity"><?= $item->quantity; ?></span>             
        </div>
        <div class="col-2 order-5 price-per-item">
            <div class="d-block d-md-none label-title"><?php echo __('Price', 'propeller-ecommerce'); ?></div>
            <span class="price"><span class="symbol">&euro;&nbsp;</span>
            <?= PropellerHelper::formatPrice($item->priceTotal); ?>
            </span>                    
        </div>
        <div class="col-2 order-5 order-md-6 order-status">
            <div class="d-block d-md-none label-title"><?php echo __('Status', 'propeller-ecommerce'); ?></div>
            <?php 
                $itemStatus = 'Unknown';

                if (!empty($order->shipments)) { 
                    foreach($order->shipments as $shipment) {
                        if (isset($shipment->items)) {
                            foreach($shipment->items as $shipmentItem) {
                                if ($shipmentItem->orderItemId == $item->id) {
                                    if($shipmentItem->quantity == $item->quantity) 
                                        $itemStatus = __('Send','propeller-ecommerce');
                                    else if ($shipmentItem->quantity < $item->quantity && $shipmentItem->quantity != 0)
                                        $itemStatus = __('Backorder','propeller-ecommerce');  
                                    else if ($shipmentItem->quantity == 0 ) 
                                        $itemStatus = __('Canceled','propeller-ecommerce');      
                                }
                            }
                        }
                    }
                }
            ?>
            <span class="shipping-sent <?= strtolower($itemStatus); ?>"><?php echo $itemStatus; ?></span>                        
        </div>
        
    </div>
</div>
