<?php

use Propeller\Includes\Controller\PageController;
use Propeller\PropellerHelper;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\UserController;
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
                
                <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>">
                    <?php if($product->has_images()) {?>
                                <img class="img-fluid" loading="lazy" 
                                    src="<?php echo esc_url($product->images[0]->images[0]->url); ?>"
                                    alt="<?php echo (count($product->images[0]->alt) ? $product->images[0]->alt[0]->value : ""); ?>" 
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
            <?php if (!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL) { ?> 
                <div class="add-to-cart-stock-wrapper">
                    <!-- Include the order button template -->	
                    <div class="add-to-basket-wrapper">  
                        <div class="add-to-basket"> 
                            <a class="btn btn-addtobasket d-flex align-items-center justify-content-center" href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>">
                                <svg class="d-flex d-md-none icon icon-cart" aria-hidden="true">
                                    <use xlink:href="#shape-shopping-cart"></use>
                                </svg>    
                                <span class="d-none d-md-flex text"><?php _e( 'See', 'propeller-ecommerce' ); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            
            <?php } else { ?>
                <!-- Include the price display template -->
                <div class="product-price">
                    <?php if(!empty($product->storePrice)) { ?>
                        <?php if ($user_prices == false) { ?>
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($product->price->gross); ?><small><?php echo __('excl VAT', 'propeller-ecommerce'); ?></small></span></div>
                        <?php } else { ?> 
                            <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($product->price->net); ?><small><?php echo __('incl VAT', 'propeller-ecommerce'); ?></small></span></div>    
                        <?php } ?>
                        
                        <div class="product-old-price d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($product->storePrice); ?></span></div>
                    <?php } else if ($user_prices == false) { ?>
                        <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($product->price->gross); ?><small><?php echo __('excl VAT', 'propeller-ecommerce'); ?></small></span></div>
                    <?php } else { ?>
                        <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span><span class="price"><?php echo PropellerHelper::formatPrice($product->price->net); ?><small><?php echo __('incl VAT', 'propeller-ecommerce'); ?></small></span></div>
                    <?php } ?>	
                </div>
                <div class="add-to-cart-stock-wrapper">
                    <!-- Include the order button template -->	
                    <div class="add-to-basket-wrapper">  
                        <?php /*if( $product->isOrderable === 'Y') { */?>
                            <div class="add-to-basket"> 
                                <form class="add-to-basket-form d-flex" name="add-product" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->productId); ?>">
                                    <input type="hidden" name="action" value="cart_add_item">
                                
                                    <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>" class="btn btn-addtobasket d-flex align-items-center justify-content-center">
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

                    <div class="product-code"><?php echo esc_html($product->sku); ?></div>
                </div>
            <?php } ?>    
        </div>

        
    </div>
</div>