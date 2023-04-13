<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Product;
use Propeller\PropellerHelper;

?>
<div class="shopping-cart-summary-items">
    <?php foreach ($order->items as $item) { 
             
        if ( $item->class == 'product' && $item->isBonus !== 'Y' ) { ?>
        
        <div class="row no-gutters align-items-start align-items-md-center sc-item">
            <div class="col-2 col-md-1 product-image">          
                <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">												 
                    <?php if($item->product->has_images()) { ?>          												 
                        <img class="img-fluid" src="<?= $item->product->images[0]->images[0]->url; ?>" alt="<?= $item->product->name[0]->value; ?>">
                    <?php } else { ?> 
                        <img class="img-fluid" 
                            src="<?php echo $this->assets_url . '/img/no-image-card.webp';?>"
                            alt="<?php echo __('No image found', 'propeller-ecommerce' ); ?>">
                    <?php } ?>
                </a>
            </div>
            <div class="col-10 col-md-7 product-description">   
                <div class="product-name">
                    <a class="product-name" href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">
                        <?= $item->name; ?>
                    </a>
                </div>         
                <div class="product-sku">
                    <?php echo __('SKU', 'propeller-ecommerce' ); ?>: <?= $item->sku; ?>
                </div>
            </div>
            <div class="pl-22 col-10 col-md-4 ml-auto d-flex align-items-center justify-content-md-between">
                <div class="item-quantity">
                    <span class="label d-block d-md-inline-flex"><?php echo __('Quantity', 'propeller-ecommerce' ); ?></span> 
                    <?= $item->quantity; ?>
                </div>
                <div class="pl-5 item-price">
                    <span class="label d-block d-md-none"><?php echo __('Price', 'propeller-ecommerce' ); ?></span>
                    <span class="symbol">&euro;&nbsp;</span>
                        <?= PropellerHelper::formatPrice($item->priceTotal); ?>
                </div>
            </div>
        </div>
    <?php } else if ((isset($item->bonusitems) && count($item->bonusitems) > 0) OR ($item->class == 'product' && $item->isBonus == 'Y') ) { ?>
        <div class="order-bonus-wrapper">
            <div class="row no-gutters align-items-start">
                <div class="col mr-auto order-header">
                    <h5><?php echo __('Bonus items', 'propeller-ecommerce' ); ?></h5>
                </div>
            </div>
            <?php if (isset($item->bonusitems) && count($item->bonusitems) > 0 ) { foreach ($item->bonusitems as $bonusItem) { 
                  $bonusItem->product = new Product($bonusItem->product);?>
                <div class="order-bonus-item">
                    <div class="row no-gutters align-items-center">
                        <div class="col-2 col-md-1 order-bonus-image">		
                        <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value); ?>">											
                            <img class="img-fluid" 
                                loading="lazy"
                                src="<<?php echo $bonusItem->product->has_images() ? $bonusItem->product->images[0]->images[0]->url : $this->assets_url . '/img/no-image-card.webp'; ?>" 
                                alt="<?= $bonusItem->product->name[0]->value; ?>">
                            
                        </a>					
                           
                        </div>
                        <div class="col-10 col-md-7 order-bonus-description">
                            <div class="order-bonus-productname">
                                <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value); ?>">	
                                    <?= $bonusItem->name; ?>
                                </a>
                            </div>
                            <div class="order-bonus-productcode">
                                <?php echo __('SKU', 'propeller-ecommerce' ); ?>: <?= $bonusItem->sku; ?>
                            </div>  
                        </div>
                        
                        <div class="pl-22 col-10 col-md-4 ml-auto d-flex align-items-center justify-content-md-between order-bonus-quantity">
                            <div class="item-quantity no-input">
                                <span class="label d-block d-md-inline-flex"><?php echo __('Quantity', 'propeller-ecommerce' ); ?></span>
                                    <?= $bonusItem->quantity; ?>
                            </div>
                            <div class="pl-5 item-price">
                                <span class="label d-block d-md-none"><?php echo __('Price', 'propeller-ecommerce' ); ?></span>
                                <span class="symbol">&euro;&nbsp;</span>
                                    0,00                    
                            </div>
                        </div>
                    </div>
                </div>
            <?php } } else if ($item->class == 'product' && $item->isBonus == 'Y') {?>
                <div class="order-bonus-item">
                    <div class="row no-gutters align-items-center">
                        <div class="col-2 col-md-1 order-bonus-image">		
                        <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">											
                            <img class="img-fluid" 
                                src="<<?php echo $item->product->has_images() ? $item->product->images[0]->images[0]->url : $this->assets_url . '/img/no-image-card.webp'; ?>" 
                                alt="<?= $item->product->name[0]->value; ?>">
                            
                        </a>					
                           
                        </div>
                        <div class="col-10 col-md-7 order-bonus-description">
                            <div class="order-bonus-productname">
                                <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value); ?>">	
                                    <?= $item->name; ?>
                                </a>
                            </div>
                            <div class="order-bonus-productcode">
                                <?php echo __('SKU', 'propeller-ecommerce' ); ?>: <?= $item->sku; ?>
                            </div>  
                        </div>
                        
                        <div class="pl-22 col-10 col-md-4 ml-auto d-flex align-items-center justify-content-md-between order-bonus-quantity">
                            <div class="item-quantity no-input">
                                <span class="label d-block d-md-inline-flex"><?php echo __('Quantity', 'propeller-ecommerce' ); ?></span>
                                    <?= $item->quantity; ?>
                            </div>
                            <div class="pl-5 item-price">
                                <span class="label d-block d-md-none"><?php echo __('Price', 'propeller-ecommerce' ); ?></span>
                                <span class="symbol">&euro;&nbsp;</span>
                                    <?= PropellerHelper::formatPrice($item->priceTotal); ?>                   
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } } ?>
</div>