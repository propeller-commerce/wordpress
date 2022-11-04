<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>

<div class="product-meta d-flex d-md-none">
    <span class="product-category"><a href="<?= $obj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $product->category->slug[0]->value); ?>"><?= $product->category->name[0]->value; ?></a></span>
    <span class="product-code"><?php echo __('SKU', 'propeller-ecommerce'); ?>: <?= $product->sku; ?></span>
    <input type="hidden" id="productId" value="<?= $product->productId ?>">
</div>