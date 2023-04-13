<?php
    $expanded = true;
?>
<div class="filter" id="<?php echo esc_attr($filter->searchId); ?>">
    <button class="btn-filter" type="button" href="#filtersForm_<?php echo esc_attr($filter->id); ?>" data-toggle="collapse" aria-expanded="<?php echo esc_attr($expanded ? 'true': 'false'); ?>" aria-controls="filterForm_<?php echo esc_attr($filter->id); ?>">
        <span><?php echo esc_html($filter->description); ?></span>
    </button>  
        
    <div class="text-filter collapse <?php echo (bool) $expanded ? 'show': ''; ?>" id="filtersForm_<?php echo esc_attr($filter->id); ?>">
        <form method="get" class="filterForm" id="filterForm_<?php echo esc_attr($filter->id); ?>">
            <input type="hidden" name="prop_value" value="<?php echo esc_attr($this->slug); ?>" />
            <input type="hidden" name="prop_name" value="<?php echo esc_attr($this->prop); ?>" />
            <input type="hidden" name="action" value="<?php echo esc_attr($this->action); ?>" />
            
            <?php foreach ($filter->textFilter as $vals) { if ($vals->value != '') { 
             
                $checked = '';
                if (isset($_REQUEST[$filter->searchId])) {
                    $filter_vals = explode('^', esc_attr($_REQUEST[$filter->searchId]));

                    if (in_array($vals->value . '~' . $type, $filter_vals))
                        $checked = 'checked';
                }
            ?>

                <div class="form-check">
                    <input 
                        type="checkbox" 
                        data-id="<?php echo esc_attr($filter->id); ?>"
                        name="<?php echo esc_attr($filter->searchId); ?>"
                        class="form-check-input styled-checkbox" 
                        id="filterForm_<?php echo esc_attr($filter->id); ?>_<?php echo esc_attr( $vals->value ); ?>"
                        value="<?php echo esc_attr($vals->value) . '~' . esc_attr($type); ?>"
                        <?php echo (string) $checked; ?>>
                        <label for="filterForm_<?php echo esc_attr($filter->id); ?>_<?php echo esc_attr($vals->value); ?>" title="<?php echo esc_attr($vals->value); ?>"
                            class="form-check-label"><span class="value"><?php echo esc_html($vals->value); ?> (<?php echo intval($vals->count); ?>)</span>
                    </label>
                </div> 
            <?php } } ?>
        </form>
    </div>
</div>