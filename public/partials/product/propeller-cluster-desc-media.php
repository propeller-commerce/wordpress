<div class="row propeller-desc-media">
    <div class="col-12">
        <ul class="nav nav-tabs" id="product-sticky-links">
            <?php if(!empty($cluster->description[0]->value)) { ?>   
                <li class="nav-item">
                    <a href="#pane-desc" class="nav-link active"><?php echo __('Description', 'propeller-ecommerce'); ?></a>
                </li>
            <?php } ?>
            <li class="nav-item">
                <a href="#pane-spec" class="nav-link <?php if(empty($cluster->description[0]->value)) { ?>active<?php } ?>"><?php echo __('Specifications', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#pane-downloads" class="nav-link"><?php echo __('Downloads', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item">
                <a href="#pane-video" class="nav-link"><?php echo __('Videos', 'propeller-ecommerce'); ?></a>
            </li>
        </ul>

        <?= apply_filters('propel_cluster_description', $cluster); ?>

        <?= apply_filters('propel_cluster_specifications', $cluster_product); ?>

        <?= apply_filters('propel_cluster_downloads', $cluster_product); ?>

        <?= apply_filters('propel_cluster_videos', $cluster_product); ?>

    </div>
</div>