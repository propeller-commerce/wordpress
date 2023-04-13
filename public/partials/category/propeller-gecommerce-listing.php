<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

    echo '<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "ItemList",
        "numberOfItems": "'. count($products). '",
        "itemListElement": [ '?>
            <?php
                foreach ($products as $key => $product) { 
                    if ($product->class == 'cluster')
                        $product = $product->defaultProduct ? $product->defaultProduct : $product->products[0];
            ?>
                
                {
                    "@type": "ListItem",
                    "position": "<?php echo esc_attr($key+1); ?>",
                    "item": {
                        "@type": "Product",
                        "name": " <?php echo esc_attr($product->name[0]->value); ?> ",
                        "url":" <?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>",
                        <?php if($product->has_images()) echo'"image":" ' .esc_url( $product->images[0]->images[0]->url ) .'",'?>
                        "description":" <?php echo isset($product->description[0]->value) ? strip_tags($product->description[0]->value) : '' ?>",
                        "mpn":"<?php echo esc_attr($product->manufacturerCode); ?>",
                        "sku":"<?php echo esc_attr($product->sku); ?>'",
                        "productId":"<?php echo esc_attr($product->productId); ?>",
                        "category":"<?php echo isset($product->category->name[0]->value) ? esc_attr($product->category->name[0]->value ) : ''; ?>",
                        "brand": {
                            "@type": "Brand",
                            "name":"<?php echo esc_attr($product->manufacturer); ?>"
                        }
                        <?php if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL)) { ?>    ,
                        "offers": {
                            "@type": "Offer",
                            "url": "<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId)); ?>",
                            "priceCurrency": "EUR",
                            "price": "<?php echo PropellerHelper::formatPriceGTM($product->price->net) ?>",
                            "itemCondition": "http://schema.org/NewCondition",
                            "availability": "http://schema.org/<?php if ($product->inventory && $product->inventory->totalQuantity > 0) echo "InStock"; else echo "OutOfStock"; ?>"
                            
                        }
                    <?php } ?>
                }
            }<?php if (count($products) > 1 AND $key + 1 < count($products)) echo ','; ?>
            <?php } ?>
        <?php echo ']
    }
    </script>';
?>