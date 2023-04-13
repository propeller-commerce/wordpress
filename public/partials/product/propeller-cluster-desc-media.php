<div class="row propeller-desc-media">
    <div class="col-12">
        <ul class="nav nav-tabs" id="product-sticky-links">
            <?php if(!empty($cluster->description[0]->value)) { ?>   
                <li class="nav-item">
                    <a href="#pane-desc" class="nav-link active"><?php echo __('Description', 'propeller-ecommerce'); ?></a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a href="#pane-specifications" data-loaded="false" data-tab="specifications" data-id="<?php echo esc_attr($cluster_product->productId); ?>" class="nav-link <?php if(empty($cluster->description[0]->value)) { ?>active<?php } ?>"><?php echo __('Specifications', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#pane-downloads" data-loaded="false" data-tab="downloads" data-id="<?php echo esc_attr($cluster_product->productId); ?>" class="nav-link"><?php echo __('Downloads', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
            <a href="#pane-videos" data-loaded="false" data-tab="videos" data-id="<?php echo esc_attr($cluster_product->productId); ?>" class="nav-link"><?php echo __('Videos', 'propeller-ecommerce'); ?></a>
            </li>
        </ul>

        <?php echo apply_filters('propel_cluster_description', $cluster); ?>

        <?php echo apply_filters('propel_product_specifications', $cluster_product); ?>

        <?php echo apply_filters('propel_product_downloads', $cluster_product); ?>

        <?php echo apply_filters('propel_product_videos', $cluster_product); ?>

    </div>
</div>