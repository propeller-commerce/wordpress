<?php

use Propeller\Includes\Controller\PageController;
use Propeller\PropellerHelper;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Enum\PageType;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

?>

<div>
    <div class="card propeller-product-card propeller-product-card-small">
        <figure class="card-img-top">
            <div class="product-labels">
                <?php if ($product->has_attributes()){
                    foreach ($product->get_attributes() as $attribute) {
                        if($attribute->searchId == 'attr_product_label_1' && !empty($attribute->get_value())) { ?>
                            <div class="product-label label-1 order-1">
                                <span><?= $attribute->get_value(); ?></span>
                            </div>
                        <?php }
                        if($attribute->searchId == 'attr_product_label_2' && !empty($attribute->get_value())) { ?>
                            <div class="product-label label-2  order-2">
                                <span><?= $attribute->get_value(); ?></span>
                            </div>
                        <?php }
                    }
                }
                ?>		            
            </div>
            <div class="product-card-image">					
                <!-- build the product urls with the classId of the product (temporary) -->
                
                <a href="<?php echo $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value); ?>">
                    <?php if($product->has_images()) {?>
                                <img class="img-fluid" loading="lazy" 
                                    src="<?= $product->images[0]->images[0]->url;?>"
                                    alt="<?= (count($product->images[0]->alt) ? $product->images[0]->alt[0]->value : ""); ?>" 
                                    width="<?= PROPELLER_PRODUCT_IMG_CATALOG_WIDTH; ?>" 
                                    height="<?= PROPELLER_PRODUCT_IMG_CATALOG_HEIGHT; ?>">
                    <?php }
                        else { ?>
                        <img class="img-fluid no-image-card" loading="lazy" 
                            src="<?php echo $this->assets_dir . '/img/no-image-card.webp';?>"
                            alt="<?php echo __('No image found', 'propeller-ecommerce'); ?>"
                            width="300" height="300">
                    <?php } ?>
                </a>   
            </div>
        </figure>
        <div class="card-body product-card-description">
            <div class="product-name">
                <!-- build the product urls with the classId of the product (temporary) -->
                <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value); ?>">
                    <?= $product->name[0]->value; ?>
                </a>
            </div>
        </div>
        <div class="card-footer product-card-footer">
            <!-- Include the price display template -->
            <div class="product-price">
                <?php if(!empty($product->storePrice)) { ?>
                    <?php if ($user_prices == false) { ?>
                        <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->price->gross); ?><small><?php echo __('excl VAT', 'propeller-ecommerce'); ?></small></span></div>
                    <?php } else { ?> 
                        <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->price->net); ?><small><?php echo __('incl VAT', 'propeller-ecommerce'); ?></small></span></div>    
                    <?php } ?>
                    
                    <div class="product-old-price d-md-inline-flex"><span class="price"><?= PropellerHelper::formatPrice($product->suggestedPrice); ?></span></div>
                <?php } else if ($user_prices == false) { ?>
                    <div class="product-current-price"><span class="price"><?= PropellerHelper::formatPrice($product->price->gross); ?></span></div>
                <?php } else { ?>
                    <div class="product-current-price"><span class="price"><?= PropellerHelper::formatPrice($product->price->net); ?></span></div>
                <?php } ?>	
            </div>
            
            <!-- Include the order button template -->	
        <div class="add-to-basket-wrapper">  
            <?php /*if( $product->isOrderable === 'Y') { */?>
                <div class="add-to-basket"> 
                    <form class="add-to-basket-form d-flex" name="add-product" method="post">
                        <input type="hidden" name="product_id" value="<?= $product->productId; ?>">
                        <input type="hidden" name="action" value="cart_add_item">
                    
                        <a href="<?= $this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value); ?>" class="btn btn-addtobasket d-flex align-items-center justify-content-center">
                            <!-- <svg class="d-flex icon icon-cart" aria-hidden="true">
                                <use xlink:href="#shape-shopping-cart"></use>
                            </svg>     -->
                            <span class="d-flex text"><?php echo __('See', 'propeller-ecommerce'); ?></span>
                        </a>
                        
                    </form>
                </div>
            <?php /* } else { */ ?>
                <!--<div class="alert alert-dark alert-not-available"><?php echo __('Product is no longer available', 'propeller-ecommerce'); ?></div> --->
            <?php /* } */ ?>
        </div>

        <div class="product-code"><?= $product->sku; ?></div>     
            
        </div>

        
    </div>
</div>