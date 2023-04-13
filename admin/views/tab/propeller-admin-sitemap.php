    <div class="form-row p-3 <?php echo esc_attr($sitemap_valid ? 'text-success' : ''); ?>">
        <h5>
            <?php 
                if (!count($sitemap_files))
                    echo esc_html(__('There are no sitemap files.', 'propeller-ecommerce'));
                else
                    echo esc_html($sitemap_valid ? __('Sitemap is valid.', 'propeller-ecommerce') : __('Your Sitemap was generated more than 24 hours ago. Please regenerate if you have made any changes to your content or products recently.', 'propeller-ecommerce')); 
            ?>
        </h5>
    </div>
    <div class="form-row text-left text-warning p-3">
        <small>(<?php echo esc_html(__('NOTE: Generating your sitemap usually takes place after midnight on a daily basis by running a scheduled task when your webshop is not under heavy usage', 'propeller-ecommerce')); ?>)</small>
    </div>

    <div class="form-row p-3 mt-3">
        <div class="col-md-6">
            <?php if (count($sitemap_files)) { ?>
                <ul>
                    <?php if ($sitemap->has_index()) { ?>
                        <?php $index = $sitemap->get_index(); ?>
                        <li>- 
                            <a href="<?php echo esc_url($index->url); ?>" target="_blank">
                                <?php echo esc_html(basename($index->url)); ?>
                            </a>
                            <span>(<?php echo esc_html($index->lastmod); ?>)</span>
                        </li>
                    <?php } ?>
                <?php foreach ($sitemap_files as $file) { ?>
                    <li>- 
                        <a href="<?php echo esc_url($file->url); ?>" target="_blank">
                            <?php echo esc_html(basename($file->url)); ?>
                        </a>
                        <span>(<?php echo esc_html($file->lastmod); ?>)</span>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <div class="col-md-6 align-middle">
            <?php 
                if (count($sitemap_files)) {
                    if ($sitemap->yoast_active()) { 
            ?>
                <strong><?php echo esc_html(__('Yoast plugin is active! You can check your sitemap here: ', 'propeller-ecommerce')); ?></strong>
                <a href="<?php echo esc_url(home_url('/sitemap_index.xml')); ?>" target="_blank">
                    <?php echo esc_html('sitemap_index.xml'); ?>
                </a>
                <div class="text-left text-warning">
                    <small>(<?php echo esc_html(__('NOTE: When Yoast plugin is active, Propeller\'s sitemap.xml file is not used', 'propeller-ecommerce')); ?>)</small>
                </div>
            <?php } else { ?>
                <strong><?php echo esc_html(__('You are not using Yoast! You can check your sitemap here: ', 'propeller-ecommerce')); ?></strong>
                <a href="<?php echo esc_url(home_url('/sitemap.xml')); ?>" target="_blank">
                    <?php echo esc_html('sitemap.xml'); ?>
                </a>
            <?php } } ?>
        </div>
    </div>

    <div class="row">
        <div class="col text-right">
            <button type="button" id="generate_sitemap" class="sitemap-form-btn btn btn-success">Generate sitemap</button>
            <div class="d-block text-right text-warning">
            <small>(<?php echo esc_html(__('NOTE: Generating sitemap might take a while until your catalog is processed', 'propeller-ecommerce')); ?>)</small>
            </div>
        </div>
    </div>