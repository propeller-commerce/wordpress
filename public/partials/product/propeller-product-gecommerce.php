<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;
 
echo '<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": " '. $product->name[0]->value .' ",
        "url":"' . $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId) . '",
        ' . ($product->has_images() ? '"image":" ' . $product->images[0]->images[0]->url . '",':"") . '
        "description":"'. strip_tags($product->description[0]->value) .'",
        "mpn":"'. $product->manufacturerCode .'",
        "sku":"'. $product->sku . '",
        "productId":"'. $product->productId. '",
        "category":"'. $product->category->name[0]->value .'",
        "brand": {
            "@type": "Brand",
            "name":"'.$product->manufacturer.'"
        } ';
if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL)) {
    echo ' , "offers": {
        "@type": "Offer",
        "url": "' . $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $product->slug[0]->value, $product->urlId) . '",
        "priceCurrency": "EUR",
        "price": "'.PropellerHelper::formatPriceGTM($product->price->net).'",
        "itemCondition": "http://schema.org/NewCondition",
        "availability": "http://schema.org/'.((!empty($product->inventory) && $product->inventory->totalQuantity > 0)? 'InStock':'OutOfStock').'"
        
    } ';

} 

echo ' } 
    </script>';