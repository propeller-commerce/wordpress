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
                    "position": "<?php echo $key+1; ?>",
                    "item": {
                        "@type": "Product",
                        "name": " <?php echo $product->name[0]->value ?> ",
                        "url":" <?php echo $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value) ?>",
                        <?php if($product->has_images()) echo'"image":" ' . $product->images[0]->images[0]->url .'",'?>
                        "description":" <?php echo isset($product->description[0]->value) ? strip_tags($product->description[0]->value) : '' ?>",
                        "mpn":"<?php echo $product->manufacturerCode ?>",
                        "sku":"<?php echo $product->sku ?>'",
                        "productId":"<?php echo $product->productId ?>",
                        "category":"<?php echo isset($product->category->name[0]->value) ? $product->category->name[0]->value : ''; ?>",
                        "brand": {
                            "@type": "Brand",
                            "name":"<?php echo $product->manufacturer ?>"
                        }
                    <?php if ( UserController::is_logged_in() ) { ?>   ,
                        "offers": {
                            "@type": "Offer",
                            "url": "<?php echo $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value) ?>",
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