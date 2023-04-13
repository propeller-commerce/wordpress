<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;
 
    echo '<script type="application/ld+json">
        {
            "@context": "https://schema.org/",
            "@type": "Product",
            "name": " '. $cluster->name[0]->value .' ",
            "url":"' . $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $cluster_product->slug[0]->value, $cluster_product->urlId) . '",
            ' . ($cluster_product->has_images() ? '"image":" ' . $cluster_product->images[0]->images[0]->url . '",':"") . '
            "description":"'. strip_tags($cluster->description[0]->value) .'",
            "mpn":"'. $cluster_product->manufacturerCode .'",
            "sku":"'. $cluster->sku . '",
            "productId":"'. $cluster_product->productId. '",';
    if ($cluster_product->category) 
        echo '"category":"'. $cluster_product->category->name[0]->value .'",';
    echo    '"brand": {
                "@type": "Brand",
                "name":"'.$cluster_product->manufacturer.'"
            } '; 
    if (!(!UserController::is_logged_in() && PROPELLER_WP_SEMICLOSED_PORTAL))  {
        echo ' , "offers": {
            "@type": "Offer",
            "url": "' . $obj->buildUrl(PageController::get_slug(PageType::PRODUCT_PAGE), $cluster_product->slug[0]->value, $cluster_product->urlId) . '",
            "priceCurrency": "EUR",
            "price": "'.PropellerHelper::formatPriceGTM($cluster_product->price->net).'",
            "itemCondition": "http://schema.org/NewCondition",
            "availability": "http://schema.org/'.(!(empty($cluster_product->inventory) && $cluster_product->inventory->totalQuantity > 0)? 'InStock':'OutOfStock').'"
        } ';
    }
    echo ' } 
        </script>'; 