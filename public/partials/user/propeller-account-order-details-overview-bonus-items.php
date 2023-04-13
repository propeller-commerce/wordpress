<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Product;
use Propeller\PropellerHelper;

foreach($items as $item) { 
    if ($item->class == 'product' && $item->isBonus =='Y') { ?>
    <div class="order-bonus-wrapper">
        <div class="row align-items-start">
            <div class="col mr-auto order-header">
                <h5><?php echo __('Bonus items!', 'propeller-ecommerce'); ?></h5>
            </div>
        </div>
        <?php  if (isset($item->bonusitems) && count($item->bonusitems)) { 
            foreach ($item->bonusitems as $bonusItem) { 
                $bonusItem->product = new Product($bonusItem->product); ?>
            
            <div class="order-bonus-item">
                <div class="row no-gutters align-items-start">
                    <div class="col-2 col-md-2 col-lg-1 px-4 order-bonus-image">							
                        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value, $bonusItem->product->urlId)); ?>">
                            <img class="img-fluid" 
                            loading="lazy"
                            src="<?php echo esc_url($bonusItem->product->has_images() ? $bonusItem->product->images[0]->images[0]->url : $this->assets_url . '/img/no-image-card.webp'); ?>"
                            alt="<?php echo esc_attr($bonusItem->product->name[0]->value); ?>">
                           
                        </a>
                    </div>
                    <div class="col-10 col-md-4 col-lg-5 pr-5 pl-0 order-bonus-description">
                        <div class="order-bonus-productname"><a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value, $bonusItem->product->urlId)); ?>"><?php echo esc_html($bonusItem->product->name[0]->value); ?></a></div>
                        <div class="order-bonus-productcode">
                            <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo esc_html($bonusItem->sku); ?>
                        </div>  
                    </div>
                
                    <div class="offset-2 offset-md-0 col-2 col-md-2 order-bonus-quantity">
                        <div class="product-quantity no-input">
                        <div class="d-block d-md-none label-title"><?php echo __('Quantity', 'propeller-ecommerce'); ?></div>
                            <?php echo esc_html($bonusItem->quantity); ?>
                        </div>
                    </div>
                    <div class="col-2 order-bonus-price">
                        <div class="d-block d-md-none label-title"><?php echo __('Price', 'propeller-ecommerce'); ?></div>
                        <div class="order-bonus-total"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($bonusItem->totalPrice); ?></div>
                    </div>
                    <div class="col-2 order-bonus-status">
                        <div class="d-block d-md-none label-title"><?php echo __('Status', 'propeller-ecommerce'); ?></div>
                        <?php 
                    if (!empty($this->order->shipments)) { 
                        foreach($this->order->shipments as $shipment) {
                            foreach($shipment->items as $shipmentItem) {
                                if ($shipmentItem->orderItemId == $bonusItem->id) {
                                    if($shipmentItem->quantity == $bonusItem->quantity) 
                                        $itemStatus = __('Send','propeller-ecommerce');
                                    else if ($shipmentItem->quantity < $item->quantity && $shipmentItem->quantity != 0)
                                        $itemStatus = __('Backorder','propeller-ecommerce');  
                                    else if ($shipmentItem->quantity == 0 ) 
                                        $itemStatus = __('Canceled','propeller-ecommerce');      
                                }
                            }
                        }
                    }
                    else 
                        $itemStatus = 'Unknown';
                ?>
                <span class="shipping-sent <?php echo strtolower($itemStatus); ?>"><?php echo esc_html($itemStatus); ?></span>
                    </div>
                </div>
            </div>
        <?php } } ?>
        <?php if($item->class == 'product' && $item->isBonus =='Y') { ?>
            <div class="order-bonus-item">
                <div class="row no-gutters align-items-start">
                    <div class="col-2 col-md-2 col-lg-1 px-4 order-bonus-image">							
                        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value,  $item->product->urlId)); ?>">
                            <img class="img-fluid" 
                            loading="lazy"
                            src="<?php echo esc_url($item->product->has_images() ? $item->product->images[0]->images[0]->url : $this->assets_url . '/img/no-image-card.webp'); ?>"
                            alt="<?php echo esc_attr($item->product->name[0]->value); ?>">
                        </a>
                           
                    </div>
                    <div class="col-10 col-md-4 col-lg-5 pr-5 pl-0 order-bonus-description">
                        <div class="order-bonus-productname"><a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value, $item->product->urlId)); ?>"><?php echo esc_html($item->name); ?></a></div>
                        <div class="order-bonus-productcode">
                            <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo esc_html($item->sku); ?>
                        </div>  
                    </div>
                
                    <div class="offset-2 offset-md-0 col-2 col-md-2 order-bonus-quantity">
                        <div class="product-quantity no-input">
                        <div class="d-block d-md-none label-title"><?php echo __('Quantity', 'propeller-ecommerce'); ?></div>
                            <?php echo esc_html($item->quantity); ?>
                        </div>
                    </div>
                    <div class="col-2 order-bonus-price">
                        <div class="d-block d-md-none label-title"><?php echo __('Price', 'propeller-ecommerce'); ?></div>
                        <div class="order-bonus-total"><span class="symbol">&euro;&nbsp;</span><?php echo PropellerHelper::formatPrice($item->priceTotal); ?></div>
                    </div>
                    <div class="col-2 order-bonus-status">
                        <div class="d-block d-md-none label-title"><?php echo __('Status', 'propeller-ecommerce'); ?></div>
                        <?php 
                            if (!empty($this->order->shipments)) { 
                                foreach($this->order->shipments as $shipment) {
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
                            else 
                                $itemStatus = 'Unknown';
                        ?>
                        <span class="shipping-sent <?php echo strtolower($itemStatus); ?>"><?php echo esc_html($itemStatus); ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } } ?>