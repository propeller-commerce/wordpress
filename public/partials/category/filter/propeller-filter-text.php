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
            
            <?php 
                sort($filter->textFilter);

                foreach ($filter->textFilter as $vals) { if ($vals->value != '') { 
                    $checked = '';
                    $count = $vals->count;
                    $vals->value = trim($vals->value);

                    if (isset($_REQUEST[$filter->searchId])) {
                        $filter_val_parts = explode('^', esc_attr($_REQUEST[$filter->searchId]));

                        for ($i = 0; $i < count($filter_val_parts); $i++)
                            $filter_val_parts[$i] = urldecode($filter_val_parts[$i]);

                        $filter_vals = [];
                        
                        foreach($filter_val_parts as $filter_val_part) {
                            $_filter_val_parts = explode('~', $filter_val_part);
                            $filter_vals[] = wp_unslash($_filter_val_parts[0]);
                        }

                        if (in_array(wp_unslash($vals->value), $filter_vals)) {
                            $checked = 'checked';
                        }                                    
                    }

                    if (isset($_REQUEST['active_filter']) && $_REQUEST['active_filter'] == $filter->id)
                        $count = $vals->countActive;
                    else if ($vals->count == 0 && $vals->countActive > 0)
                        $count = $vals->countActive;

                    if ($count == 0 && !$vals->isSelected)
                        continue;
            ?>
                    <div class="form-check test">
                        <input 
                            type="checkbox" 
                            data-id="<?php echo esc_attr($filter->id); ?>"
                            name="<?php echo esc_attr($filter->searchId); ?>"
                            class="form-check-input styled-checkbox" 
                            id='filterForm_<?php echo esc_attr($filter->id); ?>_<?php echo esc_html($vals->value); ?>'
                            value='<?php echo esc_html($vals->value . '~' . $type); ?>'
                            <?php echo (string) $checked; ?>>
                            <label for='filterForm_<?php echo esc_attr($filter->id); ?>_<?php echo esc_attr($vals->value); ?>' title='<?php echo esc_attr($vals->value); ?>'
                                class="form-check-label"><span class="value"><?php echo esc_html($vals->value); ?></span> <span class="totals"> (<span class="filter-count"><?php echo esc_html($count); ?></span>)</span>
                        </label>
                    </div> 
            <?php } } ?>
        </form>
    </div>
</div>