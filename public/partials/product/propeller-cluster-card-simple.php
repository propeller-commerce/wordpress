<?php

use Propeller\Includes\Controller\PageController;
use Propeller\PropellerHelper;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

$cluster_product = $product->defaultProduct ? $product->defaultProduct : $product->products[0];

?>

<div>
    <div class="card propeller-product-card propeller-product-card-small propeller-cluster-card propeller-cluster-card-small">
        <figure class="card-img-top">
            <div class="product-labels">
                <?php if ($cluster_product->has_attributes()){
                    foreach ($cluster_product->get_attributes() as $attribute) {
                        if($attribute->searchId == 'attr_product_label_1' && !empty($attribute->get_value())) { ?>
                            <div class="product-label label-1 order-1">
                                <span><?php echo esc_html($attribute->get_value()); ?></span>
                            </div>
                        <?php }
                        if($attribute->searchId == 'attr_product_label_2' && !empty($attribute->get_value())) { ?>
                            <div class="product-label label-2  order-2">
                                <span><?php echo esc_html($attribute->get_value()); ?></span>
                            </div>
                        <?php }
                    }
                }
                ?>		            
            </div>
            <div class="product-card-image">					
                <!-- build the product urls with the classId of the product (temporary) -->
                
                <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $cluster_product->slug[0]->value, $cluster_product->urlId)); ?>">
                    <?php if($cluster_product->has_images()) {?>
                                <img class="img-fluid" loading="lazy" 
                                    src="<?php echo esc_url($cluster_product->images[0]->images[0]->url); ?>"
                                    alt="<?php echo (count($cluster_product->images[0]->alt) ? $cluster_product->images[0]->alt[0]->value : ""); ?>" 
                                    width="<?php echo PROPELLER_PRODUCT_IMG_CATALOG_WIDTH; ?>" 
                                    height="<?php echo PROPELLER_PRODUCT_IMG_CATALOG_HEIGHT; ?>">
                    <?php }
                        else { ?>
                        <img class="img-fluid no-image-card" loading="lazy" 
                            src="<?php echo esc_url($this->assets_url . '/img/no-image-card.webp'); ?>"
                            alt="<?php echo __('No image found', 'propeller-ecommerce'); ?>"
                            width="300" height="300">
                    <?php } ?>
                </a>   
            </div>
        </figure>
        <div class="card-body product-card-description">
            <div class="product-name">
                <!-- build the product urls with the classId of the product (temporary) -->
                <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>">
                    <?php echo esc_html($product->name[0]->value); ?>
                </a>
            </div>
        </div>
        <div class="card-footer product-card-footer">
            <?php if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL)) { ?> 
                <!-- Include the price display template -->
                <div class="product-price">
                    <?php if(!empty($cluster_product->storePrice)) { ?>
                        <?php if ($user_prices == false) { ?>
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->gross); ?><small><?php echo __('excl VAT', 'propeller-ecommerce'); ?></small></span></div>
                        <?php } else { ?> 
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->net); ?><small><?php echo __('incl VAT', 'propeller-ecommerce'); ?></small></span></div>    
                        <?php } ?>
                        
                        <div class="product-old-price d-md-inline-flex"><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->suggestedPrice); ?></span></div>
                    <?php } else if ($user_prices == false) { ?>
                        <div class="product-current-price"><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->gross); ?></span></div>
                    <?php } else { ?>
                        <div class="product-current-price"><span class="price"><?php echo PropellerHelper::formatPrice($cluster_product->price->net); ?></span></div>
                    <?php } ?>	
                </div>
            <?php } ?>
            <!-- Include the order button template -->	
            <div class="add-to-basket-wrapper">  
                <?php /*if( $cluster_product->isOrderable === 'Y') { */?>
                    <div class="add-to-basket">  
                        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>" class="btn btn-addtobasket d-flex align-items-center justify-content-center">
                            <!-- <svg class="d-flex icon icon-cart" aria-hidden="true">
                                <use xlink:href="#shape-shopping-cart"></use>
                            </svg>     -->
                            <span class="d-flex text"><?php echo __('See', 'propeller-ecommerce'); ?></span>
                        </a>
                    </div>
                <?php /* } else { */ ?>
                    <!--<div class="alert alert-dark alert-not-available"><?php echo __('Product is no longer available', 'propeller-ecommerce'); ?></div> --->
                <?php /* } */ ?>
            </div>

        <div class="product-code"><?php echo esc_html($cluster_product->sku); ?></div>
            
        </div>

        
    </div>
</div>