<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<div class="product-meta d-none d-md-flex">
    <?php if ($cluster_product->category) { ?>
    <span class="product-category"><a href="<?php echo esc_url($obj->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $cluster_product->category->slug[0]->value, $cluster_product->category->urlId)); ?>"><?php echo esc_html($cluster_product->category->name[0]->value); ?></a></span>
    <?php } ?>
    <span class="product-code"><?php echo __('SKU', 'propeller-ecommerce'); ?>: <?php echo esc_html($cluster->sku); ?></span>
</div>