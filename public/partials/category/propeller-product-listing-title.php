<div class="row">
    <div class="col-12">
        <h1 class="title <?php echo apply_filters('propel_listing_title_classes', ''); ?>"><?php echo esc_html($data->name[0]->value); ?></h1>
    </div>
</div>
<?php if (!empty($data->shortDescription[0]->value)) { ?>    
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="category-description">
                <?php
                echo wp_kses($data->shortDescription[0]->value, wp_kses_allowed_html('post'))
                ?>
            </div>
        </div>
    </div>
<?php } ?>