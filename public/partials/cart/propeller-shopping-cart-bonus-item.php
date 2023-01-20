<?php

    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
    use Propeller\Includes\Object\Product;
    use Propeller\PropellerHelper;

    $bonusItem->product = new Product($bonusItem->product);

?> 
<div class="row sc-bonus-item no-gutters align-items-start">
    <div class="col-3 col-md-1 sc-bonus-image">							
        <a href="<?php echo $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value); ?>">											
            <img class="img-fluid" 
                src="<?php echo $bonusItem->product->has_images() ? $bonusItem->product->images[0]->images[0]->url : $obj->assets_url . '/img/no-image-card.webp'; ?>" 
                alt="<?php echo $bonusItem->product->name[0]->value; ?>"> 
        </a>
    </div>
    <div class="col-9 col-md-3 col-lg-6 sc-bonus-description">
        <div class="sc-bonus-productname">
            <a href="<?php echo $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $bonusItem->product->slug[0]->value); ?>">
                <?php echo $bonusItem->product->name[0]->value; ?>
            </a>
        </div>
        <div class="sc-bonus-productcode">
            <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo $bonusItem->product->supplierCode; ?>
        </div>  
    </div>
    <div class="col-8 col-md-2 col-lg-1 ml-auto sc-bonus-quantity">
        <div class="product-quantity no-input text-center">
            <?php echo $bonusItem->quantity; ?>
        </div>
    </div>
    <div class="col-4 col-md-2 col-lg-2 sc-bonus-price text-left">
        <div class="sc-bonus-total"><span class="symbol">&euro;&nbsp;</span> <?php echo PropellerHelper::formatPrice($bonusItem->totalPrice); ?></div>
    </div>
</div>