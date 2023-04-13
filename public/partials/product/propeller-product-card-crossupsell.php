<?php

use Propeller\Includes\Controller\PageController;
use Propeller\PropellerHelper;
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\Includes\Object\Product;

$user_prices = SessionController::get(PROPELLER_SPECIFIC_PRICES);

$crossupsell->item = new Product($crossupsell->item);

?>

<div class="card propeller-product-card">   
    <svg style="display: none;">
        <symbol viewBox="0 0 23 20" id="shape-shopping-cart"><title>Shopping cart</title> <path d="M18.532 20c.72 0 1.325-.24 1.818-.723a2.39 2.39 0 0 0 .739-1.777c0-.703-.253-1.302-.76-1.797a.899.899 0 0 0-.339-.508 1.002 1.002 0 0 0-.619-.195H7.55l-.48-2.5h13.26a.887.887 0 0 0 .58-.215.995.995 0 0 0 .34-.527l1.717-8.125a.805.805 0 0 0-.18-.781.933.933 0 0 0-.739-.352H5.152L4.832.781a.99.99 0 0 0-.338-.566.947.947 0 0 0-.62-.215H.48a.468.468 0 0 0-.34.137.45.45 0 0 0-.14.332V.78c0 .13.047.241.14.332a.468.468 0 0 0 .34.137h3.155L6.43 15.82c-.452.47-.679 1.042-.679 1.72 0 .676.247 1.256.74 1.737.492.482 1.098.723 1.817.723.719 0 1.324-.24 1.817-.723.493-.481.739-1.074.739-1.777 0-.443-.12-.86-.36-1.25h5.832c-.24.39-.36.807-.36 1.25 0 .703.246 1.296.74 1.777.492.482 1.097.723 1.816.723zm1.518-8.75H6.83l-1.438-7.5h16.256l-1.598 7.5zm-11.742 7.5c-.347 0-.646-.124-.899-.371s-.38-.54-.38-.879c0-.339.127-.632.38-.879s.552-.371.899-.371c.346 0 .645.124.898.371s.38.54.38.879c0 .339-.127.632-.38.879s-.552.371-.898.371zm10.224 0c-.346 0-.645-.124-.898-.371s-.38-.54-.38-.879c0-.339.127-.632.38-.879s.552-.371.898-.371c.347 0 .646.124.899.371s.38.54.38.879c0 .339-.127.632-.38.879s-.552.371-.899.371z" fill-rule="nonzero"/></symbol>  
    </svg>
    <figure class="card-img-top">
        <div class="product-labels">
            <?php if ($crossupsell->item->has_attributes()) {
                foreach ($crossupsell->item->get_attributes() as $attribute) {
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
            <a href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $crossupsell->item->slug[0]->value, $crossupsell->item->urlId)); ?>">
                <?php if($crossupsell->item->has_images()) {?>
                    <img class="img-fluid" loading="lazy" 
                        src="<?php echo esc_url($crossupsell->item->images[0]->images[0]->url); ?>"
                        alt="<?php echo count($crossupsell->item->images[0]->alt) ? $crossupsell->item->images[0]->alt[0]->value : ""; ?>" 
                        width="<?php echo PROPELLER_PRODUCT_IMG_CATALOG_WIDTH; ?>" height="<?php echo PROPELLER_PRODUCT_IMG_CATALOG_HEIGHT; ?>">
                <?php }
                    else { ?>
                    <img class="img-fluid" loading="lazy" 
                        src="<?php echo esc_url($obj->assets_url . '/img/no-image-card.webp'); ?>"
                        alt="<?php echo __('No image found', 'propeller-ecommerce'); ?>"
                        width="300" height="300" >
                <?php } ?>
            </a>   
        </div>
    </figure>
    <div class="card-body product-card-description">
        <div class="product-code"><?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo esc_html($crossupsell->item->sku); ?></div>
        <div class="product-name">

            <!-- build the product urls with the classId of the product (temporary) -->
            <a href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $crossupsell->item->slug[0]->value, $crossupsell->item->urlId)); ?>">
                <?php echo esc_html($crossupsell->item->name[0]->value); ?>
            </a>
        </div>
    </div>
    <div class="card-footer product-card-footer">
        <?php if (!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL) { ?> 
            <div class="add-to-cart-stock-wrapper">
                <!-- Include the order button template -->	
                <div class="add-to-basket-wrapper">  
                    <div class="add-to-basket"> 
                        <a class="btn btn-addtobasket d-flex align-items-center justify-content-center" href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $crossupsell->item->slug[0]->value, $crossupsell->item->urlId)); ?>">
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
                <?php if(!empty($crossupsell->item->suggestedPrice)) { ?>
                    <?php if ($user_prices == false) { ?>
                        <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span> <span class="price"><?php echo PropellerHelper::formatPrice($crossupsell->item->price->gross); ?> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span></div>
                    <?php } else { ?> 
                        <div class="product-current-price has-discount d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span> <span class="price"><?php echo PropellerHelper::formatPrice($crossupsell->item->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span></div>    
                    <?php } ?>
                    
                    <div class="product-old-price d-md-inline-flex"><span class="symbol">&euro;&nbsp;</span> <span class="price"><?php echo PropellerHelper::formatPrice($crossupsell->item->storePrice); ?></span></div>
                <?php } else if ($user_prices == false) { ?>
                    <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span> <span class="price"><?php echo PropellerHelper::formatPrice($crossupsell->item->price->gross); ?> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span></div>
                <?php } else { ?>
                    <div class="product-current-price"><span class="symbol">&euro;&nbsp;</span> <span class="price"><?php echo PropellerHelper::formatPrice($crossupsell->item->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span></div>
                <?php } ?>	
            </div>
            <?php if ($user_prices == false) { ?>
                <small class="product-customer-price"> 
                    <span class="product-price-tax"><span class="symbol">&euro;&nbsp;</span> <?php echo PropellerHelper::formatPrice($crossupsell->item->price->net); ?> <?php echo __('incl. VAT', 'propeller-ecommerce'); ?></span>
                </small>
            <?php } else { ?>
                <small class="product-customer-price"> 
                    <span class="product-price-tax"><span class="symbol">&euro;&nbsp;</span> <?php echo PropellerHelper::formatPrice($crossupsell->item->price->gross); ?> <?php echo __('excl. VAT', 'propeller-ecommerce'); ?></span>
                </small>
            <?php } ?>

            <div class="add-to-cart-stock-wrapper">
                <!-- Include the order button template -->	
                <div class="add-to-basket-wrapper">  
                    <?php /*if( $crossupsell->item->isOrderable === 'Y') { */?>
                        <div class="add-to-basket"> 
                            <form class="add-to-basket-form d-flex" name="add-product" method="post">
                                <input type="hidden" name="product_id" value="<?php echo esc_attr($crossupsell->item->productId); ?>">
                                <input type="hidden" name="action" value="cart_add_item">
                                    <div class="input-group product-quantity">
                                        <label class="sr-only" for="quantity-item-<?php echo esc_html($crossupsell->item->productId); ?>"><?php echo __('Quantity', 'propeller-ecommerce'); ?></label>
                                        <span class="input-group-prepend incr-decr">
                                            <button type="button" class="btn-quantity" 
                                            data-type="minus">-</button>
                                        </span>
                                        <input
                                            type="number"
                                            ondrop="return false;" 
                                            onpaste="return false;"
                                            onkeypress="return event.charCode>=48 && event.charCode<=57"
                                            id="quantity-item-<?php echo esc_html($crossupsell->item->productId); ?>"
                                            class="quantity large form-control input-number"
                                            name="quantity"
                                            value="<?php echo esc_attr($crossupsell->item->minimumQuantity); ?>"
                                            autocomplete="off"
                                            min="<?php echo esc_attr($crossupsell->item->minimumQuantity); ?>"
                                            data-min="<?php echo esc_attr($crossupsell->item->minimumQuantity); ?>"
                                            data-unit="<?php echo esc_attr($crossupsell->item->unit); ?>"
                                            <?php if($stock_show) { ?> 
                                                data-stock="<?php echo esc_attr($crossupsell->item->inventory->totalQuantity); ?>"
                                            <?php } ?>
                                            >  
                                        <span class="input-group-append incr-decr">
                                            <button type="button" class="btn-quantity" data-type="plus">+</button>
                                        </span>
                                    </div>
                                <button class="btn btn-addtobasket d-flex align-items-center justify-content-center" type="submit">
                                    <svg class="d-flex d-md-none icon icon-cart" aria-hidden="true">
                                        <use xlink:href="#shape-shopping-cart"></use>
                                    </svg>    
                                    <span class="d-none d-md-flex text"><?php echo __('In cart', 'propeller-ecommerce'); ?></span>
                                </button>
                            </form>
                        </div>
                    <?php /* } else { */ ?>
                        <!--<div class="alert alert-dark alert-not-available"><?php echo __('Product is no longer available', 'propeller-ecommerce'); ?></div> --->
                    <?php /* } */ ?>
                </div>
            
                <!-- Stock status -->
                <?php if(!empty($crossupsell->item->inventory) AND $crossupsell->item->inventory->totalQuantity > 0) { ?>
                    <div class="product-status in-stock"><?php echo __('Available', 'propeller-ecommerce'); ?><span class="quantity-stock">: <?php echo esc_html($crossupsell->item->inventory->totalQuantity); ?></span></div>
                <?php } else { ?>
                    <div class="product-status out-of-stock"><?php echo __('Out of stock', 'propeller-ecommerce'); ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>