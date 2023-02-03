<?php
    $expanded = true;
?>

<div class="filter">
    <button class="btn-filter" type="button" href="#filterForm_prices" data-toggle="collapse" aria-expanded="<?php echo esc_attr($expanded ? 'true': 'false'); ?>" aria-controls="filterForm_prices">
        <span><?php echo __('Price', 'propeller-ecommerce'); ?></span>
    </button>  
    <div class="numeric-filter collapse <?php echo (bool) $expanded ? 'show': ''; ?>" id="filterForm_prices">
        <form method="get" class="filterForm filterFormNumeric collapse <?php echo (bool) $expanded ? 'show': ''; ?>">
            <input type="hidden" name="prop_value" value="<?php echo esc_attr($this->slug); ?>" />
            <input type="hidden" name="prop_name" value="<?php echo esc_attr($this->prop); ?>" />
            <input type="hidden" name="action" value="<?php echo esc_attr($this->action); ?>" />
            
            <div class="slider-container">
                <div id="price_slider" class="slider" data-prop_value="<?php echo esc_attr($this->slug); ?>" data-prop_name="<?php echo esc_attr($this->prop); ?>" data-action="<?php echo esc_attr($this->action); ?>" data-min="<?php echo esc_attr($filter->min); ?>" data-max="<?php echo esc_attr($filter->max); ?>"></div>
            </div>
            <div class="input-group min">
                <div class="input-group-prepend"><span class="input-group-text"><?php echo _e('Min.', 'propeller-ecommerce'); ?></span></div>
                <input type="number" name="price_from" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" value="<?php echo esc_attr($filter->min); ?>" class="form-control form-control-sm numeric-min" data-min="<?php echo esc_attr($filter->min); ?>" min="<?php echo esc_attr($filter->min); ?>" max="<?php echo esc_attr($filter->max); ?>">
            </div>
            <div class="price-tot"><span><?php _e('from', 'propeller-ecommerce'); ?></span></div>
            <div class="input-group max">
                <div class="input-group-prepend"><span class="input-group-text"><?php echo _e('Max.', 'propeller-ecommerce'); ?></span></div>
                <input type="number" name="price_to" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" value="<?php echo esc_attr($filter->max); ?>" class="form-control form-control-sm numeric-max" data-max="<?php echo esc_attr($filter->max); ?>" min="<?php echo esc_attr($filter->min); ?>" max="<?php echo esc_attr($filter->max); ?>">
            </div>
        </form>
    </div>
   
</div>