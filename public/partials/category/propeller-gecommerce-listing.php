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
                    "position": "<?= $key+1; ?>",
                    "item": {
                        "@type": "Product",
                        "name": " <?= $product->name[0]->value ?> ",
                        "url":" <?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value) ?>",
                        <?php if($product->has_images()) echo'"image":" ' . $product->images[0]->images[0]->url .'",'?>
                        "description":" <?= strip_tags($product->description[0]->value) ?>",
                        "mpn":"<?= $product->manufacturerCode ?>",
                        "sku":"<?= $product->sku ?>'",
                        "productId":"<?= $product->productId ?>",
                        "category":"<?= $product->category->name[0]->value ?>",
                        "brand": {
                            "@type": "Brand",
                            "name":"<?= $product->manufacturer ?>"
                        }
                    <?php if ( UserController::is_logged_in() ) { ?>   ,
                        "offers": {
                            "@type": "Offer",
                            "url": "<?= $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value) ?>",
                            "priceCurrency": "EUR",
                            "price": "<?= PropellerHelper::formatPriceGTM($product->price->net) ?>",
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