<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>

<div class="product-meta d-flex d-md-none">
    <span class="product-category"><a href="<?php echo $obj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $product->category->slug[0]->value); ?>"><?php echo $product->category->name[0]->value; ?></a></span>
    <span class="product-code"><?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo $product->sku; ?></span>
    <input type="hidden" id="productId" value="<?php echo $product->productId ?>">
</div>