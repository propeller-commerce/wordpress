<?php
    $expanded = true;
?>

<div class="filter">
    <button class="btn-filter" type="button" href="#filterForm_prices" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true': 'false'; ?>" aria-controls="filterForm_prices">
        <span><?php echo __('Price', 'propeller-ecommerce'); ?></span>
    </button>  
    <div class="numeric-filter collapse <?php echo $expanded ? 'show': ''; ?>" id="filterForm_prices">
        <form method="get" class="filterForm filterFormNumeric collapse <?php echo $expanded ? 'show': ''; ?>">
            <input type="hidden" name="prop_value" value="<?= $this->slug; ?>" />
            <input type="hidden" name="prop_name" value="<?= $this->prop; ?>" />
            <input type="hidden" name="action" value="<?= $this->action; ?>" />
            
            <div class="slider-container">
                <div id="price_slider" class="slider" data-prop_value="<?= $this->slug; ?>" data-prop_name="<?= $this->prop; ?>" data-action="<?= $this->action; ?>" data-min="<?= $filter->min; ?>" data-max="<?= $filter->max; ?>"></div>
            </div>
            <div class="input-group min">
                <div class="input-group-prepend"><span class="input-group-text"><?= _e('Min.', 'propeller-ecommerce'); ?></span></div>
                <input type="number" name="price_from" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" value="<?= $filter->min; ?>" class="form-control form-control-sm numeric-min" data-min="<?= $filter->min; ?>" min="<?= $filter->min; ?>" max="<?= $filter->max; ?>">
            </div>
            <div class="price-tot"><span><?php _e('from', 'propeller-ecommerce'); ?></span></div>
            <div class="input-group max">
                <div class="input-group-prepend"><span class="input-group-text"><?= _e('Max.', 'propeller-ecommerce'); ?></span></div>
                <input type="number" name="price_to" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57" value="<?= $filter->max; ?>" class="form-control form-control-sm numeric-max" data-max="<?= $filter->max; ?>" min="<?= $filter->min; ?>" max="<?= $filter->max; ?>">
            </div>
        </form>
    </div>
   
</div>