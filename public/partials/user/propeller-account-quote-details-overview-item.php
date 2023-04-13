<?php

    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
    use Propeller\PropellerHelper;

?>

<div class="order-product-item">
    <div class="row no-gutters align-items-start">        
        <div class="col-2 col-md-2 col-lg-1 px-22 product-image">
            <?php if(is_object($item->product->cluster)) { ?> 
                <a href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->cluster->slug[0]->value, $item->product->cluster->urlId)); ?>">
            <?php } else { ?>
                <a href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value, $item->product->urlId)); ?>">
            <?php } ?> 	           												 
                <img class="img-fluid" 
                    loading="lazy"
                    src="<?php echo esc_url($item->product->has_images() ? $item->product->images[0]->images[0]->url : $obj->assets_url . '/img/no-image-card.webp'); ?>"
                    alt="<?php echo esc_attr($item->product->name[0]->value); ?>">
            </a>
        </div>
        <div class="col-10 col-md-4 col-lg-5 pr-5 product-description">            
            <a class="product-name" href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $item->product->slug[0]->value, $item->product->urlId)); ?>">
                <?php echo esc_html($item->product->name[0]->value); ?>
            </a>
            <div class="product-sku">
            <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo esc_html($item->sku); ?>
            </div>
            
        </div>
        <!-- <div class="col-5 col-md-3 reference">
            <?php echo esc_html($item->notes); ?>
        </div> -->
        <div class="offset-2 offset-md-0 col-2 col-md-2 order-3">
            <div class="d-block d-md-none label-title"><?php echo __('Quantity', 'propeller-ecommerce'); ?></div>
            <span class="product-quantity"><?php echo esc_html($item->quantity); ?></span>
        </div>
        <div class="col-3 col-md-2 order-discount order-4">
            <div class="d-block d-md-none label-title"><?php echo __('Discount', 'propeller-ecommerce'); ?></div>
            <?php if($item->discount) { ?>
                <span class="discount"> - <?php echo esc_html($item->discount); ?>&nbsp;&euro;</span>
            <?php } ?>             
        </div>
        <div class="col-3 col-md-2 price-per-item order-5">
            <div class="d-block d-md-none label-title"><?php echo __('Price', 'propeller-ecommerce'); ?></div>
            <span class="price"><span class="symbol">&euro;&nbsp;</span>
            <?php echo PropellerHelper::formatPrice($item->priceTotal); ?>
            </span>                    
        </div>
        
    
    </div>
</div>