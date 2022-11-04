<div class="row propeller-desc-media">
    <div class="col-12">
        <ul class="nav nav-tabs" id="product-sticky-links">
            <?php if(!empty($product->description[0]->value)) { ?>   
                <li class="nav-item">
                    <a href="#pane-desc" class="nav-link active"><?php echo __('Description', 'propeller-ecommerce'); ?></a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a href="#pane-spec" class="nav-link <?php if(empty($product->description[0]->value)) { ?>active<?php } ?>"><?php echo __('Specifications', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#pane-downloads" class="nav-link"><?php echo __('Downloads', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#pane-video" class="nav-link"><?php echo __('Videos', 'propeller-ecommerce'); ?></a>
            </li>
        </ul>

        <?= apply_filters('propel_product_description', $product); ?>

        <?= apply_filters('propel_product_specifications', $product); ?>

        <?= apply_filters('propel_product_downloads', $product); ?>

        <?= apply_filters('propel_product_videos', $product); ?>
        
    </div>
</div>