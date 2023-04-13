<?php if ($obj->parts->itemsFound > 0) { ?>
    <div class="row align-items-center catalog-result-options">
        <div class="col-12 mb-4 d-md-none">
            <button class="d-flex d-md-none btn-filter-menu" type="button" data-toggle="off-canvas-filters" 
            data-target="#filter_container" aria-controls="filter_container" 
            aria-expanded="false" aria-label="Toon menu">
                <span><?php echo __('Filters', 'propeller-ecommerce'); ?></span>
                <svg class="icon icon-svg" aria-hidden="true">
                    <use xlink:href="#shape-filters"></use>
                </svg>
            </button>
        </div>  
        <!-- Active filters -->
        <div class="col-12 mb-4 mt-4">
            <?php
                $selected_filters = $obj->get_selected_filters($obj->filters->getFilters());
                
                foreach ($selected_filters as $selected_filter) { 
            ?>
                    <a class="btn-active-filter"
                    data-filter="<?php echo esc_attr($selected_filter->filter->searchId); ?>"
                    data-value="<?php echo esc_attr($selected_filter->value); ?>"
                    data-type="<?php echo esc_attr($selected_filter->filter->type); ?>">
                        <span class="active-filter-name"><?php echo esc_attr($selected_filter->value); ?></span>
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-close"></use>
                        </svg>
                    </a>
            <?php } ?>
        </div>
        <div class="col-auto mr-auto catalog-result">
            <div class="catalog-result-count">
                <?php if (count($obj->machines)) { ?>
                    <span id="catalog_total"><?php echo esc_html(count($obj->machines)); ?></span> 
                    <?php echo __('machines', 'propeller-ecommerce'); ?>
                <?php } ?>
                
                <?php if ($obj->parts->itemsFound > 0) { ?>
                    <?php if (count($obj->machines)) echo ', '; ?>

                    <span id="catalog_total"><?php echo esc_html($obj->parts->itemsFound); ?></span> 
                    <?php echo __('parts', 'propeller-ecommerce'); ?>
                <?php } ?>
            </div>
        </div>
        
        <?php require $obj->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-offset-sort.php'); ?>
        
        <div class="col-auto d-none d-md-flex align-items-center liststyle-options">
            <div class="input-group">
                <a class="input-group-prepend btn-liststyle" href="" data-liststyle="list" aria-label="<?php echo __('Show as list', 'propeller-ecommerce'); ?>" rel="nofollow">
                    <span class="icon"> 
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-list"></use>
                        </svg>
                    </span>
                </a>
                <a class="input-group-append btn-liststyle active" href="" data-liststyle="blocks" aria-label="<?php echo __('Show as blocks', 'propeller-ecommerce'); ?>" rel="nofollow">
                    <span class="icon">
                        <svg class="icon icon-svg" aria-hidden="true">
                            <use xlink:href="#shape-blocks"></use>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
<?php } ?>