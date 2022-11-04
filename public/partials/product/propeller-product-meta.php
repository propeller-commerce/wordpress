<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>

<div class="product-meta d-none d-md-flex">
    <span class="product-category"><a href="<?= $obj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $product->category->slug[0]->value); ?>"><?= $product->category->name[0]->value; ?></a></span>
    <span class="product-code"><?php echo __('SKU', 'propeller-ecommerce'); ?>: <?= $product->sku; ?></span>
</div>