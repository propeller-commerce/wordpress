<?php

use Propeller\PropellerHelper;

?>
<div class="shopping-cart-totals">
    <div class="row align-items-baseline">
        <div class="col-12">
            <div class="sc-items"><?php echo __('Order overview', 'propeller-ecommerce'); ?> (<span class="propel-total-items"><?= $obj->get_items_count();?></span> <?php echo __('items', 'propeller-ecommerce'); ?>)</div>
            <hr>
        </div>
    </div>
    <?php foreach ($obj->get_items() as $item) { ?>
        <div class="row align-items-start sc-item">
            <div class="col-3 product-image">                  
                <?php if($item->product->has_images()) { ?>          												 
                    <img class="img-fluid" src="<?= $item->product->mediaImages->items[0]->imageVariants[0]->url; ?>" alt="<?= $item->product->name[0]->value; ?>">
                <?php } else { ?> 
                    <img class="img-fluid"  
                        src="<?php echo $obj->assets_url . '/img/no-image-card.webp';?>"
                        alt="<?php echo __('No image found', 'propeller-ecommerce'); ?>">
                <?php } ?>         												 
            </div>
            <div class="col-9 product-description">            
                <div class="product-sku">
                    <?php echo __('SKU', 'propeller-ecommerce'); ?>: <?= $item->product->sku; ?>
                </div>
                <div class="product-name">
                    <?= $item->product->name[0]->value; ?>
                </div>
            
            </div>
            <div class="col-9 ml-auto d-flex align-items-center justify-content-between">
                <span class="item-quantity"><?php echo __('Quantity', ''); ?>: <?= $item->quantity; ?></span>
                <span class="item-price">
                <span class="symbol">&euro;&nbsp;</span>
                    <?= PropellerHelper::formatPrice($item->price); ?>
                </span>
            </div>
        </div>
    <?php } ?>
    <div class="row align-items-baseline sc-calculation sc-subtotal">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Subtotal', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-subtotal"><?= PropellerHelper::formatPrice($cart->total->subTotal); ?></span>
            </div>
        </div>
    </div>
    <?php if(!empty($cart->total->discountGross)) { ?>
        <div class="row align-items-baseline sc-calculation propel-discount">
            <div class="col-8 col-lg-5"><?php echo __('Discount', 'propeller-ecommerce'); ?></div>
            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="propel-total-voucher"><?= PropellerHelper::formatPrice($cart->total->discountGross); ?></span>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row align-items-baseline sc-calculation">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Shipping costs', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-shipping"><?= PropellerHelper::formatPrice($cart->postageData->postage); ?></span>
            </div>
        </div>
    </div>
    <div class="row align-items-baseline sc-calculation">
        <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total excl. VAT', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total">
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-excl-btw"><?= PropellerHelper::formatPrice($cart->total->totalGross); ?></span>
            </div>
        </div>
    </div>
    <div class="row align-items-baseline sc-calculation">
        <?php 
            $taxPercentage = '';
            if(!empty($cart->taxLevels)) { 
                foreach($cart->taxLevels as $taxLevel) {
                    if ($taxLevel->taxCode ==='H') {
                        $taxPercentage = '21';
                    } else {
                        $taxPercentage = '9';
                    }
                }
            }
           
        ?>
        <div class="col-8 col-lg-6 col-xl-5"><?= $taxPercentage; ?>% <?php echo __('VAT', 'propeller-ecommerce'); ?></div>
        <div class="col-4 col-lg-4 ml-auto sc-price text-right">
            <div class="sc-total-btw">
                <?php 
                    $totalNet = $cart->total->totalNet;
                    $totalGross = $cart->total->totalGross;
                    $totalBTW = $totalNet-$totalGross;
                ?>
                <span class="symbol">&euro;&nbsp;</span><span class="propel-total-btw"><?= PropellerHelper::formatPrice($totalBTW); ?></span>
            </div>
        </div>
    </div>
    <div class="sc-grand-total">
        <div class="row align-items-baseline">
            <div class="col-8 col-lg-6 col-xl-5"><?php echo __('Total', 'propeller-ecommerce'); ?></div>
            <div class="col-4 col-lg-4 ml-auto sc-price text-right">
                <div class="sc-total">
                    <span class="symbol">&euro;&nbsp;</span><span class="propel-total-price"><?= PropellerHelper::formatPrice($cart->total->totalNet); ?></span>
                </div>
            </div> 
        </div>
        
    </div>
</div>